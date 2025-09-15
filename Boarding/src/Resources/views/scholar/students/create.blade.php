@extends('administration::layouts.default')

@section('title', 'Data siswa - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::scholar.students.index')) }}">Siswa</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">
                <a class="text-decoration-none small" href="{{ request('next', route('administration::scholar.students.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
                Tambah siswa
            </h2>
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::scholar.students.store', ['next' => request('next', route('administration::scholar.students.index'))]) }}" method="POST"> @csrf
                        <div class="form-group required row">
                            <label for="acdmc_id" class="col-md-3 col-form-label">Tahun ajaran masuk</label>
                            <div class="col-md-5">
                                <select class="form-control @error('acdmc_id') is-invalid @enderror" name="acdmc_id" required id="acdmc_id">
                                    @foreach ($acdmcs as $acdmc)
                                        <option value="{{ $acdmc->id }}" @if (old('acdmc_id') == $acdmc->id) selected @endif>{{ $acdmc->name }}</option>
                                    @endforeach
                                </select>
                                @error('acdmc_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group required row">
                            <label for="name" class="col-md-3 col-form-label">Nama lengkap siswa</label>
                            <div class="col-md-7">
                                <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama siswa" value="{{ old('name') }}">
                                @error('name')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group required row">
                            <label for="nis" class="col-md-3 col-form-label">NIS</label>
                            <div class="col-md-4">
                                <input type="number" name="nis" required class="form-control @error('nis') is-invalid @enderror" id="nis" placeholder="NIS" value="{{ old('nis') }}">
                                @error('nis')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nisn" class="col-md-3 col-form-label">NISN</label>
                            <div class="col-md-4">
                                <input type="number" name="nisn" class="form-control @error('nisn') is-invalid @enderror" id="nisn" placeholder="NISN" value="{{ old('nisn') }}">
                                @error('nisn')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="nik" class="col-md-3 col-form-label">NIK</label>
                            <div class="col-md-6">
                                <input type="number" name="nik" class="form-control @error('nik') is-invalid @enderror" id="nik" placeholder="NIK" value="{{ old('nik') }}">
                                @error('nik')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pob" class="col-md-3 col-form-label">Tempat lahir</label>
                            <div class="col-md-5">
                                <input type="text" name="pob" class="form-control @error('pob') is-invalid @enderror" id="pob" placeholder="Tempat lahir" value="{{ old('pob') }}">
                                @error('pob')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dob" class="col-md-3 col-form-label">Tanggal lahir</label>
                            <div class="col-md-5">
                                <input type="text" name="dob" class="form-control @error('dob') is-invalid @enderror" id="dob" placeholder="Tanggal lahir" value="{{ old('dob') }}" data-mask="00-00-0000">
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
                                            <input type="radio" name="sex" value="{{ $k }}" autocomplete="off" @if (old('sex', -1) == $k) checked @endif> {{ $v }}
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
                                        <option value="{{ $hobby->id }}" @if (old('hobby_id') == $hobby->id) selected @endif>{{ $hobby->name }}</option>
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
                                        <option value="{{ $desire->id }}" @if (old('desire_id') == $desire->id) selected @endif>{{ $desire->name }}</option>
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
                                <input type="text" name="entered_at" class="form-control @error('entered_at') is-invalid @enderror" id="entered_at" placeholder="Tanggal masuk" value="{{ old('entered_at') }}" data-mask="00-00-0000">
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
                </div>
            </div>
        </div>
    </div>
@endsection
