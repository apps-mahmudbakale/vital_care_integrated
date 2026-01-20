<?php

namespace App\Http\Controllers;

use App\Models\Antenatal;
use App\Models\Drug;
use App\Models\Billing;
use App\Models\Laboratory;
use App\Models\Radiology;
use App\Models\Speciality;
use App\Models\DrugRequest;
use App\Models\Payment;
use App\Models\CashPoint;
use Illuminate\Http\Request;
use App\Services\ServiceRequestHandler;

class BillingController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $billings = Billing::with(['patient.user', 'user'])->latest()->paginate(20);
    return view('billing.index', compact('billings'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
//    dd($request->all());
    $request_ref = str()->random(6);
    if ($request->service_category == 'consultations') {
      $consult = Speciality::find($request->service_id);

      $serviceHandler = new ServiceRequestHandler();
      $billingRecord = $serviceHandler->handleServiceRequest($consult->name, $request->patient_id, 'consultations', $request->service_type, $request_ref, 1);
      // Generate unique access code
//      $accessCode = 'FU-' . strtoupper(Str::random(6));

    } elseif ($request->service_category == 'laboratory') {
      $lab = Laboratory::find($request->service_id);
      $serviceHandler = new ServiceRequestHandler();
      $billingRecord = $serviceHandler->handleServiceRequest($lab->name, $request->patient_id, 'laboratory', $request_ref, 1);
    } elseif ($request->service_category == 'pharmacy') {
      $drug = Drug::find($request->service_id);
      $serviceHandler = new ServiceRequestHandler();
      $billingRecord = $serviceHandler->handleServiceRequest($drug->name, $request->patient_id, 'pharmacy', $request_ref, 1);
    }elseif ($request->service_category == 'ophthicals') {
      //  dd($request->all());
      $optic = Antenatal::find($request->service_id);
      $serviceHandler = new ServiceRequestHandler();
      $billingRecord = $serviceHandler->handleServiceRequest($optic->name, $request->patient_id, 'ophthicals', $request_ref, 1);
    }elseif ($request->service_category == 'radiology'){
      $imaging = Radiology::find($request->service_id);
      $serviceHandler = new ServiceRequestHandler();
      $billingRecord = $serviceHandler->handleServiceRequest($imaging->name, $request->patient_id, 'radiology', $request_ref, 1);
    }
    return redirect()->back()->with('success', 'Bill Added Successfully!');

  }

  /**
   * Display the specified resource.
   */
  public function show($ref)
  {
    $amount = Billing::where('bill_ref', $ref)
      ->sum('amount');
    $billing = Billing::with('patient')->where('bill_ref', $ref)->first();

    return view('billing.show', compact('amount', 'ref', 'billing'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Billing $billing)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Billing $billing)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Billing $billing)
  {
    //
  }
  public function paymentForm($ref)
  {
      $amount = Billing::where('bill_ref', $ref)->sum('amount');
      $items = Billing::where('bill_ref', $ref)->get();
      $billing = $items->first(); // For patient info
      $cashpoints = CashPoint::all();
      
      return view('billing.payment', compact('amount', 'items', 'billing', 'ref', 'cashpoints'));
  }

  public function processPayment(Request $request)
  {
      $request->validate([
          'bill_ref' => 'required',
          'amount_paid' => 'required|numeric',
          'payment_method' => 'required', // Cash, POS, Transfer, Wallet
      ]);

      // Logic to process payment
      Billing::where('bill_ref', $request->bill_ref)->update(['status' => 1]);

      // Store Payment Record
      Payment::create([
          'bill_ref' => $request->bill_ref,
          'payment_method' => $request->payment_method,
          'paying_amount' => $request->amount_paid,
          'user_id' => auth()->id(),
      ]);

      return redirect()->back()->with('success', 'Payment processed successfully.');
  }

  public function receipt($ref)
  {
      $items = Billing::where('bill_ref', $ref)->get();
      $billing = $items->first();
      $amount = $items->sum('amount');
      $payment = Payment::where('bill_ref', $ref)->latest()->first();
      
      return view('billing.receipt', compact('items', 'billing', 'amount', 'payment', 'ref'));
  }
}
