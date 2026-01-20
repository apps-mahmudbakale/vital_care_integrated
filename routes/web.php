<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('app.dashboard');
});

Route::get('editor', function () {
    return view('editor');
})->name('editor');

Auth::routes();
Route::group(['prefix' => 'app', 'as' => 'app.', 'middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('trix/attachments', [\App\Http\Controllers\TrixAttachmentController::class, 'store'])->name('trix.attachments');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('patients', \App\Http\Controllers\PatientController::class);
    Route::resource('appointments', \App\Http\Controllers\AppointmentController::class);
    Route::get('vitals/create/{patient}', [\App\Http\Controllers\VitalsController::class, 'create'])->name('vitals.create');
    Route::get('checkins/complaint/{checkIn}', [\App\Http\Controllers\CheckInController::class, 'complaint'])->name('checkins.complaint');
    Route::post('checkins/charting/{checkIn}', [\App\Http\Controllers\CheckInController::class, 'storeCharting'])->name('checkins.charting.store');
    Route::get('checkins/charting/drugs/{store}', [\App\Http\Controllers\CheckInController::class, 'getDrugsByStore'])->name('checkins.charting.drugs');
    Route::get('allergies/create/{patient}', [\App\Http\Controllers\AllergyController::class, 'create'])->name('allergies.create');
    Route::get('allergies/edit/{allergy}', [\App\Http\Controllers\AllergyController::class, 'edit'])->name('allergies.edit');
    Route::delete('allergies/{allergy}', [\App\Http\Controllers\AllergyController::class, 'destroy'])->name('allergies.destroy');
    Route::get('lab-requests/create/{patient}', [\App\Http\Controllers\LabRequestController::class, 'create'])->name('lab-requests.create');
    Route::resource('lab-requests', \App\Http\Controllers\LabRequestController::class)->except(['create']);
    Route::get('lab-requests/{labRequest}/findings', [\App\Http\Controllers\LabRequestController::class, 'findings'])->name('lab-requests.findings');
    Route::post('lab-requests/findings', [\App\Http\Controllers\LabRequestController::class, 'addFindings'])->name('lab-requests.add_findings');
    Route::get('lab-requests/{labRequest}/print', [\App\Http\Controllers\LabRequestController::class, 'print'])->name('lab-requests.print');
    Route::post('lab-requests/{labRequest}/approve', [\App\Http\Controllers\LabRequestController::class, 'approve'])->name('lab-requests.approve');
    Route::get('radiology-requests/create/{patient}', [\App\Http\Controllers\RadiologyRequestController::class, 'create'])->name('radiology-requests.create');
    Route::resource('radiology-requests', \App\Http\Controllers\RadiologyRequestController::class)->except(['create']);
    Route::get('radiology-requests/{radiologyRequest}/findings', [\App\Http\Controllers\RadiologyRequestController::class, 'findings'])->name('radiology-requests.findings');
    Route::post('radiology-requests/findings', [\App\Http\Controllers\RadiologyRequestController::class, 'addFindings'])->name('radiology-requests.add_findings');
    Route::get('radiology-requests/{radiologyRequest}/print', [\App\Http\Controllers\RadiologyRequestController::class, 'print'])->name('radiology-requests.print');
    Route::post('radiology-requests/{radiologyRequest}/approve', [\App\Http\Controllers\RadiologyRequestController::class, 'approve'])->name('radiology-requests.approve');
    Route::resource('billing', \App\Http\Controllers\BillingController::class);
    Route::get('billing/payment/{ref}', [\App\Http\Controllers\BillingController::class, 'paymentForm'])->name('billing.payment_form');
    Route::post('billing/payment', [\App\Http\Controllers\BillingController::class, 'processPayment'])->name('billing.process_payment');
    Route::get('billing/receipt/{ref}', [\App\Http\Controllers\BillingController::class, 'receipt'])->name('billing.receipt');

    Route::get('drug-requests/create/{patient}', [\App\Http\Controllers\DrugRequestController::class, 'create'])->name('drug-requests.create');
    Route::resource('drug-requests', \App\Http\Controllers\DrugRequestController::class)->except(['create']);

    // Settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.', 'middleware' => ['permission:read-settings']], function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
        Route::get('/admissions', [SettingsController::class, 'admissions'])->name('admissions');
        Route::get('/consultation', [SettingsController::class, 'consultation'])->name('consultation');
        Route::get('/pharmacy', [SettingsController::class, 'pharmacy'])->name('pharmacy');
        Route::get('/lab', [SettingsController::class, 'lab'])->name('lab');
        Route::get('/radiology', [SettingsController::class, 'radiology'])->name('radiology');
        Route::get('/antenatal', [SettingsController::class, 'antenatal'])->name('antenatal');
        Route::get('/procedure', [SettingsController::class, 'procedure'])->name('procedure');
    });
});
