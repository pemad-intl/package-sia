@extends('counseling::layouts.default')

@section('title', 'Data kasus - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-briefcase-account-outline float-left mr-2"></i>Data kasus
                </div>
                <div class="card-body">
                    <form action="{{ route('counseling::cases.index') }}" method="GET">
                        <div class="input-group mb-2">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama/kasus disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('counseling::cases.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                        <small class="text-muted">Menampilkan data kasus Tahun Ajaran <strong>{{ $acsem->full_name }}</strong></small>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table-hover border-bottom mb-0 table">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th nowrap>Nama</th>
                                <th>Kasus</th>
                                <th>Saksi</th>
                                <th class="text-center">Poin</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cases as $case)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration + ($cases->firstItem() - 1) }}</td>
                                    <td nowrap>
                                        {{ $case->semester->student->full_name }} <br>
                                        <small class="text-muted">{{ $case->semester->classroom->name }}</small>
                                    </td>
                                    <td style="min-width: 200px;">
                                        {{ $case->description }} <br>
                                        <small class="text-muted">{{ $case->category->name }}</small>
                                    </td>
                                    <td class="align-middle" nowrap>{{ $case->witness }}</td>
                                    <td class="text-center align-middle" nowrap><strong>{{ $case->point }}</strong></td>
                                    <td nowrap class="py-2 text-right align-middle">
                                        <a class="btn btn-warning btn-sm" href="{{ route('counseling::cases.edit', ['case' => $case->id]) }}" data-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                        <form class="d-inline form-block form-confirm" action="{{ route('counseling::cases.destroy', ['case' => $case->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    {{ $cases->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-briefcase-account-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $cases_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah kasus saat ini </small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::cases.create', ['next' => url()->full()]) }}"><i class="mdi mdi-briefcase-plus-outline"></i> Input kasus baru</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.cases.categories.index') }}"><i class="mdi mdi-briefcase-outline"></i> Kelola kategori</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.cases.descriptions.index') }}"><i class="mdi mdi-briefcase-outline"></i> Kelola deskripsi</a>
                </div>
            </div>
        </div>
    </div>
@endsection
