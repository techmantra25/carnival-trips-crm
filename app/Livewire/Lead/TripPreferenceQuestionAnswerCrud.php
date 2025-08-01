<?php

namespace App\Livewire\Lead;

use Livewire\Component;
use App\Models\TripPreferenceQuestion;
use App\Models\TripPreferenceQuestionOption;
use Illuminate\Support\Facades\DB;

class TripPreferenceQuestionAnswerCrud extends Component
{
    public $questions = [];
    public $existingQuestion = [];
    public $active_modal_change_potisions = 0;

    public function mount()
    {
        $this->questions = TripPreferenceQuestion::with('options')->orderBy('step','ASC')->get()->map(function ($q) {
            return [
                'id' => $q->id,
                'step' => $q->step,
                'title' => $q->title,
                'options' => $q->options->map(fn($opt) => [
                    'id' => $opt->id,
                    'option_text' => $opt->option_text,
                ])->toArray()
            ];
        })->toArray();
    }
    public function ChangePositions()
    {
        $this->active_modal_change_potisions = 1;
    }

    public function addQuestion()
    {
        $step = 1;
        foreach ($this->questions as $index => &$question) {
            $question['step'] = $step;
            if (!empty($question['id'])) {
                // Update in database
                TripPreferenceQuestion::where('id', $question['id'])->update(['step' => $step]);
            }
             // Update local state
            $step++;
        }

        // Reset the Livewire state
        $this->questions = array_values($this->questions);

        $this->questions[] = [
            'title' => '',
            'step' => count($this->questions) + 1,
            'options' => [''],
        ];
    }

    public function addOption($qIndex)
    {
        $this->questions[$qIndex]['options'][] = [
            'id' => null,
            'option_text' => '',
        ];
    }

    public function removeOption($qIndex, $oIndex)
    {
        $option = $this->questions[$qIndex]['options'][$oIndex] ?? null;

        // Delete from DB if option has an ID
        if (isset($option['id'])) {
            TripPreferenceQuestionOption::where('id', $option['id'])->delete();
        }

        // Remove from local array
        unset($this->questions[$qIndex]['options'][$oIndex]);

        // Re-index options
        $this->questions[$qIndex]['options'] = array_values($this->questions[$qIndex]['options']);
    }


    public function removeQuestion($qIndex)
    {
        $question = $this->questions[$qIndex];
        if (!empty($question['id'])) {
            // Ask frontend for confirmation before deletion
            $this->dispatch('showConfirm', ['id' => $question['id'], 'index' => $qIndex]);
        } else {
            // No DB record, remove directly
            unset($this->questions[$qIndex]);
            $this->questions = array_values($this->questions);
        }
    }
  public function deleteConfirmed($id, $index)
    {
        // Delete options and question from DB
        TripPreferenceQuestionOption::where('trip_question_id', $id)->delete();
        TripPreferenceQuestion::where('id', $id)->delete();

        // Remove from local array
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions); // Re-index

        // Reset step values based on current order
        foreach ($this->questions as $i => &$question) {
            $question['step'] = $i + 1;
        }
    }


    public function QuestionUpdate($qIndex)
    {
        $q = $this->questions[$qIndex];

        if (!empty(trim($q['title']))) {
            $question = TripPreferenceQuestion::updateOrCreate(
                ['step' => $q['step']],
                ['title' => $q['title']]
            );

            $this->questions[$qIndex]['id'] = $question->id;
        }
    }

   public function OptionUpdate($qIndex, $oIndex)
    {
        $q = $this->questions[$qIndex];
        $optionText = $q['options'][$oIndex]['option_text'] ?? null;
        $optionId = $q['options'][$oIndex]['id'] ?? null;

        if (!isset($q['id']) || !$optionText) {
            return;
        }
        if ($optionId) {
            // Update existing
            TripPreferenceQuestionOption::where('id', $optionId)->update([
                'option_text' => $optionText,
            ]);
        } else {
            // Create new and update local array with new ID
            $new = TripPreferenceQuestionOption::create([
                'trip_question_id' => $q['id'],
                'option_text' => $optionText,
            ]);

            $this->questions[$qIndex]['options'][$oIndex]['id'] = $new->id;
        }
    }

    public function updateQuestionPosition($orderedIds){
        // Step 2: Assign new steps
        foreach ($orderedIds as $index => $questionId) {
            TripPreferenceQuestion::where('id', $questionId)->update(['step' => $index + 1]);
        }
    }
    public function render()
    {
        $this->existingQuestion = TripPreferenceQuestion::get();
        return view('livewire.lead.trip-preference-question-answer-crud');
    }
}
