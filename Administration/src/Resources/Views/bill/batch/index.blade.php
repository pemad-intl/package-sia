@extends('administration::layouts.default')

@section('title', 'Gedung - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Tagihan</li>
    <li class="breadcrumb-item active">Referensi</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Data Gelombang Pendaftaran</div>
                <div class="card-body">
                    <form action="{{ route('administration::bill.batchs.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::bill.batchs.index') }}"><i class="mdi mdi-refresh"></i></a>
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
                            <th>Gelombang</th>
                            <th>Semester</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse($billsBatch as $batch)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $batch->name }}</td>
                                    <td>{{ $batch->semesters->name }} - {{ $batch->semesters->academic->name }}</td>
                                    <td>
                                        @if ($batch->trashed())
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.batchs.restore', ['batch' => $batch->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.batchs.kill', ['batch' => $batch->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Permanen"><i class="mdi mdi-delete-outline"></i></button>
                                            </form>
                                        @else
                                            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah Komponen Pembayaran" href="{{ route('administration::bill.batchs.index', ['edit' => $batch->id]) }}"><i class="mdi mdi-pencil"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.batchs.destroy', ['batch' => $batch->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    <p style="margin-top: 10px; margin-left: 10px;">Jumlah Gelombang Pendaftaran : {{ $billBatchCount }}</p>
                </div>
                <div class="card-body">
                    {{ $billsBatch->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Kelola Gelombang Pendaftaran</div>
                <div class="card-body">
                    <form class="form-block" action="{{ isset($editBillBatch) ? route('administration::bill.batchs.update', $editBillBatch->id) : route('administration::bill.batchs.store') }}" method="POST">
                        @csrf

                        @if(isset($editBillBatch))
                            @method('PUT')
                        @endif

                        <div class="form-group mb-3">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name" 
                                value="{{ old('name', $editBillBatch->name ?? '') }}" required autocomplete="off">
                        </div>

                        <div class="form-group mb-3">
                            <label>Semester</label>
                            <select class="form-select" name="semester_id" required>
                                <option value="">Pilih</option>
                                @foreach($academicSemester as $academic)
                                    <option value="{{ $academic->id }}" 
                                            @selected(old('semester_id', $editBillBatch->semester_id ?? null) == $academic->id)>
                                     {{ $academic->name }} - {{ $academic->academic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <button class="btn btn-primary">
                                {{ isset($editBillBatch) ? 'Update' : 'Simpan' }}
                            </button>
                            @if(isset($editBillBatch))
                                <a href="{{ route('administration::bill.batchs.index') }}" class="btn btn-secondary">Batal</a>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::bill.batchs.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan Gelmbong pendaftaran yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
