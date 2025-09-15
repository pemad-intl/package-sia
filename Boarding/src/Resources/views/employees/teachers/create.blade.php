@extends('administration::layouts.default')

@section('title', 'Tambah guru - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kepegawaian</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::employees.teachers.index')) }}">Guru dan Karyawan</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">
                <a class="text-decoration-none small" href="{{ request('next', route('administration::employees.teachers.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
                Tambah guru
            </h2>
            <p>
                <a class="btn btn-primary" href="{{ route('administration::employees.teachers.create', ['user' => request('user') ? null : 1]) }}"> <i class="mdi mdi-account-plus-outline"></i> {{ request('user') == 1 ? 'Input guru baru' : 'Atau tambah guru dari pengguna yang sudah ada' }}</a>
            </p>
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::employees.teachers.store', ['next' => request('next', route('administration::employees.teachers.index')), 'user' => request('user')]) }}" method="POST"> @csrf
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
                            <label for="nip" class="col-md-3 col-form-label">NIP</label>
                            <div class="col-md-4">
                                <input type="number" name="nip" required class="form-control @error('nip') is-invalid @enderror" id="nip" placeholder="NIP" value="{{ old('nip') }}">
                                @error('nip')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nuptk" class="col-md-3 col-form-label">NUPTK</label>
                            <div class="col-md-4">
                                <input type="number" name="nuptk" class="form-control @error('nuptk') is-invalid @enderror" id="nuptk" placeholder="NUPTK" value="{{ old('nuptk') }}">
                                @error('nuptk')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        @if (request('user') == 1)
                            <div class="form-group required row">
                                <label for="user_id" class="col-md-3 col-form-label">Nama pengguna</label>
                                <div class="col-md-7">
                                    <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required data-placeholder="Cari nama disini ...">
                                    </select>
                                    @error('user_id')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="form-group required row">
                                <label for="name" class="col-md-3 col-form-label">Nama lengkap guru</label>
                                <div class="col-md-7">
                                    <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama guru" value="{{ old('name') }}">
                                    @error('name')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
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
                            <hr>
                        @endif
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
                                <a class="btn btn-secondary" href="{{ request('next', route('administration::employees.teachers.index')) }}">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('[name="user_id"]').select2({
                    minimumInputLength: 3,
                    theme: 'bootstrap5',
                    ajax: {
                        url: '{{ route('api.getUsers') }}',
                        dataType: 'json',
                        delay: 500,
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.min.css') }}">
@endpush
