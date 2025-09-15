@extends('administration::layouts.default')

@section('title', 'Jurusan - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item active">Jurusan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-details float-left mr-2"></i>Jurusan
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::scholar.majors.index', ['academic' => request('academic')]) }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama jurusan disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::scholar.majors.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-refresh"></i></a>
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
                <div class="list-group list-group-flush border-top">
                    @forelse($majors as $major)
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-9">
                                    <h5 class="{{ request('trash') ? 'text-muted' : '' }} mb-0">Jurusan {{ $major->name }}</h5>
                                    @forelse($major->classrooms->take(8) as $classroom)
                                        <span class="badge {{ request('trash') ? 'badge-secondary' : 'badge-dark' }}">{{ $classroom->name }}</span>
                                    @empty
                                        <span class="text-muted font-italic">Tidak rombel di jurusan ini</span>
                                    @endforelse
                                    @if ($major->classrooms->count() > 8)
                                        <span class="badge badge-secondary">+{{ $major->classrooms->count() - 8 }} lainnya</span>
                                    @endif
                                </div>
                                <div class="col-3 align-self-center text-nowrap text-right">
                                    @if ($major->trashed())
                                        <form class="d-inline form-block form-confirm" action="{{ route('administration::scholar.majors.restore', ['major' => $major->id]) }}" method="POST"> @csrf @method('PUT')
                                            <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                        </form>
                                        <form class="d-inline form-block form-confirm" action="{{ route('administration::scholar.majors.kill', ['major' => $major->id]) }}" method="POST"> @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{ $major->id }}" data-name="{{ $major->name }}" data-action="{{ route('administration::scholar.majors.update', ['major' => $major->id]) }}"><i class="mdi mdi-pencil"></i></button>
                                        <form class="d-inline form-block form-confirm" action="{{ route('administration::scholar.majors.destroy', ['major' => $major->id]) }}" method="POST"> @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Buang"><i class="mdi mdi-delete-forever-outline"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item">
                            <i>Tidak ada data jurusan</i>
                        </div>
                    @endforelse
                    <div class="list-group-item">
                        <form class="form-block" action="{{ route('administration::scholar.majors.store', ['semester_id' => $acsem->id]) }}" method="POST"> @csrf
                            <div class="form-group mb-2">
                                <label>Tambah jurusan tahun ajaran <strong>{{ $acsem->full_name }}</strong></label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="name" value="{{ request('name') }}" placeholder="Nama jurusan ...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body">
                <form class="form-block" action="{{ route('administration::scholar.majors.index') }}" method="GET">
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
                    <div class="text-value">{{ $majors_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah jurusan</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-account-group-outline"></i> Kelola rombel</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.superiors.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-file-settings-variant-outline"></i> Kelola unggulan</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::scholar.majors.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan jurusan yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form class="modal-content form-block" id="modal-edit-form" method="POST"> @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Ubah jurusan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tahun ajaran</label>
                        <strong><span class="form-control-plaintext">{{ $acsem->full_name }}</span></strong>
                    </div>
                    <div class="form-group">
                        <label>Nama jurusan</label>
                        <input id="modal-edit-input-name" class="form-control" type="text" name="name" value="" placeholder="Nama jurusan ...">
                    </div>
                    <div class="form-group mb-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const exampleModal = document.getElementById('exampleModal');

        exampleModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const action = button.getAttribute('data-action')

            const nameInput = exampleModal.querySelector('#modal-edit-input-name');
            nameInput.value = name;
            const form = exampleModal.querySelector('#modal-edit-form');
            form.action = action;
        });
    </script>
@endpush
