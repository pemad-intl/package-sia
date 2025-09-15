@extends('administration::layouts.default')

@section('title', 'Data siswa - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item active">Data siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-details float-left mr-2"></i>Data siswa
                </div>
                <div class="card-body">
                    <form action="{{ route('administration::scholar.students.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::scholar.students.index') }}"><i class="mdi mdi-refresh"></i></a>
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
                                <th>NIS</th>
                                <th></th>
                                <th nowrap>Nama lengkap</th>
                                <th>JK</th>
                                <th>Angkatan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr @if ($student->trashed()) class="text-muted bg-light" @endif>
                                    <td>{{ $loop->iteration + ($students->firstItem() - 1) }}</td>
                                    <td nowrap>{{ $student->nis }}</td>
                                    <td class="py-2" width="35">
                                        <img class="rounded-circle" src="{{ $student->user->profile->avatar_path }}" height="32" alt="">
                                    </td>
                                    <td nowrap>
                                        @if ($student->trashed() || $student->user->is(auth()->user()))
                                            {{ $student->user->profile->name }}
                                        @else
                                            <a href="{{ route('administration::scholar.students.show', ['student' => $student->id]) }}">{{ $student->user->profile->name }}</a>
                                        @endif
                                    </td>
                                    <td nowrap>{{ $student->user->profile->sex_name ?: '-' }}</td>
                                    <td nowrap>{{ $student->generation->year ?: '-' }}</td>
                                    <td nowrap class="py-2 text-right align-middle">
                                        @if ($student->user->isnot(auth()->user()))
                                            @if ($student->trashed())
                                                <form class="d-inline form-block form-confirm" action="{{ route('administration::scholar.students.restore', ['student' => $student->id]) }}" method="POST"> @csrf @method('PUT')
                                                    <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                                </form>
                                                <form class="d-inline form-block form-confirm" action="{{ route('administration::scholar.students.kill', ['student' => $student->id]) }}" method="POST"> @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
                                                </form>
                                            @else
                                                <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah siswa" href="{{ route('administration::scholar.students.show', ['student' => $student->id]) }}"><i class="mdi mdi-pencil"></i></a>
                                                <form class="d-inline form-block form-confirm" action="{{ route('administration::scholar.students.destroy', ['student' => $student->id]) }}" method="POST"> @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Buang"><i class="mdi mdi-delete-outline"></i></button>
                                                </form>
                                            @endif
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
                    {{ $students->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $students_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah siswa</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-transfer float-left mr-2"></i>Impor data siswa
                </div>
                <div class="card-body">
                    <form class="form-block form-confirm" action="{{ route('administration::scholar.students.import') }}" method="POST" enctype="multipart/form-data"> @csrf
                        <p>Download template <a href="{{ route('administration::scholar.students.export') }}" target="download">di sini</a></p>
                        <div class="form-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('file') is-invalid @enderror" name="file" id="file">
                                <label class="custom-file-label" for="file">Pilih file</label>
                            </div>
                            @error('file')
                                <small class="text-danger form-text">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-import"></i> Impor data</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.students.create') }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah siswa</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::scholar.students.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan siswa yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(() => {
            function readURL(input) {
                if (input.files && input.files[0]) {
                    $('[for="file"]').html(input.files[0].name)
                }
            }

            $("#file").change(function(e) {
                readURL(this);
            });
        })
    </script>
@endpush
