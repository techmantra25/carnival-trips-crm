<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\Lead;
use App\Models\TripPreferenceLog;

class TripPreferenceForm extends Component
{
    public $lead, $title='';
    public $currentStep = 1;

    public $formResponses = []; // optional if you want to display live answers

    // Sample question map (customize this)
    public $questions = [
        1 => 'What type of trip are you planning?',
        2 => 'Who are you traveling with?',
        3 => 'What is your travel budget range?',
        4 => 'What kind of destination are you looking for?',
        5 => 'How many days do you want the trip to last?',
        6 => 'Do you have specific travel dates in mind?',
    ];

    public function mount($code)
    {
        $this->lead = Lead::where('unique_id', $code)->firstOrFail();
    }

    public function storeAnswer($step, $answer)
    {
        $question = $this->questions[$step] ?? 'Step ' . $step;

        TripPreferenceLog::updateOrCreate(
            ['lead_id' => $this->lead->id, 'step' => $step],
            ['question' => $question, 'answer' => $answer]
        );

        $this->formResponses[$step] = $answer;

        $this->currentStep++;
    }
    public function render()
    {
        return view('livewire.website.trip-preference-form')->layout('layouts.frontend.master', ['title' => $this->title]);
    }
}
