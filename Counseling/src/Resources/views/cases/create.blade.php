@extends('counseling::layouts.default')

@section('title', 'Input kasus baru - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-briefcase-plus-outline float-left mr-2"></i>Input kasus baru
                </div>
                <div class="card-body">
                    <div class="row">
                        @if (session('success'))
                            <div id="flash-success" class="alert alert-success mt-4">
                                {!! session('success') !!}
                            </div>
                        @endif
                    </div>
                    <form action="{{ route('counseling::cases.store', ['next' => request('next')]) }}" method="POST"> @csrf
                        <div class="form-group required mb-3">
                            <label>Nama siswa</label>
                            <select class="form-control @error('smt_id') is-invalid @enderror" name="smt_id[]" data-placeholder="Cari nama siswa disini ..." multiple="multiple" required>
                                @foreach ($classrooms as $classroom => $semesters)
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}" @if (in_array($semester->id, old('smt_id', []))) selected @endif>{{ $classroom . ' - ' . $semester->student->full_name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('smt_id')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required w-75 mb-3">
                            <label>Kategori kasus</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if ($category->id == old('category_id')) selected @endif data-descriptions="{{ $category->descriptions }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required w-75 mb-3">
                            <label>Deskripsi</label>
                            <select class="form-control @error('description') is-invalid @enderror" name="description" data-placeholder="Pilih kategori terlebih dahulu" required>
                                @if (old('description'))
                                    @foreach ($categories->firstWhere('id', old('category_id'))->descriptions as $description)
                                        <option value="{{ $description->name }}" @if (old('description') == $description->name) selected @endif>{{ $description->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('description')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required w-50 mb-3">
                            <label>Poin</label>
                            <input class="form-control @error('point') is-invalid @enderror" name="point" type="number" value="{{ old('point') }}" required>
                            @error('point')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required w-75 mb-3">
                            <label>Saksi</label>
                            <input class="form-control @error('witness') is-invalid @enderror" name="witness" type="text" value="{{ old('witness') }}" required>
                            @error('witness')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required w-50 mb-3">
                            <label>Tanggal dan waktu</label>
                            <input class="form-control @error('break_at') is-invalid @enderror" name="break_at" type="datetime-local" value="{{ old('break_at', date('Y-m-d\TH:i')) }}" required>
                            @error('break_at')
                                <small class="invalid-feedback"> {{ $message }} </small>
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
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::cases.index') }}"><i class="mdi mdi-briefcase-account-outline"></i> Data kasus</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.cases.categories.index') }}"><i class="mdi mdi-briefcase-outline"></i> Kelola kategori</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.cases.descriptions.index') }}"><i class="mdi mdi-briefcase-outline"></i> Kelola deskripsi</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('[name="smt_id[]"]').select2({
            minimumInputLength: 1,
            theme: 'bootstrap5'
        });
        $('[name="category_id"]').on('change', (e) => {
            var descs = $('[name="category_id"] > option:selected').data('descriptions');
            $('[name="description"]').html('')
            $.each(descs, (k, v) => {
                $('[name="description"]').append(`<option value="${v.name}" data-point="${v.point}">${v.name}</option>`);
            });
            $('[name="point"]').val(descs[0].point || 0)
        });
        $('[name="description"]').on('change', (e) => {
            var point = $('[name="description"] > option:selected').data('point');
            $('[name="point"]').val(point)
        })
        $('[name="description"]').select2({
            theme: 'bootstrap5',
            tags: true
        });
    </script>
@endpush
