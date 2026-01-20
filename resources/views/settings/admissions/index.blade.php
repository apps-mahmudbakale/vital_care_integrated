@extends('layouts.app')

@section('title', 'Admissions Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3 mb-0">
            <span class="text-muted fw-light">Settings / Admissions /</span> Wards & Beds
        </h4>
        <a href="{{ route('app.settings.index') }}" class="btn btn-label-secondary">
            <i class="ti tabler-arrow-left me-1"></i> Back to Settings
        </a>
    </div>

    <div class="row g-6 mb-6">
        <div class="col-md-6">
            <livewire:settings.admissions.ward-manager />
        </div>
        <div class="col-md-6">
            <livewire:settings.admissions.bed-manager />
        </div>
    </div>
</div>
@endsection
