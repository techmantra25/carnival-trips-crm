<div>
    <div class="box custom-box">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full">
                    <thead class="uppercase">
                        <tr class="border-b !border-primary/30">
                            <th scope="col" class="!text-center">ITINERARY</th>
                            <th scope="col" class="!text-center">SHARED BY</th>
                            <th scope="col" class="!text-center">CHANNEL</th>
                            <th scope="col" class="!text-center">LINKS</th>
                            <th scope="col" class="!text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                               <td class="!text-center space-y-1">
                                    @php
                                        $isPreset = $log->itinerary_id !== null;
                                        $label = $isPreset ? 'Preset' : 'Customized';
                                        $badgeColor = $isPreset ? 'bg-warning/10 text-warning' : 'bg-success/10 text-success';
                                        $syntax = $isPreset 
                                            ? ($log->itinerary->itinerary_syntax ?? 'N/A') 
                                            : ($log->sent_itinerary->itinerary_syntax ?? 'N/A');
                                    @endphp

                                    <div class="flex items-center justify-center gap-1">
                                        <span class="badge !rounded-full {{ $badgeColor }} cursor-default" title="{{ $label }} Itinerary">
                                            <i class="fa-solid fa-route me-1"></i> {{ $label }}
                                        </span>
                                    </div>

                                    <div class="text-xs text-muted mt-1 truncate max-w-[150px]" title="{{ $syntax }}">
                                        {{ $syntax }}
                                    </div>
                                </td>
                                <td class="!text-center">
                                    {{ $log->sharedBy->name ?? 'System' }}<br>
                                    <small class="text-gray-500">{{ $log->sharedBy->email ?? '' }}</small>
                                </td>
                                <td class="!text-center">
                                    {{ ucfirst($log->channel ?? 'Unknown') }}
                                </td>
                                <td class="!text-center">
                                    <a href="{{ $log->links }}" target="_blank">
                                    <span class="badge gap-2 bg-danger/10 text-danger">
                                        <span class="w-1.5 h-1.5 inline-block bg-danger rounded-full"></span>View Link
                                    </span>
                                    </a>
                                    {{-- <a href="{{ $log->shared_link }}" target="_blank" class="text-blue-600 underline text-sm">
                                        View Link
                                    </a> --}}
                                </td>
                                <td class="!text-center">
                                    <span class="text-xs text-gray-500 block">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y h:i A') }}</span>
                                    <br>
                                    <span class="badge !rounded-full bg-{{ $expandedShareId === $log->id ? 'danger' : 'primary' }}/10 text-{{ $expandedShareId === $log->id ? 'danger' : 'primary' }} cursor-pointer" wire:click="toggleStats({{ $log->id }})">{{ $expandedShareId === $log->id ? 'Hide Stats' : 'View Stats' }}</span>
                                </td>
                            </tr>
                            @if ($expandedShareId === $log->id)
                                <tr class="bg-gray-50">
                                    <td colspan="5">
                                        <div class="p-3">
                                            <h6 class="text-sm font-semibold mb-2 text-primary">Click Details</h6>
                                            @if (count($clickStats))
                                                @php
                                                    $totalClicks = 0;
                                                    $totalSeconds = 0;
                                                @endphp

                                                <table class="table table-sm table-bordered w-full text-xs">
                                                    <thead>
                                                        <tr class="bg-primary text-white">
                                                            <th>Entry Time</th>
                                                            <th>Exit Time</th>
                                                            <th class="text-center">Click Count</th>
                                                            <th>Duration</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($clickStats as $stat)
                                                            @php
                                                                $totalClicks += $stat['click_count'];
                                                                $durationParts = explode(':', $stat['duration']);
                                                                $totalSeconds += ($durationParts[0] * 3600) + ($durationParts[1] * 60) + ($durationParts[2]);
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $stat['entry_time'] ?? '-' }}</td>
                                                                <td>{{ $stat['exit_time'] ?? '-' }}</td>
                                                                <td class="text-center">{{ $stat['click_count'] }}</td>
                                                                <td>{{ $stat['duration'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                        <tr class="bg-gray-100 font-semibold">
                                                            <td colspan="2" class="text-right">Total:</td>
                                                            <td class="text-center">{{ $totalClicks }}</td>
                                                            <td>{{ gmdate("H:i:s", $totalSeconds) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="text-gray-500">No click data found.</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-4">No shared itinerary history found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div wire:loading class="loader">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
