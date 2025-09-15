@extends('administration::layouts.default')

@section('title', 'Kelola guru - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kepegawaian</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::employees.teachers.index')) }}">Guru dan Karyawan</a></li>
    <li class="breadcrumb-item active">Lihat guru</li>
@endsection

@php
    $user = $teacher->employee;
    $page = request('page');

    if (!in_array($page, ['appreciations'])) {
        $page = null;
    }
@endphp

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::employees.teachers.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Lihat detail guru
    </h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="py-4">
                        <img class="rounded-circle" src="{{ asset('img/default-avatar.svg') }}" alt="" width="128">
                    </div>
                    <h5 class="mb-1"><strong>{{ $user->user->profile->full_name }}</strong></h5>
                    <p>
                        NIP. {{ $teacher->nip ?: '-' }}
                        @if ($teacher->employee->nuptk)
                            <br> NUPTK. {{ $teacher->employee->nuptk }}
                        @endif
                    </p>
                    <h4 class="mb-0">
                        @if (!empty($user->phone->whatsapp) && $user->phone->whatsapp)
                            <a class="text-primary px-1" href="https://wa.me/{{ $user->phone->number }}" target="_blank"><i class="mdi mdi-whatsapp"></i></a>
                        @endif
                        @if (!empty($user->email->verified_at) && $user->email->verified_at)
                            <a class="text-danger px-1" href="mailto:{{ $user->email->address }}"><i class="mdi mdi-email-outline"></i></a>
                        @endif
                    </h4>
                </div>
                <div class="list-group list-group-flush border-top">
                    @foreach ([
            'Masuk pada' => optional($teacher->entered_at)->diffForHumans() ?: '-',
            'Bergabung pada' => $user->created_at->diffForHumans(),
        ] as $k => $v)
                        <div class="list-group-item border-0">
                            {{ $k }} <br>
                            <span class="{{ $v ? 'font-weight-bold' : 'text-muted' }}">
                                {{ $v ?? 'Belum diisi' }}
                            </span>
                        </div>
                    @endforeach
                    <div class="list-group-item text-muted border-0">
                        <i class="mdi mdi-account-circle"></i> User ID : {{ $user->id }}
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-0"><i class="mdi mdi-account-circle-outline"></i> Profil</h4>
                </div>
                <div class="list-group list-group-flush border-top">
                    @foreach ([
            'Nama lengkap' => $user->user->profile->full_name,
            'Tempat lahir' => $user->user->profile->pob,
            'Tanggal lahir' => $user->user->profile->dob_name,
            'Jenis kelamin' => $user->user->profile->sex_name,
        ] as $k => $v)
                        <div class="list-group-item border-0">
                            {{ $k }} <br>
                            <span class="{{ $v ? 'font-weight-bold' : 'text-muted' }}">
                                {{ $v ?? 'Belum diisi' }}
                            </span>
                        </div>
                    @endforeach
                    <a class="list-group-item list-group-item-action border-top text-primary" href="{{ route('administration::database.manage.users.show', ['user' => $user->id]) }}">
                        Lihat selengkapnya &raquo;
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0 text-center">
                    <ul class="nav nav-pills pb-3" style="overflow-x: auto; display: -webkit-box; flex-wrap: nowrap;">
                        <li class="nav-item"> <a class="nav-link @if ($page == null) active bg-primary @endif" href="{{ url()->current() }}">Detail guru</a> </li>
                        <li class="nav-item"> <a class="nav-link @if ($page == 'appreciations') active bg-primary @endif" href="?page=appreciations">Penghargaan</a> </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if ($page == null)
                        <form class="form-block" action="{{ route('administration::employees.teachers.update', ['teacher' => $teacher->id]) }}" method="POST"> @csrf @method('PUT')
                            <div class="form-group required row">
                                <label for="nip" class="col-md-3 col-form-label">NIP</label>
                                <div class="col-md-4">
                                    <input type="number" name="nip" required class="form-control @error('nip') is-invalid @enderror" id="nip" placeholder="NIP" value="{{ old('nip', $teacher->employee->nip) }}">
                                    @error('nip')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nuptk" class="col-md-3 col-form-label">NUPTK</label>
                                <div class="col-md-4">
                                    <input type="number" name="nuptk" class="form-control @error('nuptk') is-invalid @enderror" id="nuptk" placeholder="NUPTK" value="{{ old('nuptk', $teacher->nuptk) }}">
                                    @error('nuptk')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="form-group required row">
                                <label for="name" class="col-md-3 col-form-label">Nama lengkap guru</label>
                                <div class="col-md-7">
                                    <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama guru" value="{{ old('name', $user->user->profile->name) }}">
                                    @error('name')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nik" class="col-md-3 col-form-label">NIK</label>
                                <div class="col-md-6">
                                    <input type="number" name="nik" class="form-control @error('nik') is-invalid @enderror" id="nik" placeholder="NIK" value="{{ old('nik', $user->user->profile->nik) }}">
                                    @error('nik')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pob" class="col-md-3 col-form-label">Tempat lahir</label>
                                <div class="col-md-5">
                                    <input type="text" name="pob" class="form-control @error('pob') is-invalid @enderror" id="pob" placeholder="Tempat lahir" value="{{ old('pob', $user->user->profile->pob) }}">
                                    @error('pob')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dob" class="col-md-3 col-form-label">Tanggal lahir</label>
                                <div class="col-md-5">
                                    <input type="text" name="dob" class="form-control @error('dob') is-invalid @enderror" id="dob" placeholder="Tanggal lahir" value="{{ old('dob', date('d-m-Y', strtotime($user->user->profile->dob))) }}" data-mask="00-00-0000">
                                    <small class="form-text text-muted">Format hh-bb-tttt (ex: 23-02-2001)</small>
                                    @error('dob')
                                        <small class="text-danger"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sex" class="col-md-3 col-form-label">Jenis kelamin</label>
                                <div class="col-md-5">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        @foreach (\Modules\Account\Models\UserProfile::$sex as $k => $v)
                                            <label class="btn btn-outline-secondary active">
                                                <input type="radio" name="sex" value="{{ $k }}" autocomplete="off" @if (old('sex', $user->user->profile->sex, -1) == $k) checked @endif> {{ $v }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('sex')
                                        <small class="text-danger"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="entered_at" class="col-md-3 col-form-label">Tanggal masuk</label>
                                <div class="col-md-5">
                                    <input type="text" name="entered_at" class="form-control @error('entered_at') is-invalid @enderror" id="entered_at" placeholder="Tanggal masuk" value="{{ old('entered_at', date('d-m-Y', strtotime($user->entered_at))) }}" data-mask="00-00-0000">
                                    <small class="form-text text-muted">Format hh-bb-tttt (ex: 23-02-2001)</small>
                                    @error('entered_at')
                                        <small class="text-danger"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-3">
                                    <button class="btn btn-primary" type="submit">Simpan</button>
                                    <a class="btn btn-secondary" href="{{ request('next', route('administration::employees.teachers.index')) }}">Kembali</a>
                                </div>
                            </div>
                        </form>
                    @endif
                    @if ($page == 'appreciations')
                        <span class="text-muted">Fitur belum tersedia</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
