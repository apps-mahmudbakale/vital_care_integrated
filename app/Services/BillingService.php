<?php

namespace App\Services;

use App\Http\Livewire\Pharmacy;
use App\Models\Antenatal;
use App\Models\Bed;
use App\Models\Drug;
use App\Models\Laboratory;
use App\Models\Procedure;
use App\Models\Radiology;
use App\Models\Speciality;
use Illuminate\Support\Facades\DB;

class BillingService
{


  public function admissions()
  {
    $admissions = Bed::with('ward')->get();
    return $admissions;
  }

  function ophthicals()
  {
    $antenatal = Antenatal::all();
    return $antenatal;
  }

  public function laboratory()
  {
    $laboratory = Laboratory::all();
    return $laboratory;
  }
  public function procedure()
  {
    $procedure = Procedure::all();
    return $procedure;
  }

  public function pharmacy()
  {
    $pharmacy = Drug::all();
    return $pharmacy;
  }
  public function radiology()
  {
    $radiology = Radiology::all();
    return $radiology;
  }
  public function consultations()
  {
    $consultations = Speciality::all();
    return $consultations;
  }

  public function consultationsServices($id)
  {
    $consultationsServices = Speciality::where('id', $id)->first();
    return $consultationsServices;
  }
}
