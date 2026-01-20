<?php

namespace App\Services;

use App\Models\CheckIn;
use Carbon\Carbon;

class CheckInService
{
  public function hasCheckedInToday($patientId)
  {
    // Get the current date
    $today = Carbon::today();

    // Query the check-in records for the patient for today
    $checkInRecord = CheckIn::where('patient_id', $patientId)
      ->whereDate('check_in_date', $today)
      ->first();

    // If a check-in record is found, return true; otherwise, return false
    return $checkInRecord !== null;
  }

  public function checkIn($patientId)
  {
    // Create a new check-in record for the patient
    CheckIn::create([
      'patient_id' => $patientId,
      'check_in_date' => Carbon::now()
    ]);
  }
}
