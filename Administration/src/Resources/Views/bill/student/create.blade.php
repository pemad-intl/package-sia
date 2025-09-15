@extends('administration::layouts.default')

@section('title', 'Pertemuan - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Tagihan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::bill.students.index')) }}">Pembayaran Siswa</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::bill.students.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Tambah Pembayaran Siswa
    </h2>
    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4"> 
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::bill.students.store') }}" method="POST"> @csrf
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Daftar Tagihan pembayaran</h4>

                                <div class="row">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama Komponen Pembayaran</th>
                                                <th>Tipe Tagihan</th>
                                            </tr>
                                        </thead>
                                        @foreach($referenceComponent as $reference)
                                            <tr>
                                                <td><input type="checkbox" name="item[{{ $reference->id }}]"> {{$reference->kd}}</td>
                                                <td>{{$reference->name}}</td>
                                                <td>{{$reference->type->name}}</td>
                                            </tr>
                                        @endforeach
                                    </table>

                                    <div class="form-group mb-3">
                                        <label>Pilih Rombel</label>

                                        <select name="classroom" class="form-select">
                                            <option value="">Pilih</option>
                                            @foreach($classRoomStudent as $classroom)
                                                <option value="{{ $classroom->id }}">{{$classroom->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Murid</label>
                                        <div class="alert alert-info text-center">Pilih Rombel dahulu</div>
                                        {{-- <select class="form-select" name="students[]" multiple size="5">
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
