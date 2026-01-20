<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patient;

class PatientsList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search = '';

    public function render()
    {
        $settings = app(\App\Settings\SystemSettings::class);
        $patients = Patient::with('user')
            ->where(function ($query) {
                $query->where('hospital_no', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('firstname', 'like', '%' . $this->search . '%')
                            ->orWhere('lastname', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(12);

        return view('livewire.patients-list', [
            'patients' => $patients,
            'settings' => $settings
        ]);
    }
}
