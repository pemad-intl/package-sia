@extends('academic::layouts.default')

@section('title', 'Izin | ')

@include('components.tourguide', [
    'steps' => array_filter([
        [
            'selector' => '.tg-steps-leave-submission',
            'title' => 'Pengajuan izin',
            'content' => 'Tekan tombol ini untuk melakukan pengajuan izin.',
        ],
        [
            'selector' => '.tg-steps-leave-count',
            'title' => 'Statistik izin',
            'content' => 'Kolom ini menampilkan statistik izin yang telah kamu gunakan di tahun ini.',
        ],
        [
            'selector' => '.tg-steps-leave-filter',
            'title' => 'Filter riwayat izin',
            'content' => 'Gunakan filter ini untuk melihat riwayat izin pada bulan-bulan sebelumnya.',
        ],
        [
            'selector' => '.tg-steps-leave-table',
            'title' => 'Tabel riwayat izin',
            'content' => 'Menampilkan riwayat izin berdasarkan filter yang diterapkan.',
        ],
    ]),
])

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::dashboard.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Izin</h2>
            <div class="text-muted">Nggak perlu khawatir kalo ada keperluan mendadak!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card tg-steps-leave-submission border-0">
                <div class="card-body py-4 text-center">
                    <div class="my-4">
                        <a class="btn btn-soft-danger rounded-circle d-flex justify-content-center align-items-center mx-auto" href="{{ route('portal::leave.submission.create', ['next' => url()->full()]) }}" style="width: 100px; height: 100px;"><i class="mdi mdi-exit-to-app mdi-48px"></i></a>
                    </div>
                    <h4 class="mb-1">Pengajuan baru</h4>
                    <p class="text-muted mb-0">Silakan tekan tombol di atas untuk mengajukan izin baru</p>
                </div>
            </div>
            <div class="card tg-steps-leave-count border-0">
                <div class="card-body border-top py-4">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="small text-uppercase">Jumlah izin yang diambil</div>
                            <div class="small text-muted">Tahun {{ date('Y') }}</div>
                        </div>
                        <div class="col-4">
                            <div class="h1 mb-0 text-end">{{ $leaves_this_year_count }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($employee->position->position_id == 2 || $employee->position->position_id == 1)
                @if (in_array($employee->position?->position->level->value ?: 0, array_column(config('modules.core.features.services.leaves.approvable_steps', []), 'value')))
                    <div class="list-group mb-4">
                        <a class="list-group-item list-group-item-action p-4" href="{{ route('portal::leave.manage.index', ['next' => url()->current()]) }}" style="border-style: dashed;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-inline-block bg-soft-secondary text-danger me-2 rounded text-center" style="height: 36px; width: 36px;">
                                    <i class="mdi mdi-calendar-check-outline mdi-24px"></i>
                                </div>
                                <div class="flex-grow-1">Kelola pengajuan izin</div>
                                <i class="mdi mdi-chevron-right-circle-outline"></i>
                            </div>
                        </a>
                    </div>
                @endif
            @endif
        </div>
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-calendar-multiselect"></i> Riwayat izin
                    </div>
                    <input type="checkbox" class="btn-check" id="collapse-btn" autocomplete="off" @if (request('search')) checked @endif>
                    <label class="btn btn-outline-secondary text-dark btn-sm rounded px-2 py-1" data-bs-toggle="collapse" data-bs-target="#collapse-filter" for="collapse-btn"><i class="mdi mdi-filter-outline"></i> <span class="d-none d-sm-inline">Filter</span></label>
                </div>
                <div class="card-body border-top border-bottom tg-steps-leave-filter">
                    <form class="form-block row gy-2 gx-2" action="{{ route('portal::leave.submission.index') }}" method="get">
                        <div class="col-12 flex-grow-1 my-0">
                            <div class="@if (request('search')) show @endif collapse" id="collapse-filter">
                                <input class="form-control" type="search" name="search" placeholder="Cari kategori/deskripsi di sini ..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="flex-grow-1 col-auto">
                            <div class="input-group">
                                <div class="input-group-text"><span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span><span class="d-none d-sm-inline">Periode</span></div>
                                <button type="button" class="btn btn-light dropdown-toggle d-none d-sm-block" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">Rentang waktu</button>
                                <input class="form-control" type="date" name="start_at" value="{{ request('start_at') }}">
                                <input class="form-control" type="date" name="end_at" value="{{ request('end_at') }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('portal::leave.submission.index') }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive table-responsive-xl tg-steps-leave-table">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
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
                                    <td style="min-width: 200px;" class="py-3">
                                        <div>{{ $leave->category->name }}</div>
                                        <small class="text-muted">{{ $leave->description }}</small>
                                    </td>
                                    <td class="small">{{ $leave->created_at->formatLocalized('%d %B %Y') }}</td>
                                    <td style="min-width: 200px;">
                                        @foreach (collect($leave->dates)->take(3) as $date)
                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none {{ isset($date['c']) ? 'text-decoration-line-through' : '' }}" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset>
                                                @isset($date['f'])
                                                    <i class="mdi mdi-account-network-outline text-danger"></i>
                                                @endisset
                                                {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                                @isset($date['t_s'])
                                                    pukul {{ $date['t_s'] }}
                                                @endisset
                                                @isset($date['t_e'])
                                                    s.d. {{ $date['t_e'] }}
                                                @endisset
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
                                                    <li><a class="dropdown-item" href="{{ route('portal::leave.submission.show', ['leave' => $leave->id, 'next' => request('next')]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
                                                    @if (isset($leave->attachment) && Storage::exists($leave->attachment))
                                                        <li><a class="dropdown-item" href="{{ Storage::url($leave->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline me-1"></i> Lihat lampiran</a></li>
                                                    @endif
                                                    <li><a class="dropdown-item" href="{{ route('portal::leave.print', ['leave' => $leave->id]) }}" target="_blank"><i class="mdi mdi-printer-outline me-1"></i> Cetak dokumen (.pdf)</a></li>
                                                </ul>
                                            </div>
                                        @endunless
                                    </td>
                                </tr>
                                @if ($leave->hasApprovables() && !$leave->trashed())
                                    <tr>
                                        <td class="p-0" colspan="6">
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
                                                        @foreach ($leave->approvables as $approvable)
                                                            <tr>
                                                                <td class="small {{ $approvable->cancelable ? 'text-danger' : 'text-muted' }}">{{ ucfirst($approvable->type) }} #{{ $approvable->level }}</td>
                                                                <td @if ($loop->last) class="border-0" @endif>
                                                                    <div class="badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                                </td>
                                                                <td class="small ps-0">{{ $approvable->reason }}</td>
                                                                <td class="small">{{ $approvable->userable->getApproverLabel() }}</td>
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
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
@endpush
