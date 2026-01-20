<div>
    <div class="table-responsive">
        <table class="table table-hover border-top">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Clinic</th>
                    <th>Appt. Type</th>
                    <th>Specialty</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                    <td><span class="badge bg-{{ $item->type == 'Visit' ? 'primary' : 'secondary' }}">{{ $item->type }}</span></td>
                    <td>{{ $item->clinic->name ?? '-' }}</td>
                    <td>{{ $item->appointmentType->name ?? '-' }}</td>
                    <td>{{ $item->specialty->name ?? '-' }}</td>
                
                    <td>
                        @if($item->type == 'Visit')
                            <span class="badge bg-label-success">Visit ({{ $item->appointment->status ?? 'Completed' }})</span>
                        @else
                            <span class="badge bg-label-info">Appt: {{ $item->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if($item->type == 'Visit')
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="icon-base ti tabler-dots-vertical icon-md"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end m-0">
                                <a href="javascript:;" 
                                    class="dropdown-item" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#global-modal"
                                    data-url="{{ route('app.checkins.complaint', $item->id) }}">
                                    <i class="ti tabler-clipboard-text me-1"></i> Chart Complaint
                                </a>
                                <a href="javascript:void(0)" class="dropdown-item">
                                    <i class="ti tabler-eye me-1"></i> View
                                </a>
                            </div>
                        </div>
                        @else
                           <span class="text-muted small">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">No history found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
