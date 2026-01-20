<?php

namespace App\Livewire;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class RolesList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $deleteId;

    protected $listeners = ['deleteConfirmed' => 'deleteRole'];

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function deleteRole()
    {
        $role = Role::find($this->deleteId);
        if ($role) {
            $role->delete();
            session()->flash('success', 'Role Deleted Successfully');
            return redirect()->route('app.roles.index');
        }
    }

    public function render()
    {
        $roles = Role::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(6); // 6 roles per page for card layout

        return view('livewire.roles-list', compact('roles'));
    }
}
