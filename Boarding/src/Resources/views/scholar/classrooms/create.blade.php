@extends('administration::layouts.default')

@section('title', 'Rombel - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::scholar.classrooms.index')) }}">Rombel</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::scholar.classrooms.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Tambah rombel
    </h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::scholar.classrooms.store', ['semester_id' => $acsem->id, 'next' => request('next', route('administration::scholar.classrooms.index', ['academic' => request('academic', $acsem->id)]))]) }}" method="POST"> @csrf
                        <div class="form-group required row">
                            <label for="semester_id" class="col-md-3 col-form-label">Tahun ajaran</label>
                            <div class="col-md-5">
                                <strong><span class="form-control-plaintext">{{ $acsem->full_name }}</span></strong>
                            </div>
                        </div>
                        <div class="form-group required row">
                            <label for="semester_id" class="col-md-3 col-form-label">Jenjang kelas</label>
                            <div class="col-md-5">
                                <select class="form-control @error('level_id') is-invalid @enderror" name="level_id" id="level_id">
                                    @foreach (getGrade()->levels as $level)
                                        <option value="{{ $level->id }}" @if (old('level_id') == $level->id) selected @endif>{{ $level->kd . ' - ' . $level->name }}</option>
                                    @endforeach
                                </select>
                                @error('level_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group required row">
                            <label for="name" class="col-md-3 col-form-label">Nama rombel</label>
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama rombel" value="{{ old('name') }}">
                                @error('name')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="semester_id" class="col-md-3 col-form-label">Ruangan</label>
                            <div class="col-md-7">
                                <select class="form-control @error('room_id') is-invalid @enderror" name="room_id" id="room_id">
                                    <option value="">-- Pilih ruang --</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}" @if (old('room_id') == $room->id) selected @endif>{{ $room->kd . ' - ' . $room->name }}</option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="semester_id" class="col-md-3 col-form-label">Jurusan</label>
                            <div class="col-md-7">
                                <select class="form-control @error('major_id') is-invalid @enderror" name="major_id" id="major_id">
                                    <option value="">-- Pilih jurusan --</option>
                                    @foreach ($acsem->majors as $major)
                                        <option value="{{ $major->id }}" @if (old('major_id') == $major->id) selected @endif>{{ $major->name }}</option>
                                    @endforeach
                                </select>
                                @error('major_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="semester_id" class="col-md-3 col-form-label">Unggulan</label>
                            <div class="col-md-7">
                                <select class="form-control @error('superior_id') is-invalid @enderror" name="superior_id" id="superior_id">
                                    <option value="">-- Pilih unggulan --</option>
                                    @foreach ($acsem->superiors as $superior)
                                        <option value="{{ $superior->id }}" @if (old('superior_id') == $superior->id) selected @endif>{{ $superior->name }}</option>
                                    @endforeach
                                </select>
                                @error('superior_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="semester_id" class="col-md-3 col-form-label">Wali kelas</label>
                            <div class="col-md-6">
                                <select class="form-control @error('supervisor_id') is-invalid @enderror" name="supervisor_id" id="supervisor_id">
                                    <option value="">-- Pilih wali kelas --</option>
                                    @foreach ($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" @if (old('supervisor_id') == $supervisor->id) selected @endif>{{ $supervisor->full_name }}</option>
                                    @endforeach
                                </select>
                                @error('supervisor_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-3">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                                <a class="btn btn-secondary" href="{{ request('next', route('administration::scholar.classrooms.index', ['academic' => $acsem->id])) }}">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body">
                <form class="form-block" action="{{ route('administration::scholar.classrooms.create') }}" method="GET">
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
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.majors.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-folder-settings-variant-outline"></i> Kelola jurusan</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.superiors.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-file-settings-variant-outline"></i> Kelola unggulan</a>
                </div>
            </div>
        </div>
    </div>
@endsection
