<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;

class NavbarSearch extends Component
{
    public $query = '';
    public $results = [];

    public function updatedQuery()
    {
        if (strlen($this->query) >= 2) {
            $this->results = Patient::with('user')
                ->whereHas('user', function ($q) {
                    $q->where('firstname', 'like', '%' . $this->query . '%')
                      ->orWhere('lastname', 'like', '%' . $this->query . '%');
                })
                ->orWhere('hospital_no', 'like', '%' . $this->query . '%')
                ->limit(5)
                ->get();
        } else {
            $this->results = [];
        }
    }

    public function selectPatient($id)
    {
        return redirect()->route('app.patients.show', $id);
    }

    public function render()
    {
        return view('livewire.navbar-search');
    }
}
