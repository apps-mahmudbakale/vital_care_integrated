@extends('layouts.app')

@section('title', 'Laboratory Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3 mb-0">
            <span class="text-muted fw-light">Settings /</span> Laboratory
        </h4>
        <a href="{{ route('app.settings.index') }}" class="btn btn-label-secondary">
            <i class="ti tabler-arrow-left me-1"></i> Back to Settings
        </a>
    </div>

    <!-- First row: Lab Tests & Categories -->
    <div class="row g-6 mb-6">
        <div class="col-md-6">
            <livewire:settings.laboratory.lab-test-manager />
        </div>
        <div class="col-md-6">
             <livewire:settings.laboratory.lab-category-manager />
        </div>
    </div>

    <!-- Second row: Templates & Parameters -->
    <div class="row g-6">
        <div class="col-md-6">
            <livewire:settings.laboratory.lab-template-manager />
        </div>
        <div class="col-md-6">
            <livewire:settings.laboratory.lab-parameter-manager />
        </div>
    </div>
</div>
@endsection
