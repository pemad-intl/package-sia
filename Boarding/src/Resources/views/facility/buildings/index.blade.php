@extends('administration::layouts.default')

@section('title', 'Gedung - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Sarpras</li>
    <li class="breadcrumb-item active">Gedung</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Data Gedung</div>
                <div class="card-body">
                    <form action="{{ route('administration::facility.buildings.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::facility.buildings.index') }}"><i class="mdi mdi-refresh"></i></a>
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
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse($buildings as $building)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $building->kd }}</td>
                                    <td>{{ $building->name }}</td>
                                    <td>{{ $building->village }}</td>
                                    <td>
                                        @if ($building->trashed())
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::facility.buildings.restore', ['building' => $building->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::facility.buildings.kill', ['building' => $building->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Permanen"><i class="mdi mdi-delete-outline"></i></button>
                                            </form>
                                        @else
                                            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah Gedung" href="{{ route('administration::facility.buildings.show', ['building' => $building->id]) }}"><i class="mdi mdi-pencil"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::facility.buildings.destroy', ['building' => $building->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    <p style="margin-top: 10px; margin-left: 10px;">Jumlah Gedung : {{ $buildings->total() }}</p>
                </div>
                <div class="card-body">
                    {{ $buildings->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Tambah Gedung</div>
                <div class="card-body">
                    <form class="form-block" action="" method="POST"> @csrf
                        <div class="form-group">
                            <label>Kode Gedung</label>
                            <input type="text" class="form-control" name="kd" value="" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Nama Gedung</label>
                            <input type="text" class="form-control" name="name" value="" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" class="form-control" name="address" value="" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>RT</label>
                            <input type="number" class="form-control" name="rt" value="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>RW</label>
                            <input type="number" class="form-control" name="rw" value="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Kelurahan</label>
                            <input type="text" class="form-control" name="village" value="" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <select name="district_id" class="form-select">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($district as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kode Pos</label>
                            <input type="number" class="form-control" name="postal" value="" required autocomplete="off">
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
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::facility.buildings.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan Gedung yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
