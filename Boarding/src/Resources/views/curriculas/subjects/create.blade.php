@extends('administration::layouts.default')

@section('title', 'Mapel - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::curriculas.subjects.index')) }}">Mapel</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::curriculas.subjects.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Tambah mapel
    </h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::curriculas.subjects.store', ['semester_id' => $acsem->id, 'next' => request('next', route('administration::curriculas.subjects.index', ['academic' => request('academic', $acsem->id)]))]) }}" method="POST"> @csrf
                        <div class="form-group required row">
                            <label for="semester_id" class="col-md-3 col-form-label">Tahun ajaran</label>
                            <div class="col-md-5">
                                <strong><span class="form-control-plaintext">{{ $acsem->full_name }}</span></strong>
                            </div>
                        </div>
                        <div class="form-group required row">
                            <label for="semester_id" class="col-md-3 col-form-label">Kode mapel</label>
                            <div class="col-md-5">
                                <input type="text" name="kd" class="form-control @error('kd') is-invalid @enderror" id="kd" placeholder="Kode mapel" value="{{ old('kd') }}">
                                @error('kd')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group required row">
                            <label for="name" class="col-md-3 col-form-label">Nama mapel</label>
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama mapel" value="{{ old('name') }}">
                                @error('name')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group required row">
                            <label for="semester_id" class="col-md-3 col-form-label">Kelas</label>
                            <div class="col-md-7">
                                <select class="form-control @error('level_id') is-invalid @enderror" name="level_id" id="level_id" required>
                                    <option value="">-- Pilih kelas --</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}" @if (old('level_id') == $level->id) selected @endif>{{ $level->kd }} - {{ $level->name }}</option>
                                    @endforeach
                                </select>
                                @error('level_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="semester_id" class="col-md-3 col-form-label">Kategori mapel</label>
                            <div class="col-md-7">
                                <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
                                    <option value="">-- Pilih kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @if (old('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-3">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                                <a class="btn btn-secondary" href="{{ request('next', route('administration::curriculas.subjects.index', ['academic' => $acsem->id])) }}">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body">
                <form class="form-block" action="{{ route('administration::curriculas.subjects.create') }}" method="GET">
                    <div class="form-group mb-0">
                        <label>Tahun ajaran</label>
                        <div class="input-group w-100">
                            <select name="academic" class="form-control">
                                @foreach ($acsems as $_acsem)
                                    <option value="{{ $_acsem->id }}" @if (request('academic', $acsem->id) == $_acsem->id) selected @endif>{{ $_acsem->full_name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary">Tetapkan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
