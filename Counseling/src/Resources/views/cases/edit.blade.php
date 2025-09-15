@extends('counseling::layouts.default')

@section('title', 'Ubah kasus - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-briefcase-plus-outline float-left mr-2"></i>Ubah kasus
                </div>
                <div class="card-body">
                    <form action="{{ route('counseling::cases.update', ['case' => $case->id, 'next' => request('next', route('counseling::cases.index'))]) }}" method="POST"> @csrf @method('PUT')
                        <div class="form-group">
                            <label>Nama siswa</label>
                            <input class="form-control disabled" readonly disabled value="{{ $case->semester->classroom->name . ' - ' . $case->semester->student->full_name }}"></input>
                        </div>
                        <div class="form-group required w-75 mb-3">
                            <label>Kategori kasus</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if ($category->id == old('category_id', $case->category_id)) selected @endif data-descriptions="{{ $category->descriptions }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required w-75 mb-3">
                            <label>Deskripsi</label>
                            <select class="form-control @error('description') is-invalid @enderror" name="description" data-placeholder="Pilih kategori terlebih dahulu" required>
                                @foreach ($case->category->descriptions as $description)
                                    <option value="{{ $description->name }}" @if (old('description', $case->description) == $description->name) selected @endif>{{ $description->name }}</option>
                                @endforeach
                            </select>
                            @error('description')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required w-50 mb-3">
                            <label>Poin</label>
                            <input class="form-control @error('point') is-invalid @enderror" name="point" type="number" value="{{ old('point', $case->point) }}" required>
                            @error('point')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required w-75 mb-3">
                            <label>Saksi</label>
                            <input class="form-control @error('witness') is-invalid @enderror" name="witness" type="text" value="{{ old('witness', $case->witness) }}" required>
                            @error('witness')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required w-50 mb-3">
                            <label>Tanggal dan waktu</label>
                            @php
                                $breakAt = $case->break_at ? \Carbon\Carbon::parse($case->break_at)->toDateTimeLocalString() : '';
                            @endphp

                            <input class="form-control @error('break_at') is-invalid @enderror" name="break_at" type="datetime-local" value="{{ old('break_at', $breakAt) }}" required>
                            @error('break_at')
                                <small class="invalid-feedback"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary">Simpan</button>
                            <a class="btn btn-secondary" href="{{ request('next', route('counseling::cases.index')) }}">Kembali</a>
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

@push('style')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.min.css') }}">
@endpush

@push('script')
    <script type="text/javascript" src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script>
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
            theme: 'bootstrap4',
            tags: true
        });
    </script>
@endpush
