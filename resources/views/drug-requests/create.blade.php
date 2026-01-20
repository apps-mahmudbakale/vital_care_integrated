@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <livewire:drug-request-form :patient="$patient" />
</div>
@endsection

@section('modal-content')
    <livewire:drug-request-form :patient="$patient" />
@endsection
