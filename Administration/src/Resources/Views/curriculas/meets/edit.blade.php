@extends('administration::layouts.default')

@section('title', 'Pertemuan - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::curriculas.meets.index')) }}">Pertemuan</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::curriculas.meets.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Tambah pertemuan
    </h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::curriculas.meets.update', ['meet' => $meet->id, 'next' => request('next', route('administration::curriculas.meets.index', ['academic' => request('academic', $acsem->id)]))]) }}" method="POST"> @csrf @method('PUT')
                        <div class="form-group required row">
                            <label for="semester_id" class="col-md-3 col-form-label">Tahun ajaran</label>
                            <div class="col-md-5">
                                <strong><span class="form-control-plaintext">{{ $acsem->full_name }}</span></strong>
                            </div>
                        </div>
                        <div class="form-group required row mb-3">
                            <label for="semester_id" class="col-md-3 col-form-label">Rombel</label>
                            <div class="col-md-7">
                                <select class="form-control @error('classroom_id') is-invalid @enderror" name="classroom_id" id="classroom_id" required>
                                    <option value="">-- Pilih rombel --</option>
                                    @foreach ($acsem->classrooms as $classroom)
                                        <option value="{{ $classroom->id }}" @if (old('classroom_id', $meet->classroom_id) == $classroom->id) selected @endif>{{ $classroom->full_name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group required row mb-3">
                            <label for="semester_id" class="col-md-3 col-form-label">Mapel</label>
                            <div class="col-md-5">
                                <select class="form-control @error('subject_id') is-invalid @enderror" name="subject_id" id="subject_id" required>
                                    <option value="">-- Pilih mapel --</option>
                                    @foreach ($acsem->subjects as $subject)
                                        <option value="{{ $subject->id }}" @if (old('subject_id', $meet->subject_id) == $subject->id) selected @endif>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group required row mb-3">
                            <label for="semester_id" class="col-md-3 col-form-label">Pengajar</label>
                            <div class="col-md-5">
                                <select class="form-control @error('teacher_id') is-invalid @enderror" name="teacher_id" id="teacher_id" required>
                                    <option value="">-- Pilih pengajar --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @if (old('teacher_id', $meet->teacher_id) == $teacher->id) selected @endif>{{ $teacher->user->name }}</option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row mb-3">
                            <label for="semester_id" class="col-md-3 col-form-label">Warna</label>
                            <div class="col-md-5">
                                <div class="btn-group-toggle" data-toggle="buttons">
                                    <input type="color" name="props[color]" value="{{ $meet->props->color ?? '#ffffff' }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-3">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                                <a class="btn btn-secondary" href="{{ request('next', route('administration::curriculas.meets.index', ['academic' => $acsem->id])) }}">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body">
                <div class="form-group mb-0">
                    <label>Tahun ajaran</label>
                    <div class="input-group w-100">
                        <select name="academic" class="form-control">
                            <option>{{ $acsem->full_name }}</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-primary disabled" disabled>Tetapkan</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-account-group-outline"></i> Kelola rombel</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::curriculas.subjects.index', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-book-outline"></i> Kelola mapel</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::employees.teachers.index') }}"><i class="mdi mdi-account-circle-outline"></i> Data guru</a>
                </div>
            </div>
        </div>
    </div>
@endsection
