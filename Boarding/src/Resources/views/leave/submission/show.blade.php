@extends('boarding::layouts.default')

@section('title', 'Izin | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::leave.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Izin</h2>
            <div class="text-muted">Berikut adalah informasi detail pengajuan izin karyawan!</div>
        </div>
    </div>
    @if ($leave->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Detail pengajuan</div>
                    @if (!$leave->trashed())
                        <a class="btn btn-soft-success btn-sm rounded px-2 py-1" href="{{ route('portal::leave.print', ['leave' => $leave->id]) }}" target="_blank"><i class="mdi mdi-printer-outline"></i> <span class="d-none d-sm-inline">Cetak dokumen (.pdf)</span></a>
                    @endif
                </div>
                <div class="card-body border-top">
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted">Tanggal pengajuan</div>
                            <div class="fw-bold"> {{ $leave->created_at->formatLocalized('%A, %d %B %Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Kategori izin</div>
                            <div class="fw-bold"> {{ $leave->category->name }}</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Tanggal izin yang diajukan</div>
                        <div>
                            @foreach ($leave->dates as $date)
                                <span class="badge bg-soft-secondary text-dark fw-normal user-select-none" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset style="font-size: 14px;">
                                    @isset($date['f']) <i class="mdi mdi-account-network-outline text-danger"></i>
                                @endif {{ strftime('%d %B %Y', strtotime($date['d'])) }} @isset($date['t_s']) pukul {{ $date['t_s'] }} @endisset @isset($date['t_e']) s.d. {{ $date['t_e'] }} @endisset
                                </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="small text-muted mb-1">Deskripsi/catatan/alasan</div>
                            <div class="fw-bold">{{ $leave->description ?: '-' }}</div>
                        </div>
                        <div class="mb-4">
                            <div class="small text-muted mb-1">Status</div>
                            <div>@include('portal::leave.components.status', ['leave' => $leave])</div>
                        </div>
                        <div>
                            <div class="small text-muted mb-1">Lampiran</div>
                            @if (isset($leave->attachment) && Storage::exists($leave->attachment))
                                <a href="{{ Storage::url($leave->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i> Lihat lampiran</a>
                            @else
                                <div> Tidak diunggah </div>
                            @endif
                        </div>
                    </div>
                    @if ($leave->approvables->count())
                        <div class="card-header border-top d-none d-md-block border-0">
                            <div class="row">
                                <div class="col-md-6 small text-muted"> Penanggungjawab </div>
                                <div class="col-md-6 small text-muted"> Persetujuan </div>
                            </div>
                        </div>
                        <div class="card-body border-top">
                            @foreach ($leave->approvables as $approvable)
                                <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                                    <div class="col-md-6">
                                        <div class="text-muted small mb-1">
                                            {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                        </div>
                                        <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h-100 d-sm-flex align-items-center">
                                            <div class="align-self-center badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                            <div class="ms-sm-3 mt-sm-0 mt-2">{{ $approvable->reason }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if ($approvable->history)
                                    <div class="row">
                                        <div class="col-md-6 offset-md-6">
                                            <hr class="text-muted mt-0">
                                            <p class="small text-muted mb-1">Catatan riwayat sebelumnya</p>
                                            {{ $approvable->history->reason }}
                                        </div>
                                    </div>
                                @endif
                                @if (!$loop->last)
                                    <hr class="text-muted">
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-account-box-multiple-outline"></i> Detail karyawan
                    </div>
                    <div class="list-group list-group-flush border-top">
                        @foreach (array_filter([
                'Nama karyawan' => $leave->employee->user->name,
                'NIP' => $leave->employee->kd ?: '-',
                'Jabatan' => $leave->employee->position->position->name ?? '-',
                'Departemen' => $leave->employee->position->position->department->name ?? '-',
                'Manajer' => $leave->employee->position->position->parents->firstWhere('level.value', 4)?->employees->first()->user->name,
            ]) as $label => $value)
                            <div class="list-group-item">
                                <div class="row d-flex align-items-center">
                                    <div class="col-sm-6 col-xl-12">
                                        <div class="small text-muted">{{ $label }}</div>
                                    </div>
                                    <div class="col-sm-6 col-xl-12 fw-bold"> {{ $value }} </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @unless(!$leave->hasApprovables() || $leave->trashed())
                    @if ($leave->hasAllApprovableResultIn('PENDING') || $leave->hasAnyApprovableResultIn('REVISION') || $leave->hasAnyApprovableResultIn('REJECT') || !$leave->hasApprovables())
                        <form class="form-block form-confirm" action="{{ route('portal::leave.submission.destroy', ['leave' => $leave->id]) }}" method="post"> @csrf @method('delete')
                            <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                                <i class="mdi mdi-trash-can-outline me-3"></i>
                                <div>Batalkan pengajuan <br> <small class="text-muted">Hapus data pengajuan {{ $leave->hasApprovables() ? 'sebelum disetujui oleh atasan' : '' }}</small></div>
                            </button>
                        </form>
                    @endif
                @endunless
            </div>
        </div>
    @endsection
