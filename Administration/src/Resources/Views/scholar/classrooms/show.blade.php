@extends('administration::layouts.default')

@section('title', 'Rombel - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::scholar.classrooms.index')) }}">Rombel</a></li>
    <li class="breadcrumb-item active">Detail rombel {{ $classroom->name }}</li>
@endsection

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::scholar.classrooms.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Detail rombel
    </h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-details float-left mr-2"></i>Daftar siswa Tahun Ajaran <strong>{{ $classroom->semester->full_name }}</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('administration::scholar.classrooms.students', ['classroom' => $classroom->id]) }}" method="POST"> @csrf @method('PUT')
                        <div class="form-group mb-3">
                            <select class="form-control" multiple="multiple" size="10" name="stsems[]">
                                @foreach ($stsems as $stsem)
                                    <option value="{{ $stsem->id }}" @if ($classroom->stsems->contains('id', $stsem->id)) selected @endif>{{ $stsem->student->user->profile->full_name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('stsems.0'))
                                <span class="text-danger">Siswa yang Anda pilih tidak valid</span>
                            @endif
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a class="btn btn-secondary" href="{{ request('next', route('administration::scholar.classrooms.index')) }}"> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $classroom->stsems->count() }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah siswa rombel {{ $classroom->name }}</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.students.index') }}"><i class="mdi mdi-account-group-outline"></i> Data siswa</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.semesters.index') }}"><i class="mdi mdi-account-group-outline"></i> Registrasi semester</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.classrooms.index') }}"><i class="mdi mdi-folder-settings-variant-outline"></i> Kelola rombel</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-duallistbox.min.css') }}">
    <script src="{{ asset('js/bootstrap-duallistbox.min.js') }}"></script>

    <script>
        $(() => {
            $('[name="stsems[]"]').bootstrapDualListbox({
                moveOnSelect: false,
                nonSelectedListLabel: 'Siswa kelas non rombel',
                selectedListLabel: 'Siswa rombel {{ $classroom->name }}'
            });
        })
    </script>
@endpush
