@extends('administration::layouts.default')

@section('title', 'Gedung - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Sarpras</li>
    <li class="breadcrumb-item active">Gedung</li>
@endsection

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::facility.buildings.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Lihat detail Gedung
    </h2>
    <div class="row">
        <div class="col-sm-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-1">Info Gedung</h4>
                    <p class="text-muted mb-2">Informasi tentang Gedung {{ $building->name }}</p>
                </div>
                <div class="list-group list-group-flush">

                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Edit Gedung</div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::facility.buildings.update', ['building' => $building->id]) }}" method="POST"> @csrf @method('PUT')
                        <div class="form-group mb-3">
                            <label>Kode Gedung</label>
                            <input type="text" class="form-control" name="kd" value="{{ $building->kd }}" required autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label>Nama Gedung</label>
                            <input type="text" class="form-control" name="name" value="{{ $building->name }}" required autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label>Alamat</label>
                            <input type="text" class="form-control" name="address" value="{{ $building->address }}" required autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label>RT</label>
                            <input type="text" class="form-control" name="rt" value="{{ $building->rt }}" required autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label>RW</label>
                            <input type="text" class="form-control" name="rw" value="{{ $building->rw }}" required autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label>Kelurahan</label>
                            <input type="text" class="form-control" name="village" value="{{ $building->village }}" required autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label>Kecamatan</label>
                            <select name="district_id" class="form-select">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($districtAll as $value)
                                    <option {{ $building->district_id == $value->id ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Kode Pos</label>
                            <input type="text" class="form-control" name="postal" value="{{ $building->postal }}" required autocomplete="off">
                        </div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
