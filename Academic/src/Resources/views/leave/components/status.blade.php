@if ($leave->trashed())
    <div class="badge bg-soft-danger text-danger fw-normal"><i class="mdi mdi-trash-can-outline"></i> Dihapus</div>
@else
    @if (collect($leave->dates)->max('d') < date('Y-m-d'))
        <div class="badge bg-soft-secondary text-dark fw-normal"><i class="mdi mdi-check"></i> Selesai</div>
    @else
        @if ($leave->hasApprovables() && !$leave->hasAllApprovableResultIn('APPROVE'))
            @if ($leave->approvableTypeIs('approvable'))
                @if ($leave->hasAllApprovableResultIn('APPROVE'))
                    <div class="badge bg-soft-success text-success fw-normal"><i class="mdi mdi-exit-to-app"></i> Pengajuan disetujui</div>
                @elseif ($leave->hasAnyApprovableResultIn('REJECT'))
                    <div class="badge bg-soft-danger text-danger fw-normal"><i class="mdi mdi-exit-to-app"></i> Pengajuan ditolak</div>
                @else
                    <div class="badge bg-soft-primary text-primary fw-normal"><i class="mdi mdi-exit-to-app"></i> Pengajuan</div>
                @endif
            @else
                <div class="badge bg-soft-warning text-warning fw-normal"><i class="mdi mdi-progress-upload"></i> Pembatalan</div>
            @endif
        @else
            <div class="badge bg-soft-success text-success fw-normal"><i class="mdi mdi-check"></i> Disetujui</div>
        @endif
    @endif
@endif
