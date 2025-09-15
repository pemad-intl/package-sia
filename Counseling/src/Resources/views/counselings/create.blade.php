@extends('counseling::layouts.default')

@section('title', 'Input konseling baru - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-briefcase-plus-outline float-left mr-2"></i>Input konseling baru
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if (session('success'))
                                <div id="flash-success" class="alert alert-success mt-4">
                                    {!! session('success') !!}
                                </div>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('counseling::counselings.store', ['next' => request('next')]) }}" method="POST"> @csrf
                        <div class="form-group required mb-3">
                            <label>Nama siswa</label>
                            <select class="form-control @error('smt_id') is-invalid @enderror" name="smt_id" data-placeholder="Cari nama siswa disini ..." required>
                                <option value=""></option>
                                @foreach ($classrooms as $classroom => $semesters)
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}" @if (in_array($semester->id, old('smt_id', []))) selected @endif>{{ $classroom . ' - ' . $semester->student->full_name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('smt_id')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group required w-75 mb-3">
                            <label>Kategori konseling</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if ($category->id == old('category_id')) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group required mb-3">
                            <label>Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description') }}</textarea>
                            @error('description')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group required mb-3">
                            <label>Tindak lanjut</label>
                            <textarea class="form-control @error('follow_up') is-invalid @enderror" name="follow_up" required>{{ old('follow_up') }}</textarea>
                            @error('follow_up')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            @include('account::includes.account-info')
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::counselings.index') }}"><i class="mdi mdi-briefcase-account-outline"></i> Data konseling</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.counseling.categories.index') }}"><i class="mdi mdi-briefcase-outline"></i> Kelola kategori</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.min.css') }}">
@endpush

@push('script')
    <script type="text/javascript" src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script>
        $('[name="smt_id"]').select2({
            minimumInputLength: 1,
            theme: 'bootstrap4'
        });
    </script>
@endpush
