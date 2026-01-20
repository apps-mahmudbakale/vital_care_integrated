<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $deleteId;

    protected $listeners = ['deleteConfirmed' => 'deleteUser'];

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function deleteUser()
    {
        $user = User::find($this->deleteId);
        if ($user) {
            $user->delete();
            session()->flash('success', 'User Deleted Successfully');
            return redirect()->route('app.users.index');
        }
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest() // or orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.users-list', compact('users'));
    }
}
