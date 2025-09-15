@extends('counseling::layouts.default')

@section('title', 'Kelola kategori kasus - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-briefcase-outline float-left mr-2"></i>Kategori
                </div>
                <div class="card-body">
                    <form action="{{ route('counseling::manage.cases.categories.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('counseling::manage.cases.categories.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>
                    @if (request('trash'))
                        <div class="alert alert-warning text-danger mb-0 mt-3">
                            <i class="mdi mdi-alert-circle-outline"></i> Menampilkan data yang dihapus
                        </div>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table-hover border-bottom mb-0 table">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th nowrap>Nama</th>
                                <th>Item deskipsi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr @if ($category->trashed()) class="text-muted bg-light" @endif>
                                    <td>{{ $loop->iteration + ($categories->firstItem() - 1) }}</td>
                                    <td nowrap>{{ $category->name }}</td>
                                    <td nowrap>{{ $category->descriptions_count ?: 0 }} item</td>
                                    <td nowrap class="py-2 text-right align-middle">
                                        @if ($category->trashed())
                                            <form class="d-inline form-block form-confirm" action="{{ route('counseling::manage.cases.categories.restore', ['category' => $category->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('counseling::manage.cases.categories.kill', ['category' => $category->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
                                            </form>
                                        @else
                                            <a class="btn btn-warning btn-sm" href="{{ route('counseling::manage.cases.categories.edit', ['category' => $category->id]) }}" data-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('counseling::manage.cases.categories.destroy', ['category' => $category->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Buang"><i class="mdi mdi-delete-outline"></i></button>
                                            </form>
                                        @endif
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
                    {{ $categories->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-briefcase-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $categories_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah kategori kasus</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-account-plus float-left mr-2"></i>Tambah kategori
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('counseling::manage.cases.categories.store') }}" method="POST"> @csrf
                        <div class="form-group required mb-3">
                            <label>Nama kategori</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off">
                            @error('name')
                                <small class="text-danger"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('counseling::manage.cases.categories.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan kategori yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
