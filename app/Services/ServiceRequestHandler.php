<?php

namespace App\Services;

use App\Models\Billing;
use Illuminate\Support\Facades\Auth;

class ServiceRequestHandler
{
  public function handleServiceRequest($serviceName, $patientId, $serviceCategory, $billingRef, $qty)
  {
    // Determine the service type dynamically
    $serviceType = $this->detectServiceType($serviceName);


    // Fetch the service details
    $service = $serviceType ? $serviceType::where('name', $serviceName)->first() : null;
    if (!$service) {
      return null; // Service not found
    }

    // Calculate the cost
    $amount = $this->calculateAmount($serviceType, $service, $qty ?: 1);


    // Create a billing record
    return Billing::create([
      'service'    => $serviceCategory . ':' . $serviceName,
      'service_id' => $service->id,
      'user_id'    => $patientId,
      'quantity'   => $qty ?: 1,
      'amount'     => $amount,
      'bill_ref'   => $billingRef,
      'payer_id'   => Auth::id(),
      'status'     => 0,
    ]);
  }

  private function detectServiceType($serviceName)
  {
    $models = [
      'Speciality' => \App\Models\Speciality::class,
      'Drug'       => \App\Models\Drug::class,
      'Laboratory' => \App\Models\LabTest::class,
      'Radiology'  => \App\Models\RadiologyTest::class,
      'Procedure'  => \App\Models\Procedure::class,
      'Bed'        => \App\Models\Bed::class,
    ];

    foreach ($models as $modelClass) {
      if (class_exists($modelClass)) {
        if ($modelClass::where('name', $serviceName)->exists()) {
          return $modelClass;
        }
      }
    }

    return null;
  }

  private function calculateAmount($serviceType, $service, $qty)
  {
    if ($serviceType === \App\Models\Procedure::class) {
      return ($service->procedure_cost ?? 0)
        + ($service->theatre_cost ?? 0)
        + ($service->anasthesia_cost ?? 0)
        + ($service->surgeon_fee ?? 0);
    }

    if ($serviceType === \App\Models\Drug::class) {
      return ($service->selling_price ?? 0) * $qty;
    }

    return ($service->price ?? 0) * $qty;
  }

  public function isBilled($serviceId, $serviceName, $ref)
  {
    $billing = Billing::where('service_id', $serviceId)
      ->where('service', $serviceName)
      ->where('bill_ref', $ref)
      ->first();

    return $billing ? $billing->status : "0";
  }
}
