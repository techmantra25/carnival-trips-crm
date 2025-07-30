<?php

namespace App\Repositories;

use App\Models\Lead;

class LeadRepository
{
    public function getAllLeads(int $perPage = 15)
    {
        return Lead::orderBy('id', 'DESC')->paginate($perPage);
    }

    public function findLeadById($id)
    {
        return Lead::findOrFail($id);
    }

    public function createLead(array $data)
    {
        // dd($data);
        $lead = new Lead(); // Create a new instance of the Lead model
        // Assign the validated data to the model's properties
        $lead->unique_id = $data['unique_id'];
        $lead->customer_name = $data['customer_name'];
        $lead->customer_email = $data['customer_email'];
        $lead->customer_mobile = $data['customer_mobile'];
        $lead->country_code = '91';
        $lead->customer_whatsapp = $data['customer_whatsapp'];

        $lead->travel_location = $data['travel_location'];
        $lead->travel_duration = $data['nights'];

        $lead->travel_in_days = $data['days'];
        $lead->travel_in_nights = $data['nights'];
        $lead->travel_duration_text = $data['duration_text'];

        $lead->arrival_date = $data['arrival_date'];
        
        $lead->number_of_adults = $data['number_of_adults'];
        $lead->number_of_children = $data['number_of_children'];
        $lead->number_of_travellor = $data['number_of_adults']+$data['number_of_children'];

        $lead->lead_type =$data['lead_type'];
        $lead->lead_source =$data['lead_source'];
        $lead->user_id =$data['user_id'];
    
        // Save the model to the database
        $lead->save();
       
    
        return $lead;
    }

    public function updateLead(Lead $lead, array $data)
    {
        $lead->update($data);
        return $lead;
    }

    public function deleteLead(Lead $lead)
    {
        return $lead->delete();
    }
    public function updateLeadStatus(array $data){
        $Lead = $this->findLeadById($data['id']);
        $Lead->status = $data['lead_status'];
        $Lead->save();
        return $Lead;
    }
}
