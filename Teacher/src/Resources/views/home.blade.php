@extends('teacher::layouts.default')

@section('title', 'Beranda - ')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="jumbotron mb-4 border bg-white p-3">
                <h2>Assalamu'alaikum {{ \Str::title(auth()->user()->profile->full_name) }}!</h2>
                <p class="text-muted">Selamat datang di {{ config('teacher.home.name') }}</p>
                <hr>
                Tahun Ajaran <strong>{{ $acsem->full_name }}</strong>
            </div>
            @if (!is_null($unpresenced_plans) && $unpresenced_plans->count())
                <h5 class="mb-3"><i class="mdi mdi-alert-outline"></i> Pertemuan yang belum diabsen</h5>
                <div class="list-group mb-4">
                    <div class="list-group-item list-group-item-warning text-danger">
                        <strong>Peringatan!</strong> <br>
                        Anda memiliki {{ $unpresenced_plans->count() }} pertemuan tempo hari yang belum diabsen, segera lakukan absen sesuai rencana pertemuan Anda!
                    </div>
                    @foreach ($unpresenced_plans as $plan)
                        <a class="list-group-item list-group-item-action text-decoration-none text-dark" href="{{ route('teacher::plan', ['plan' => $plan->id, 'next' => request()->url()]) }}">
                            <div class="row">
                                <div class="col-8 col-md-6">
                                    <strong>{{ $plan->meet->subject->name }}</strong> rombel <strong>{{ $plan->meet->classroom->name }}</strong> <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($plan->plan_at)->formatLocalized('%A, %d %B %Y') }}</small>
                                </div>
                                <div class="col-4 col-md-6 text-md-right d-flex align-items-center justify-content-end text-left">
                                    <span class="badge badge-pill badge-warning d-none d-sm-block mr-3">
                                        {{ \Carbon\Carbon::parse($plan->plan_at)->isToday() ? 'Hari ini' : \Carbon\Carbon::parse($plan->plan_at)->diffForHumans() }}
                                    </span>
                                    <i class="mdi mdi-chevron-right-circle-outline mdi-24px"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
            <h5 class="mb-3"><i class="mdi mdi-book-outline"></i> Jadwal {{ $user->profile->display_name }}</h5>
            @if ($teacher?->meets->where('plans_count', 0)->count())
                <p class="text-danger"><i class="mdi mdi-alert"></i> <strong>Peringatan!</strong> Anda belum menentukan rencana pertemuan.</p>
            @endif
            <div class="row">
                @forelse(optional($teacher)->meets ?? [] as $meet)
                    <div class="col-sm-6">
                        <div class="card mb-4">
                            <div class="card-header bg-{{ $meet->props->color ?? 'default' }} border-0">
                                <i class="mdi mdi-account-badge-horizontal-outline position-absolute" style="right: 10px; font-size: 40pt;"></i>
                                <div class="mb-3">
                                    <strong>{{ $meet->subject->name }}</strong> <br>
                                    {{ $meet->classroom->full_name }}
                                </div>
                                {{ $meet->plans_count ?? 0 }} pertemuan
                            </div>
                            <div class="list-group list-group-flush">
                                <a class="list-group-item list-group-item-action" href="{{ route('teacher::meet', ['meet' => $meet->id]) }}">Lihat selengkapnya &raquo;</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card card-body">
                            <i>Anda tidak memiliki pertemuan dengan kelas manapun di tahun ajaran saat ini.</i>
                        </div>
                    </div>
                @endforelse
            </div>
            <h5 class="mb-3"><i class="mdi mdi-calendar-arrow-left"></i> Pertemuan dekat-dekat ini</h5>
            <div class="list-group mb-4">
                @forelse(optional($closest_plans) ?? [] as $plan)
                    <a class="list-group-item list-group-item-action text-decoration-none text-dark" href="{{ route('teacher::plan', ['plan' => $plan->id, 'next' => request()->url()]) }}">
                        <div class="row">
                            <div class="col-8 col-md-6">
                                <strong>{{ $plan->meet->subject->name }}</strong> rombel <strong>{{ $plan->meet->classroom->name }}</strong> <br>
                                <small class="text-muted">{{ $plan->plan_at->formatLocalized('%A, %d %B %Y') }}</small>
                            </div>
                            <div class="col-4 col-md-6 text-md-right d-flex align-items-center justify-content-end text-left">
                                @if ($plan->plan_at->isToday())
                                    <span class="badge badge-pill badge-primary d-none d-sm-block mr-3">Hari ini</span>
                                @endif
                                <i class="mdi mdi-chevron-right-circle-outline mdi-24px"></i>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="list-group-item">
                        <i>Tidak ada pertemuan dalam seminggu kedepan.</i>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="col-md-4">
            @if (!is_null($teacher))
                @include('teacher::includes.teacher-info', ['teacher' => $teacher])
            @endif

            @include('account::includes.account-info')
        </div>
    </div>
@endsection
