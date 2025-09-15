@extends('counseling::layouts.default')

@section('title', 'Kelola deskripsi kasus - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-details float-left mr-2"></i>Item deskripsi
                </div>
                <div class="card-body">
                    <form action="{{ route('counseling::manage.cases.descriptions.index') }}" method="GET">
                        <div class="input-group">
                            <select name="ctg" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach ($categories as $_category)
                                    <option value="{{ $_category->id }}" @if (request('ctg') == $_category->id) selected @endif>{{ $_category->name }}</option>
                                @endforeach
                            </select>
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('counseling::manage.cases.descriptions.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table-hover border-bottom mb-0 table">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th class="text-center">Poin</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($descriptions as $description)
                                <tr>
                                    <td>{{ $loop->iteration + ($descriptions->firstItem() - 1) }}</td>
                                    <td nowrap><a href="{{ route('counseling::manage.cases.categories.edit', ['category' => $description->ctg_id, 'next' => url()->full()]) }}">{{ $description->category->name }}</a></td>
                                    <td nowrap>{{ $description->name }}</td>
                                    <td nowrap class="text-center">{{ $description->point }}</td>
                                    <td nowrap class="py-2 text-right align-middle">
                                        <a class="btn btn-warning btn-sm" href="{{ route('counseling::manage.cases.descriptions.edit', ['description' => $description->id, 'next' => url()->full()]) }}" data-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                        <form class="d-inline form-block form-confirm" action="{{ route('counseling::manage.cases.descriptions.destroy', ['description' => $description->id]) }}" method="POST"> @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus"><i class="mdi mdi-delete-outline"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center"><i>Tidak ada data</i></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $descriptions->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-briefcase-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $descriptions_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah deskripsi kasus</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-account-plus float-left mr-2"></i>Tambah deskripsi
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('counseling::manage.cases.descriptions.store') }}" method="POST"> @csrf
                        <div class="form-group required mb-3">
                            <label>Kategori</label>
                            <select name="ctg_id" class="form-control @error('name') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('name')
                                <small class="text-danger"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required mb-3">
                            <label>Deskripsi</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off">
                            @error('name')
                                <small class="text-danger"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group required mb-3">
                            <label>Poin</label>
                            <input type="number" class="form-control w-50 @error('point') is-invalid @enderror" name="point" value="{{ old('point') }}" required autocomplete="off">
                            @error('point')
                                <small class="text-danger"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
