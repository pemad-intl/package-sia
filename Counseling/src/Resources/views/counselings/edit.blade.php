@extends('counseling::layouts.default')

@section('title', 'Ubah kasus - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-file-plus float-left mr-2"></i>Ubah kasus
                </div>
                <div class="card-body">
                    <form action="{{ route('counseling::counselings.update', ['counseling' => $counseling->id, 'next' => request('next', route('counseling::counselings.index'))]) }}" method="POST"> @csrf @method('PUT')
                        <div class="form-group">
                            <label>Nama siswa</label>
                            <input class="form-control disabled" readonly disabled value="{{ $counseling->semester->classroom->name . ' - ' . $counseling->semester->student->full_name }}"></input>
                        </div>
                        <div class="form-group required w-75">
                            <label>Kategori konseling</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if ($category->id == old('category_id', $counseling->category_id)) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description', $counseling->description) }}</textarea>
                            @error('description')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label>Tindak lanjut</label>
                            <textarea class="form-control @error('follow_up') is-invalid @enderror" name="follow_up" required>{{ old('follow_up', $counseling->follow_up) }}</textarea>
                            @error('follow_up')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary">Simpan</button>
                            <a class="btn btn-secondary" href="{{ request('next', route('counseling::counselings.index')) }}">Kembali</a>
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
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::counselings.index') }}"><i class="mdi mdi-file-cabinet"></i> Data kasus</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.counseling.categories.index') }}"><i class="mdi mdi-file-cabinet"></i> Kelola kategori</a>
                </div>
            </div>
        </div>
    </div>
@endsection
