<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\FollowUp;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\TempPatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
  /**
   * Process wallet payment and validate balance
   */
  private function processWalletPayment(Patient $patient, float $amount): bool
  {
    if ($patient->wallet->balance < $amount) {
      return false;
    }

    $patient->wallet->balance -= $amount;
    $patient->wallet->save(); // Save the updated wallet balance

    return true;
  }

  /**
   * Create payment and update billing status
   */
  private function createPaymentAndUpdateBilling(array $paymentData, int $billingId): Payment
  {
    $payment = Payment::create($paymentData);
    Billing::where('id', $billingId)->update(['status' => true]);
    return $payment;
  }

  /**
   * Store a newly created payment resource
   */
  public function store(Request $request)
  {
    $request->validate([
      'patient_id' => 'required|exists:patients,id',
      'billing_id' => 'required|string', // Allow string for bill_ref like 64N2yT
      'payment_method_id' => 'required|exists:payment_methods,id',
      'amount' => 'required|numeric|min:0',
    ]);

    try {
      return DB::transaction(function () use ($request) {
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);
        $patient = Patient::findOrFail($request->patient_id);

        // Find billings by bill_ref
        $billings = Billing::where('bill_ref', $request->billing_id)
          ->where('user_id', $patient->id) // Ensure billing belongs to patient
          ->get();

        if ($billings->isEmpty()) {
          Log::warning("No billing found for bill_ref: {$request->billing_id}");
          return redirect()->back()->with(['error' => 'Invalid or non-existent billing reference']);
        }

        // Handle wallet payment
        if (strcasecmp($paymentMethod->name, 'wallet') === 0
          && !$this->processWalletPayment($patient, $request->amount)) {
          dd($patient);
          return redirect()->route('app.patients.show', $patient->id)
            ->with(['error' => 'Insufficient balance']);
        }



        $paymentData = [
          'cashpoint_id' => $request->location_id,
          'payment_method_id' => $request->payment_method_id,
          'user_id' => Auth::id(),
        ];

        $payment = null;
        // Process multiple billings if they exist
        if ($billings->count() > 1) {
          foreach ($billings as $billing) {
            $paymentData['billing_id'] = $billing->id;
            $paymentData['paying_amount'] = $billing->amount;
            $payment = $this->createPaymentAndUpdateBilling($paymentData, $billing->id);
          }
        } else {
          // Single billing
          $billing = $billings->first();
          $paymentData['billing_id'] = $billing->id;
          $paymentData['paying_amount'] = $request->amount;
          $payment = $this->createPaymentAndUpdateBilling($paymentData, $billing->id);
        }

        $service = $billings->first(); // Use first billing for service check

        // Handle follow-up consultation
        if (strtolower($service->service) === strtolower('consultations:Follow-Up')) {
          $accessCode = 'OPC-' . substr(rand(100000, 999999) . time(), 0, 6);
          $access = FollowUp::create([
            'patient_id' => $service->user_id,
            'access_code' => $accessCode,
          ]);

          return view('billing.print-follow', compact('access', 'patient'));
        }

        return view('billing.print', [
          'billing' => $service,
          'payment' => $payment,
          'bill_ref' => $request->billing_id
        ])->with(['success' => 'Payment added successfully']);
      });
    } catch (\Exception $e) {
      Log::error('Payment processing failed: ' . $e->getMessage(), [
        'bill_ref' => $request->billing_id,
        'patient_id' => $request->patient_id
      ]);
      return redirect()->back()->with(['error' => 'Payment processing failed: Invalid billing reference or server error']);
    }
  }

  /**
   * Show form for creating new payment method
   */
  public function newMethod()
  {
    return view('payments.new-method');
  }

  /**
   * Save new payment method
   */
  public function saveMethod(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:payment_methods',
    ]);

    try {
      PaymentMethod::create($request->only(['name']));
      return redirect()->back()->with(['success' => 'Payment Method added successfully']);
    } catch (\Exception $e) {
      Log::error('Payment method creation failed: ' . $e->getMessage());
      return redirect()->back()->with(['error' => 'Failed to add payment method']);
    }
  }

  public  function EditMethod($id)
  {
    $method = PaymentMethod::where('id', $id)->first();
    return view('payments.edit-method', compact('method'));
  }


  public  function  UpdateMethod(Request $request)
  {
      $method = PaymentMethod::where('id', $request->method_id)->first();

      $method->update(['name' => $request->name]);

      return redirect()->back()->with('success', 'Payment Method Updated');
  }

  /**
   * Store payment for enrollment
   */
  public function storeEnroll(Request $request)
  {
    try {
      return DB::transaction(function () use ($request) {
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);
        $tempPatient = TempPatient::findOrFail($request->patient_id);

        // Handle wallet payment
        if ($paymentMethod->name === 'Wallet') {
          $patient = Patient::findOrFail($request->patient_id);
          if (!$this->processWalletPayment($patient, $request->amount)) {
            return redirect()->back()->with(['error' => 'Insufficient balance']);
          }
        }

        // Create payment
        $payment = $this->createPaymentAndUpdateBilling([
          'billing_id' => $request->billing_id,
          'cashpoint_id' => $request->location_id,
          'payment_method_id' => $request->payment_method_id,
          'paying_amount' => $request->amount,
          'user_id' => Auth::id(),
        ], $request->billing_id);

        $billing = Billing::where('id', $request->billing_id)->first();


        // Update temp patient with access code
        $accessCode = 'OPC-' . substr(rand(100000, 999999) . time(), 0, 6);
        $tempPatient->update(['accesscode' => $accessCode]);

        return view('billing.print-new', compact('tempPatient', 'billing'));
      });
    } catch (\Exception $e) {
      Log::error('Enrollment payment processing failed: ' . $e->getMessage());
      return redirect()->back()->with(['error' => 'Enrollment payment processing failed']);
    }
  }

  public function DeleteMethod(Request $request)
  {
      $method = PaymentMethod::where('id', $request->method_id)->first();

      $method->delete();

      return redirect()->back()->with('success', 'Payment Method Deleted');
  }
}
