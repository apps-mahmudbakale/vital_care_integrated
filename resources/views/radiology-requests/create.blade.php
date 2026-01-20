<div class="modal-header">
    <h5 class="modal-title" id="globalModalLabel">New Radiology Request</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @if(isset($patient))
        <livewire:radiology-request-form :patient="$patient" />
    @else
        <div class="alert alert-danger">Patient context missing.</div>
    @endif
</div>
