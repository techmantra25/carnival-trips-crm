<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadUrlClick;
use App\Models\LeadUrlShare;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    public function update_lead_status()
    {
        $statusUpdateSummary = [];

        // Get leads that are not in final states
        $leads = Lead::whereNotIn('status', [
            'Cancelled', 'Confirmed', 'Closed'
        ])->get();

        foreach ($leads as $lead) {
            $updated = false;
            $currentStatus = $lead->status;

            // Step 1: If itinerary was sent, move to "Link Generated"
            if ($currentStatus === 'Verified Lead') {
                $linkSent = LeadUrlShare::where('lead_id', $lead->id)
                    ->where('itinerary_id', $lead->itinerary->id ?? null)
                    ->exists();
                if ($linkSent) {
                    $lead->status = 'Link Generated';
                    $updated = true;
                }
            }

            // Step 2: Click-based behavior
            if (in_array($currentStatus, ['Link Generated', 'Active Lead'])) {
                
                $leadUrlClickCount = LeadUrlClick::where('lead_id', $lead->id)->count();
                // Step 1: If any click data exists, mark as "Active Lead"
                // Only if status is NOT already "Active Lead", "Pipeline", or "High Intend Lead"
                if ($leadUrlClickCount > 0 && in_array($lead->status, ['Link Generated'])) {
                    $lead->status = 'Hot Leads';
                    $updated = true;
                }
                if ($leadUrlClickCount > 2 && in_array($lead->status, ['Link Generated', 'Hot Leads'])) {
                    $lead->status = 'Active Lead';
                    $updated = true;
                }
              
                $stats = CustomHelper::getLeadUrlClickStatsByLeadId($lead->id);
                $totalClicks = collect($stats)->sum('total_clicks');
                $totalTimeSpent = collect($stats)->sum('total_time_spent_in_seconds');

                // Get thresholds from env
                $highIntendClicks = env('HIGH_INTEND_LEAD_CLICKS', 10);
                $highIntendTime = env('HIGH_INTEND_LEAD_TIME', 600); // 10 mins default

                $pipelineClicks = env('PIPELINE_CLICKS', 15);
                $pipelineTime = env('PIPELINE_TIME', 900); // 15 mins default

                // 90% thresholds
                $highIntendClickThreshold = $highIntendClicks * 0.9;
                $highIntendTimeThreshold = $highIntendTime * 0.9;

                $pipelineClickThreshold = $pipelineClicks * 0.9;
                $pipelineTimeThreshold = $pipelineTime * 0.9;

                if ($totalClicks >= $pipelineClickThreshold && $totalTimeSpent >= $pipelineTimeThreshold) {
                    $lead->status = 'Pipeline';
                    $updated = true;
                } elseif ($totalClicks >= $highIntendClickThreshold && $totalTimeSpent >= $highIntendTimeThreshold) {
                    $lead->status = 'High Intend Lead';
                    $updated = true;
                }
            }

            // Step 3: CNP (No call / no response from Active Lead)
            if ($currentStatus === 'Active Lead') {
                $lastUpdated = $lead->updated_at;
                $noResponseDuration = now()->diffInHours($lastUpdated);

                if ($noResponseDuration >= 48) {
                    $lead->status = 'CNP';
                    $updated = true;
                }
            }

            if ($updated) {
                $previous = $lead->status;
                $lead->save();

                // Log status change
                DB::table('lead_activity_logs')->insert([
                    'lead_id' => $lead->id,
                    'updated_by' => Admin::where('role', 'super_admin')->value('id'),
                    'message' => json_encode([
                        'action' => 'Auto Status Update',
                        'previous_status' => $currentStatus,
                        'new_status' => $lead->status,
                        'timestamp' => now()->toDateTimeString()
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Count status update for summary
                $statusUpdateSummary[$lead->status] = ($statusUpdateSummary[$lead->status] ?? 0) + 1;
            }
        }

        // Return status update summary as JSON message
        if (!empty($statusUpdateSummary)) {
            Log::info('Lead status updated summary:', $statusUpdateSummary);
            return response()->json([
                'message' => 'Leads updated successfully.',
                'status_summary' => $statusUpdateSummary
            ]);
        } else {
            Log::info('No lead status was updated in the scheduled cron job.');
            return response()->json([
                'message' => 'No lead status was updated.'
            ]);
        }
    }

}
