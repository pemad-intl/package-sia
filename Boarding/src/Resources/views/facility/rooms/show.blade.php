@extends('administration::layouts.default')

@section('title', 'Ruangan - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Sarpras</li>
    <li class="breadcrumb-item">Gedung</li>
    <li class="breadcrumb-item active">Ruangan</li>
@endsection

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::facility.rooms.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Lihat detail Ruang
    </h2>
    <div class="row">
        <div class="col-sm-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-1">Info Ruang</h4>
                    <p class="text-muted mb-2">Informasi tentang Ruang {{ $room->name }}</p>
                </div>
                <div class="list-group list-group-flush">

                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Edit Ruang</div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::facility.rooms.update', ['room' => $room->id]) }}" method="POST"> @csrf @method('PUT')
                        <div class="form-group">
                            <label>Kode Ruang</label>
                            <input type="text" class="form-control" name="kd" value="{{ $room->kd }}" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Nama Ruang</label>
                            <input type="text" class="form-control" name="name" value="{{ $room->name }}" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Kapasitas</label>
                            <input type="text" class="form-control" name="capacity" value="{{ $room->capacity }}" required autocomplete="off">
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
