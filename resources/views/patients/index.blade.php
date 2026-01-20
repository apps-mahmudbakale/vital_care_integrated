@extends('layouts.app')

@section('title', 'Patients')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Patient Management /</span> Patients</h4>

        <div class="row">
            <div class="col-12">
                @livewire('patients-list')
            </div>
        </div>
    </div>
@endsection
