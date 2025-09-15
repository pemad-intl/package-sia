@extends('administration::layouts.default')

@section('title', 'Data siswa - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::scholar.students.index')) }}">Data siswa</a></li>
    <li class="breadcrumb-item active">Lihat siswa</li>
@endsection

@php
    $user = $student->user;
    $page = request('page');

    if (!in_array($page, ['assessments', 'cases', 'achievements', 'bills'])) {
        $page = null;
    }
@endphp

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::scholar.students.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Lihat detail siswa
    </h2>
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div id="flash-success" class="alert alert-success mt-4">
                    {!! session('success') !!}
                </div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="py-4">
                        <img class="rounded-circle" src="{{ asset('img/default-avatar.svg') }}" alt="" width="128">
                    </div>
                    <h5 class="mb-1"><strong>{{ $user->profile->full_name }}</strong></h5>
                    <p>
                        NIS. {{ $student->nis ?: '-' }}
                        @if ($student->nisn)
                            <br> NISN. {{ $student->nisn }}
                        @endif
                    </p>
                    <h4 class="mb-0">
                        @if (!empty($user->phone->whatsapp))
                            <a class="text-primary px-1" href="https://wa.me/{{ $user->phone->number }}" target="_blank"><i class="mdi mdi-whatsapp"></i></a>
                        @endif
                        @if (!empty($user->email->verified_at))
                            <a class="text-danger px-1" href="mailto:{{ $user->email->address }}"><i class="mdi mdi-email-outline"></i></a>
                        @endif
                    </h4>
                </div>
                <div class="list-group list-group-flush border-top">
                    @foreach ([
            'Angkatan ke' => optional($student->generation)->name ?: '-',
            'Masuk pada' => optional($student->entered_at)->diffForHumans() ?: '-',
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
            'Nama lengkap' => $user->profile->full_name,
            'Tempat lahir' => $user->profile->pob,
            'Tanggal lahir' => $user->profile->dob,
            'Jenis kelamin' => $user->profile->sex_name,
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
                        <li class="nav-item"> <a class="nav-link @if ($page == null) active bg-primary @endif" href="{{ url()->current() }}">Detail siswa</a> </li>
                        <li class="nav-item"> <a class="nav-link @if ($page == 'assessments') active bg-primary @endif" href="?page=assessments">Nilai</a> </li>
                        <li class="nav-item"> <a class="nav-link @if ($page == 'cases') active bg-primary @endif" href="?page=cases">Kasus</a> </li>
                        <li class="nav-item"> <a class="nav-link @if ($page == 'achievements') active bg-primary @endif" href="?page=achievements">Prestasi</a> </li>
                        <li class="nav-item"> <a class="nav-link @if ($page == 'bills') active bg-primary @endif" href="?page=bills">Tagihan</a> </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if ($page == null)
                        <form class="form-block" action="{{ route('administration::scholar.students.update', ['student' => $student->id]) }}" method="POST"> @csrf @method('PUT')
                            <div class="form-group required row">
                                <label for="name" class="col-md-3 col-form-label">Nama lengkap siswa</label>
                                <div class="col-md-7">
                                    <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama siswa" value="{{ old('name', $user->profile->name) }}">
                                    @error('name')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group required row">
                                <label for="nis" class="col-md-3 col-form-label">NIS</label>
                                <div class="col-md-4">
                                    <input type="number" name="nis" required class="form-control @error('nis') is-invalid @enderror" id="nis" placeholder="NIS" value="{{ old('nis', $student->nis) }}">
                                    @error('nis')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nisn" class="col-md-3 col-form-label">NISN</label>
                                <div class="col-md-4">
                                    <input type="number" name="nisn" class="form-control @error('nisn') is-invalid @enderror" id="nisn" placeholder="NISN" value="{{ old('nisn', $student->nisn) }}">
                                    @error('nisn')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="nik" class="col-md-3 col-form-label">NIK</label>
                                <div class="col-md-6">
                                    <input type="number" name="nik" class="form-control @error('nik') is-invalid @enderror" id="nik" placeholder="NIK" value="{{ old('nik', $user->profile->nik) }}">
                                    @error('nik')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pob" class="col-md-3 col-form-label">Tempat lahir</label>
                                <div class="col-md-5">
                                    <input type="text" name="pob" class="form-control @error('pob') is-invalid @enderror" id="pob" placeholder="Tempat lahir" value="{{ old('pob', $user->profile->pob) }}">
                                    @error('pob')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dob" class="col-md-3 col-form-label">Tanggal lahir</label>
                                <div class="col-md-5">
                                    <input type="text" name="dob" class="form-control @error('dob') is-invalid @enderror" id="dob" placeholder="Tanggal lahir" value="{{ date('d-m-Y', strtotime($user->profile?->dob)) }}" data-mask="00-00-0000">
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
                                                <input type="radio" name="sex" value="{{ $k }}" autocomplete="off" @if (old('sex', $user->profile->sex, -1) == $k) checked @endif> {{ $v }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('sex')
                                        <small class="text-danger"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="hobby_id" class="col-md-3 col-form-label">Hobi</label>
                                <div class="col-md-5">
                                    <select class="form-control @error('hobby_id') is-invalid @enderror" name="hobby_id" id="hobby_id">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($hobbies as $hobby)
                                            <option value="{{ $hobby->id }}" @if (old('hobby_id', $user->profile->hobby_id) == $hobby->id) selected @endif>{{ $hobby->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('hobby_id')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="desire_id" class="col-md-3 col-form-label">Cita-cita</label>
                                <div class="col-md-5">
                                    <select class="form-control @error('desire_id') is-invalid @enderror" name="desire_id" id="desire_id">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($desires as $desire)
                                            <option value="{{ $desire->id }}" @if (old('desire_id', $user->profile->desire_id) == $desire->id) selected @endif>{{ $desire->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('desire_id')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="entered_at" class="col-md-3 col-form-label">Tanggal masuk</label>
                                <div class="col-md-5">
                                    <input type="text" name="entered_at" class="form-control @error('entered_at') is-invalid @enderror" id="entered_at" placeholder="Tanggal masuk" value="{{ date('d-m-Y', strtotime($student->entered_at)) }}" data-mask="00-00-0000">
                                    <small class="form-text text-muted">Format hh-bb-tttt (ex: 23-02-2001)</small>
                                    @error('entered_at')
                                        <small class="text-danger"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-3">
                                    <button class="btn btn-primary" type="submit">Simpan</button>
                                    <a class="btn btn-secondary" href="{{ request('next', route('administration::scholar.students.index')) }}">Kembali</a>
                                </div>
                            </div>
                        </form>
                    @endif
                    @if ($page == 'assessments')
                        <form action="{{ route('administration::scholar.students.show', ['student' => $student->id]) }}" method="GET">
                            <input type="hidden" name="page" value="assessments">
                            <div class="form-group">
                                <label>Tahun Ajaran</label>
                                <div class="input-group">
                                    <select class="form-control" name="smt" required>
                                        <option value="">-- Pilih tahun ajaran --</option>
                                        @foreach ($student->semesters as $semester)
                                            <option value="{{ $semester->id }}" @if (request('smt') == $semester->id) selected @endif>{{ $semester->semester->full_name . ' - Rombel ' . $semester->classroom->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @if (request('smt'))
                            @php($stsem = $student->semesters()->with('classroom.meets.subject', 'assessments')->find(request('smt')))
                            <div class="list-group">
                                @forelse($stsem->classroom->meets as $meet)
                                    <a class="list-group-item list-group-item-action bg-light" data-toggle="collapse" data-target="#accordion-assessment-{{ $meet->id }}">
                                        <div class="font-weight-bold mr-4"><i class="mdi mdi-label text-{{ $meet->props->color ?? 'dark' }}"></i> {{ $meet->subject->name }}</div>
                                    </a>
                                    @php($assessments = $stsem->assessments->where('subject_id', $meet->subject_id))
                                    <div id="accordion-assessment-{{ $meet->id }}" class="list-group list-group-item list-group-flush collapse p-0">
                                        @forelse($assessments as $assessment)
                                            <div class="list-group-item d-flex justify-content-between flex-row">
                                                <div class="font-weight-bold mr-4">{{ $assessment->type_name }}</div>
                                                <div>{{ $assessment->value }}</div>
                                            </div>
                                        @empty
                                            <div class="list-group-item">Tidak ada data nilai</div>
                                        @endforelse
                                    </div>
                                @empty
                                    <div class="list-group-item">Tidak ada jadwal yang diterapkan</div>
                                @endforelse
                            </div>
                        @endif
                    @endif
                    @if ($page == 'cases')
                        <form action="{{ route('administration::scholar.students.show', ['student' => $student->id]) }}" method="GET">
                            <input type="hidden" name="page" value="cases">
                            <div class="form-group">
                                <label>Tahun Ajaran</label>
                                <div class="input-group">
                                    <select class="form-control" name="smt" required>
                                        <option value="">-- Pilih tahun ajaran --</option>
                                        @foreach ($student->semesters as $semester)
                                            <option value="{{ $semester->id }}" @if (request('smt') == $semester->id) selected @endif>{{ $semester->semester->full_name . ' - Rombel ' . $semester->classroom->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @if (request('smt'))
                            @php($cases = $student->semesters()->with('cases')->find(request('smt'))->cases)
                            <div class="list-group list-group-flush mb-0">
                                @forelse($cases as $case)
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-center flex-row">
                                            <div class="pr-3">
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
                                    <div>Tidak ada data kasus.</div>
                                @endforelse
                            </div>
                        @endif
                    @endif
                    @if ($page == 'achievements')
                        @if (request('action') == 'create')
                            <h5>
                                <strong>
                                    <a class="text-decoration-none small" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
                                    Tambah data prestasi
                                </strong>
                            </h5>
                            <hr>
                            <form class="form-block form-confirm" action="{{ route('administration::scholar.students.achievements.store', ['student' => $student->id]) }}" method="POST" enctype="multipart/form-data"> @csrf
                                @include('account::user.achievements.includes.form-create', ['user' => $user, 'back' => true])
                            </form>
                            </form>
                        @else
                            <div class="mb-4">
                                @forelse(optional($student->user)->achievements ?? [] as $achievement)
                                    <div class="d-flex justify-content-between mb-2 flex-row">
                                        <div class="flex-grow-1 mr-3">
                                            <span class="badge badge-dark">{{ $achievement->type->name }}</span><br>
                                            <strong>{{ $achievement->name }}</strong><br>
                                            Peringkat {{ $achievement->num->name }} di {{ $achievement->territory->name }}<br>
                                            <span class="text-muted">Tahun {{ $achievement->year }}</span>
                                        </div>
                                        <form class="form-block form-confirm" action="{{ route('administration::scholar.students.achievements.destroy', ['student' => $student->id, 'achievement' => $achievement->id]) }}" method="POST"> @csrf @method('DELETE')
                                            @if (Storage::exists($achievement->file))
                                                <a class="btn btn-primary btn-sm" href="{{ Storage::url($achievement->file) }}" target="_blank">Lihat berkas</a>
                                            @endif
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</a>
                                        </form>
                                    </div>
                                @empty
                                    <div>Tidak ada data prestasi, silahkan tekan tombol dibawah untuk menambahkan data prestasi.</div>
                                @endforelse
                            </div>
                            <a href="{{ route('administration::scholar.students.show', ['student' => $student->id, 'page' => 'achievements', 'action' => 'create', 'next' => url()->current()]) }}" class="btn btn-primary">Tambah prestasi</a>
                        @endif
                    @endif
                    @if ($page == 'bills')
                        <span class="text-muted">Fitur belum tersedia</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
