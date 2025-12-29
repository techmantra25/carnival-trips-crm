<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\Lead;
use App\Models\TripPreferenceLog;
use App\Models\TripPreferenceQuestion;

class TripPreferenceForm extends Component
{
    public $lead, $title='';
    public $currentStep = 1;

    public $formResponses = []; // optional if you want to display live answers
    public $totalSteps;
    public $questions = [];
    public $selected = [];

    // Sample question map (customize this)
    // public $questions = [
    //     1 => 'What type of trip are you planning?',
    //     2 => 'Who are you traveling with?',
    //     3 => 'What is your travel budget range?',
    //     4 => 'What kind of destination are you looking for?',
    //     5 => 'How many days do you want the trip to last?',
    //     6 => 'Do you have specific travel dates in mind?',
    // ];

    public function mount($code)
    {
        $this->lead = Lead::where('unique_id', $code)->firstOrFail();
        $this->questions = TripPreferenceQuestion::with('options')->orderBy('step')->get();
        $this->totalSteps = $this->questions->count();
    }

    public function storeAnswer($step, $answer)
    {
        $question = $this->questions[$step - 1]->title ?? 'Step ' . $step;

        TripPreferenceLog::updateOrCreate(
            ['lead_id' => $this->lead->id, 'step' => $step],
            ['question' => $question, 'answer' => $answer]
        );

        $this->formResponses[$step] = $answer;
        $this->selected[$step] = $answer;

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        } else {
            $this->currentStep = $this->totalSteps + 1; // show thank-you view
        }
    }

     public function goToStep($n)
    {
        $n = (int) $n;
        if ($n >= 1 && $n <= $this->totalSteps + 1) {
            $this->currentStep = $n;
        }
    }

     public function finish()
    {
        session()->flash('success', 'Preferences saved successfully.');
        $this->currentStep = $this->totalSteps + 1;
    }

    public function render()
    {
        return view('livewire.website.trip-preference-form')->layout('layouts.frontend.master', ['title' => $this->title]);
    }
}
