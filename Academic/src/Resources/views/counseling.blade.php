@extends('academic::layouts.default')

@section('title', 'Data konseling - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-file-cabinet float-left mr-2"></i>Data konseling
                </div>
                <div class="card-body">

                    <form action="{{ route('academic::counselings.index') }}" method="GET">
                        <div class="input-group mb-2">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama/deskripsi disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('academic::counselings.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                        <small class="text-muted">Menampilkan data konseling Tahun Ajaran <strong>{{ $acsem->full_name }}</strong></small>
                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            @if (session('success'))
                                <div id="flash-success" class="alert alert-success mt-4">
                                    {!! session('success') !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table-hover border-bottom mb-0 table">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th nowrap>Nama</th>
                                <th>Kasus</th>
                                <th>Tindak lanjut</th>
                                {{-- <th></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($counselings as $counseling)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration + ($counselings->firstItem() - 1) }}</td>
                                    <td nowrap>
                                        {{ $counseling->semester->student->full_name }} <br>
                                        <small class="text-muted">{{ $counseling->semester->classroom->name }}</small>
                                    </td>
                                    <td style="min-width: 200px;">
                                        {{ $counseling->description }} <br>
                                        <small class="text-muted">{{ $counseling->category->name }}</small>
                                    </td>
                                    <td nowrap>{{ $counseling->follow_up }}</td>
                                    {{-- <td nowrap class="py-2 text-right align-middle">
                                        <a class="btn btn-warning btn-sm" href="{{ route('academic::counselings.edit', ['counseling' => $counseling->id]) }}" data-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                        <form class="d-inline form-block form-confirm" action="{{ route('counseling::counselings.destroy', ['counseling' => $counseling->id]) }}" method="POST"> @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus"><i class="mdi mdi-delete-outline"></i></button>
                                        </form>
                                    </td> --}}
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
                    {{ $counselings->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-file-cabinet float-right"></i>
                    </div>
                    <div class="text-value">{{ $counselings_count }}</div>
                    <small class="text-muted text-uppercounseling font-weight-bold">Jumlah konseling saat ini </small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::counselings.create', ['next' => url()->full()]) }}"><i class="mdi mdi-file-plus-outline"></i> Input konseling baru</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.counseling.categories.index') }}"><i class="mdi mdi-file-cabinet"></i> Kelola kategori</a>
                </div>
            </div>
        </div>
    </div>
@endsection
