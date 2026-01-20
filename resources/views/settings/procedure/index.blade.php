@extends('layouts.app')

@section('title', 'Procedure Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3 mb-0">
            <span class="text-muted fw-light">Settings /</span> Procedures
        </h4>
        <a href="{{ route('app.settings.index') }}" class="btn btn-label-secondary">
            <i class="ti tabler-arrow-left me-1"></i> Back to Settings
        </a>
    </div>

    <!-- First row: Procedure Categories -->
    <div class="row g-6 mb-6">
        <div class="col-md-12">
            <livewire:settings.procedure.procedure-category-manager />
        </div>
    </div>

    <!-- Second row: Procedures -->
    <div class="row g-6">
        <div class="col-md-12">
            <livewire:settings.procedure.procedure-manager />
        </div>
    </div>
</div>
@endsection
