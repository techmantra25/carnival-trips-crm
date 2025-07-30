<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Helpers\CustomHelper;
use Livewire\WithPagination;
use App\Models\Designation;

class DesignationCrud extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $common;
    public $name, $designation_id;
    public $updateMode = false;

    public function mount(){
        $this->common = CustomHelper::setHeadersAndTitle('Employee Management', 'Designations');
    }
    public function resetInput()
    {
        $this->name = '';
        $this->designation_id = null;
        $this->updateMode = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:designations,name',
        ]);
        Designation::create(['name' => ucwords($this->name)]);
        $this->resetInput();
        $this->dispatch('refreshComponent');
        session()->flash('success', 'Designation created successfully.');
    }

   public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        $this->designation_id = $designation->id;
        $this->name = $designation->name; // <-- must set this
        $this->updateMode = true;
    }

    public function updateData()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $this->designation_id,
        ]);
        $item = Designation::findOrFail($this->designation_id);
        $item->update(['name' => ucwords($this->name)]);
        $this->resetInput();
        session()->flash('success', 'Designation updated.');
    }

     public function delete($id)
    {
        $this->dispatch('showConfirm', ['itemId' => $id]);
    }
    public function deleteItem($id){
        $delete = Designation::find($id);
        if ($delete) {
            $delete->delete();
            session()->flash('success', 'Deleted successfully.');
        } else {
            session()->flash('error', 'Item not found.');
        }
    }

    public function toggleStatus($id)
    {
        $item = Designation::findOrFail($id);
        $item->status = !$item->status;
        $item->save();
    }
    public function render()
    {
        $designations = Designation::latest()->paginate(20);
        return view('livewire.employee.designation-crud', compact('designations'));
    }

}
