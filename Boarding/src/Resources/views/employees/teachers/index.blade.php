@extends('administration::layouts.default')

@section('title', 'Kelola guru - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kepegawaian</li>
    <li class="breadcrumb-item active">Guru dan Karyawan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-details float-left mr-2"></i>Data guru dan karyawan
                </div>
                <div class="card-body">
                    <form action="{{ route('administration::employees.teachers.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::employees.teachers.index') }}"><i class="mdi mdi-refresh"></i></a>
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
                                <th>NIP</th>
                                <th></th>
                                <th nowrap>Nama lengkap</th>
                                <th>JK</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr @if ($teacher->trashed()) class="text-muted bg-light" @endif>
                                    <td>{{ $loop->iteration }}</td>
                                    <td nowrap>{{ $teacher->employee->nip ?: '-' }}</td>
                                    <td class="py-2" width="35">
                                        <img class="rounded-circle" src="{{ $teacher->employee->user->profile->avatar_path }}" height="32" alt="">
                                    </td>
                                    <td nowrap>
                                        @if ($teacher->trashed() || $teacher->employee->user->is(auth()->user()))
                                            {{ $teacher->employee->user->profile->name }}
                                        @else
                                            <a href="{{ route('administration::employees.teachers.show', ['teacher' => $teacher->id]) }}">{{ $teacher->employee->user->profile->name }}</a>
                                        @endif
                                    </td>
                                    <td nowrap>{{ $teacher->employee->user->profile->sex_name ?: '-' }}</td>
                                    <td nowrap class="py-2 text-right align-middle">
                                        @if ($teacher->trashed())
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::employees.teachers.restore', ['teacher' => $teacher->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::employees.teachers.kill', ['teacher' => $teacher->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Permanen"><i class="mdi mdi-delete-outline"></i></button>
                                            </form>
                                        @else
                                            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah guru" href="{{ route('administration::employees.teachers.show', ['teacher' => $teacher->id]) }}"><i class="mdi mdi-pencil"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::employees.teachers.destroy', ['teacher' => $teacher->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    {{ $teachers->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $teacher_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah guru dan karyawan</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::employees.teachers.create') }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah guru</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::employees.teachers.create', ['user' => 1]) }}"><i class="mdi mdi-account-plus-outline"></i> Tambah guru dari pengguna</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::employees.teachers.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan data yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
