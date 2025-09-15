@extends('administration::layouts.default')

@section('title', 'Pertemuan - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kurikulum</li>
    <li class="breadcrumb-item active">Pertemuan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-details float-left mr-2"></i>Pertemuan
                </div>
                <div class="card-body">
                    <form action="{{ route('administration::curriculas.meets.index', ['academic' => request('academic')]) }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama rombel disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::curriculas.meets.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>
                    @if (session('success'))
                        <div id="flash-success" class="alert alert-success mt-4">
                            {!! session('success') !!}
                        </div>
                    @endif

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
                                <th></th>
                                <th>Rombel</th>
                                <th>Mapel</th>
                                <th>Pengajar</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meets as $meet)
                                <tr @if ($meet->trashed()) class="text-muted bg-light" @endif>
                                    <td class="align-middle">{{ $loop->iteration + ($meets->firstItem() - 1) }}</td>
                                    <td nowrap class="align-middle">
                                        <div style="width: 26pt; height: 26pt;" class="bg-{{ $meet->props->color ?? 'secondary' }} rounded-circle d-table-cell text-center align-middle">
                                            {{ $meet->teacher->full_name[0] }}
                                        </div>
                                    </td>
                                    <td nowrap>
                                        <strong>{{ $meet->classroom->name }}</strong> <br>
                                        {{ $meet->classroom->major->name ?? '-' }} {{ $meet->classroom->superior->name }}
                                    </td>
                                    <td nowrap class="align-middle">
                                        <strong>{{ $meet->subject->name }}</strong> <br>
                                        {{ $meet->plans_count }} pertemuan
                                    </td>
                                    <td nowrap class="align-middle">
                                        <strong>{{ $meet->teacher->full_name }}</strong> <br>
                                        NIP. {{ $meet->teacher->employee->nip }}
                                    </td>
                                    <td nowrap class="py-2 text-right align-middle">
                                        @if ($meet->trashed())
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::curriculas.meets.restore', ['meet' => $meet->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::curriculas.meets.kill', ['meet' => $meet->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
                                            </form>
                                        @else
                                            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah pertemuan" href="{{ route('administration::curriculas.meets.edit', ['meet' => $meet->id]) }}"><i class="mdi mdi-pencil"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::curriculas.meets.destroy', ['meet' => $meet->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    {{ $meets->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body">
                <form class="form-block" action="{{ route('administration::curriculas.meets.index') }}" method="GET">
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
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $meets_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah pertemuan</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::curriculas.meets.create', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah pertemuan</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-account-group-outline"></i> Kelola rombel</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::curriculas.subjects.index', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-book-outline"></i> Kelola mapel</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::employees.teachers.index') }}"><i class="mdi mdi-account-circle-outline"></i> Data guru</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::curriculas.meets.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan pertemuan yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
