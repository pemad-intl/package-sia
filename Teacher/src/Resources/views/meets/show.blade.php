@extends('teacher::layouts.default')

@section('title', $meet->subject->name . ' ' . $meet->classroom->full_name . ' - ')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card bg-{{ $meet->props->color ?? 'white border' }} mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-badge-horizontal-outline position-absolute" style="top: 10pt; right: 40pt; font-size: 40pt;"></i>
                    <h2><strong>{{ $meet->subject->name }}</strong></h3>
                        <p class="mb-0">Rombel {{ $meet->classroom->full_name }}</p>
                </div>
                @if ($plans->where('plan_at', null)->count())
                    <div class="card-footer text-danger">
                        <strong>Peringatan!</strong> <br> Sepertinya beberapa pertemuan belum ditentukan rencananya, silahkan isi terlebih dahulu!
                    </div>
                @endif
            </div>
            <h5 class="mb-1"><i class="mdi mdi-book-outline"></i> Kelola rencana pertemuan</h5>
            @if ($meet->plans_count)
                <div class="mb-3">
                    <p class="text-muted mb-0">Menampilkan {{ $plans->count() }} dari total {{ $meet->plans_count }} pertemuan</p>
                    <small><a class="text-{{ $meet->props->color ?? 'primary' }}" href="{{ route('teacher::meet', ['meet' => $meet->id, 'all' => request('all') ? 0 : 1]) }}">Tampilkan {{ request('all') ? 'sedikit saja' : 'semuanya' }}</a></small>
                </div>
                <div class="card-columns mb-4">
                    @foreach ($plans as $plan)
                        <div class="card @if ($plan->plan_at && \Carbon\Carbon::parse($plan->plan_at)->isToday()) border-{{ $meet->props->color ?? '' }} @endif text-center">
                            @if ($plan->plan_at && \Carbon\Carbon::parse($plan->plan_at)->isToday())
                                <div class="card-header bg-{{ $meet->props->color ?? '' }}">
                                    <strong>Hari ini</strong>
                                </div>
                            @endif
                            @if ($plan->test)
                                <div class="card-header bg-light">
                                    <strong>Ujian/ulangan</strong>
                                </div>
                            @endif
                            <div class="card-body">
                                <strong>Pertemuan ke-{{ $plan->az }}</strong> <br>
                                <div>
                                    @if ($plan->plan_at)
                                        {{ \Carbon\Carbon::parse($plan->plan_at)->formatLocalized('%A, %d %B %Y') }} <br>
                                        @if (!\Carbon\Carbon::parse($plan->plan_at)->isToday())
                                            <span class="badge badge-pill badge-{{ $plan->plan_at && \Carbon\Carbon::parse($plan->plan_at)->isPast() ? 'secondary' : $meet->props->color ?? 'primary' }}">
                                                {{ $plan->plan_at ? \Carbon\Carbon::parse($plan->plan_at)->diffForHumans() : '' }}
                                            </span>
                                        @endif
                                    @else
                                        <small class="text-danger">Rencana belum ditentukan</small>
                                    @endif
                                </div>
                                <div>
                                    @if (!$plan->test)
                                        <div class="mt-2">
                                            <small class="text-muted">{{ $plan->competence->full_name ?? 'Tidak ada kompetensi yang ditentukan' }}</small> <br>
                                        </div>
                                    @endif
                                    @if ($plan->plan_at)
                                        @if (empty($plan->presence) && \Carbon\Carbon::parse($plan->plan_at)->isPast() && !\Carbon\Carbon::parse($plan->plan_at)->isToday())
                                            <div>
                                                <span class="badge badge-pill badge-danger" data-toggle="tooltip">
                                                    <i class="mdi mdi-account-badge-horizontal-outline"></i> Belum diabsen
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="list-group list-group-flush border-top">
                                <a class="list-group-item list-group-item-action" href="{{ route('teacher::plan', ['plan' => $plan->id]) }}">Selengkapnya &raquo;</a>
                            </div>
                        </div>
                    @endforeach
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('teacher::meet.plans', ['meet' => $meet->id]) }}" class="list-group-item list-group-item-action text-{{ $meet->props->color ?? 'primary' }} border-{{ $meet->props->color ?? 'primary' }} py-5 text-center" style="border-style: dashed;">
                                <div><i class="mdi mdi-file-document-edit-outline display-3"></i></div>
                                <strong>Atur rencana pertemuan</strong>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card card-body mb-4 mt-3">
                    Tidak ada pertemuan yang direncanakan, silahkan tentukan pertemuan Anda
                    <hr>
                    <form class="form-block form-confirm" action="{{ route('teacher::meet', ['meet' => $meet->id]) }}" method="POST"> @csrf
                        <div class="form-group required row mb-3">
                            <label class="col-md-4 col-form-label">Jumlah pertemuan</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input class="form-control @error('meets') is-invalid @enderror" type="number" name="meets" placeholder="0" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">pertemuan</span>
                                    </div>
                                </div>
                                @error('meets')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group required row mb-3">
                            <label class="col-md-4 col-form-label">Jumlah jam pelajaran</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input class="form-control @error('count') is-invalid @enderror" type="number" name="count" placeholder="0" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">jam/pertemuan</span>
                                    </div>
                                </div>
                                @error('count')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a class="btn btn-secondary" href="{{ route('teacher::home') }}">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card card-body mb-4 mt-3">
                    <p>Atau salin rencana pertemuan dari rombel lain?</p>
                    @error('meet_id')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <form class="form-block form-confirm form-row" action="{{ route('teacher::meet.copy', ['meet' => $meet->id]) }}" method="POST"> @csrf
                        <div class="col-sm mb-sm-0 mb-3">
                            <select class="form-control @error('meet_id') is-invalid @enderror" name="meet_id" required>
                                <option value="">-- Pilih rombel --</option>
                                @foreach ($filledMeets as $_meet)
                                    <option value="{{ $_meet->id }}">{{ $_meet->classroom->full_name }} ({{ $_meet->plans_count }} pertemuan)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="col-md-4">
            @include('teacher::includes.classroom-info', ['classroom' => $meet->classroom])
            @include('teacher::includes.subject-info', ['subject' => $meet->subject])
        </div>
    </div>
@endsection

@push('style')
    <style scoped>
        @media (min-width: 576px) {
            .card-columns {
                -webkit-column-count: 2;
                -moz-column-count: 2;
                column-count: 2;
            }
        }

        @media (min-width: 992px) {
            .card-columns {
                -webkit-column-count: 3;
                -moz-column-count: 3;
                column-count: 3;
            }
        }
    </style>
@endpush
