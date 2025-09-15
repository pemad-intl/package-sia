@extends('counseling::layouts.default')

@section('title', 'Beranda - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="jumbotron mb-4 border bg-white p-2">
                <h2>Assalamu'alaikum {{ \Str::title(auth()->user()->profile->full_name) }}!</h2>
                <p class="text-muted">Selamat datang di {{ config('counseling.home.name') }}</p>
                <hr>
                Tahun Ajaran <strong>{{ $acsem->full_name }}</strong>
            </div>
            <h5 class="mb-3"><i class="mdi mdi-briefcase-outline"></i> Kasus akhir-akhir ini</h5>
            <div class="list-group mb-4">
                @forelse($last_cases as $case)
                    <div class="list-group-item">
                        <div class="d-flex align-items-center flex-row">
                            <div class="p-2">
                                <i class="mdi mdi-briefcase-account-outline mdi-36px text-muted"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong>{{ $case->semester->classroom->name . ' - ' . $case->semester->student->full_name }}</strong> <br>
                                {{ $case->category->name }} &mdash; {{ $case->description }} <br>
                                <small class="text-muted">Saksi: {{ $case->witness }} - {{ $case->break_at->diffForHumans() }}</small>
                            </div>
                            <div class="pl-3 text-center">
                                <h2 class="text-danger mb-0">{{ $case->point ?: '?' }}</h2>
                                <small class="text-muted">Poin</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item">
                        <i>Tidak ada data kasus.</i>
                    </div>
                @endforelse
                <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::cases.index') }}">Selengkapnya &raquo;</a>
            </div>
            <h5 class="mb-3"><i class="mdi mdi-file-cabinet"></i> Konseling akhir-akhir ini</h5>
            <div class="list-group mb-4">
                @forelse($last_counselings as $counseling)
                    <div class="list-group-item">
                        <div class="d-flex align-items-center flex-row">
                            <div class="p-2">
                                <i class="mdi mdi-file-cabinet mdi-36px text-muted"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong>{{ $counseling->semester->classroom->name . ' - ' . $counseling->semester->student->full_name }}</strong> <br>
                                {{ $counseling->description }} <br>
                                <small class="text-muted">Tindak lanjut: {{ $counseling->follow_up ?: 'Belum ada' }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item">
                        <i>Tidak ada data konseling.</i>
                    </div>
                @endforelse
                <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::counselings.index') }}">Selengkapnya &raquo;</a>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            @include('counseling::includes.employee-info', ['employee' => $employee])
            @include('account::includes.account-info')
        </div>
    </div>
@endsection
