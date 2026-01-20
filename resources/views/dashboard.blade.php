@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}" />
@endpush

@section('content')
    <!-- Paste all the content from your original <div class="container-xxl flex-grow-1 container-p-y"> ... </div> here -->
    <h4 class="py-3 mb-4">Analytics Dashboard</h4>

    <div class="row g-6">
        <!-- Your cards, charts, tables etc. -->
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endpush
