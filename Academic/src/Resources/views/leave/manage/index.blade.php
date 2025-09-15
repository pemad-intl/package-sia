@extends('academic::layouts.default')

@section('title', 'Kelola pengajuan | ')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="mdi mdi-calendar-multiselect"></i> Data pengajuan izin
                            </div>
                            @if (Session::has('success'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert alert-info">
                                        {{ Session::get('success') }}
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('msg-gagal'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert-danger alert">
                                        {{ Session::get('msg-gagal') }}
                                    </div>
                                </div>
                            @endif
                            
                            <div class="card-body border-top border-light">
                                <form class="form-block row gy-2 gx-2" action="{{ route('academic::leave.manage.index') }}" method="get">
                                    <input name="pending" type="hidden" value="{{ request('pending') }}">
                                    <div class="flex-grow-1 col-auto">
                                        <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                                    </div>
                                    <div class="col-auto">
                                        <a class="btn btn-light" href="{{ route('academic::leave.manage.index', request()->only('pending')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                                    </div>
                                </form>
                            </div>
                            @if (request('pending'))
                                <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                                    Hanya menampilkan pengajuan yang masih tertunda/berstatus <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-timer-outline"></i> Menunggu</div>
                                </div>
                            @endif
                            <div class="table-responsive table-responsive-xl container">
                                <table class="mb-0 table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Karyawan</th>
                                            <th>Kategori</th>
                                            <th nowrap>Tgl pengajuan</th>
                                            <th nowrap>Waktu izin</th>
                                            <th class="text-center">Lampiran</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($leaves as $leave)
                                            <tr @if ($leave->trashed()) class="text-muted" @endif>
                                                <td>{{ $leave->student->user->profile->name }}</td>
                                                <td style="min-width: 200px;" class="py-3">
                                                    <div>{{ $leave->category->name }}</div>
                                                    <small class="text-muted">{{ $leave->description }}</small>
                                                </td>
                                                <td class="small">{{ $leave->created_at->formatLocalized('%d %B %Y') }}</td>
                                                <td style="min-width: 200px;">
                                                    @foreach (collect($leave->dates)->take(3) as $date)
                                                        <span class="badge bg-soft-secondary text-dark fw-normal user-select-none {{ isset($date['c']) ? 'text-decoration-line-through' : '' }}" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset>
                                                            @isset($date['f']) <i class="mdi mdi-account-network-outline text-danger"></i>
                                                        @endif {{ strftime('%d %B %Y', strtotime($date['d'])) }} @isset($date['t_s']) pukul {{ $date['t_s'] }} @endisset @isset($date['t_e']) s.d. {{ $date['t_e'] }} @endisset
                                                        </span>
                                            @endforeach
                                            @php($remain = collect($leave->dates)->count() - 3)
                                            @if ($remain > 0)
                                                <span class="badge text-dark fw-normal user-select-none">+{{ $remain }} lainnya</span>
                                            @endif
                                            </td>
                                            <td class="text-center">
                                                @if (isset($leave->attachment) && Storage::exists($leave->attachment))
                                                    <a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="{{ Storage::url($leave->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>
                                                @endif
                                            </td>
                                            <td nowrap>@include('portal::leave.components.status', ['leave' => $leave])</td>
                                            <td nowrap class="py-1 text-end">
                                                @unless ($leave->trashed())
                                                    @if ($leave->hasApprovables())
                                                        <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $leave->id }}">
                                                            <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>
                                                        </span>
                                                    @endif
                                                    <div class="dropstart d-inline">
                                                        <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                        <ul class="dropdown-menu border-0 shadow">
                                                            <li><a class="dropdown-item" href="{{ route('academic::leave.manage.show', ['leave' => $leave->id, 'next' => request('next')]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
                                                            @if (isset($leave->attachment) && Storage::exists($leave->attachment))
                                                                <li><a class="dropdown-item" href="{{ Storage::url($leave->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline me-1"></i> Lihat lampiran</a></li>
                                                            @endif
                                                            <li><a class="dropdown-item" href="{{ route('academic::leave.print', ['leave' => $leave->id]) }}" target="_blank"><i class="mdi mdi-printer-outline me-1"></i> Cetak dokumen (.pdf)</a></li>
                                                        </ul>
                                                    </div>
                                                @endunless
                                            </td>
                                            </tr>
                                            @if ($leave->hasApprovables() && !$leave->trashed())
                                                <tr>
                                                    <td class="p-0" colspan="7">
                                                        <div class="@if ($leave->hasAnyApprovableResultIn('PENDING')) show @endif collapse" id="collapse-{{ $leave->id }}">
                                                            <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                                <thead>
                                                                    <tr class="text-muted small bg-light">
                                                                        <th class="border-bottom fw-normal">Jenis</th>
                                                                        <th class="border-bottom fw-normal" colspan="2">Persetujuan</th>
                                                                        <th class="border-bottom fw-normal">Penanggungjawab</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($leave->approvables->sortBy('level') as $approvable)
                                                                        <tr>
                                                                            <td class="small {{ $approvable->cancelable ? 'text-danger' : 'text-muted' }}">{{ ucfirst($approvable->type) }} #{{ $approvable->level }}</td>
                                                                            <td @if ($loop->last) class="border-0" @endif>
                                                                                <div class="badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                                            </td>
                                                                            <td class="small ps-0">{{ $approvable->reason }}</td>
                                                                            <td class="small">{{ in_array(strtolower($approvable->userable->getApproverLabel()), ['guru', 'humas']) ? 'Wali Kelas' : $approvable->userable->getApproverLabel() }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @empty
                                                <tr>
                                                    <td colspan="5">@include('components.notfound')</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-body">
                                        {{ $leaves->appends(request()->all())->links() }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card border-0">
                                    <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                                        <div>
                                            <div class="display-4">{{ $pending_leaves_count }}</div>
                                            <div class="small fw-bold text-secondary text-uppercase">Jumlah pengajuan tertunda</div>
                                        </div>
                                        <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                                    </div>
                                    <div class="list-group list-group-flush border-top">
                                        <a class="list-group-item list-group-item-action text-danger" href="{{ route('academic::leave.manage.index', ['pending' => !request('pending')]) }}"><i class="mdi mdi-progress-clock"></i> {{ request('pending') == 1 ? 'Tampilkan semua pengajuan' : 'Hanya tampilkan pengajuan yang tertunda' }}</a>
                                    </div>
                                </div>
                                <a class="btn w-100 d-flex justify-content-between align-items-center bg-white py-3 text-start" style="cursor: pointer;" href="{{ route('academic::leave.submission.create', ['next' => url()->full()]) }}">
                                    <div>Pengajuan izin baru <br> <small class="text-muted">Silakan buat pengajuan izin kamu di sini</small></div>
                                    <i class="mdi mdi-chevron-right-circle-outline ms-3"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
