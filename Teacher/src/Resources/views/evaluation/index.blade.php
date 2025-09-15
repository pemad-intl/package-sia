@extends('teacher::layouts.default')

@section('title', 'Daftar prestasi - ' )

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card bg-white mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-badge-horizontal-outline position-absolute" style="top: 10pt; right: 40pt; font-size: 40pt;"></i>
                    <h2><strong>Tipe Penilaian Pertemuan </strong></h3>
                </div>
            </div>
            <h2>
                {{-- <a class="text-decoration-none small text-primary" href="{{ request('next', route('teacher::meet', ['meet' => $meet->id])) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a> --}}
                <i class="mdi mdi-arrow-left-circle-outline"></i> Daftar Penilaian
            </h2>


            <hr>

            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                        <div id="flash-success" class="alert alert-success mt-4">
                            {!! session('success') !!}
                        </div>
                    @endif

                    @if (session('error'))
                        <div id="flash-danger" class="alert alert-danger mt-4">
                            {!! session('error') !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-account-badge-horizontal-outline me-1"></i> Jenis Penilaian
                    </div>
                </div>


                {{-- <form class="form-block form-confirm" action="{{ route('teacher::supervisor', ['meet' => $meet->id, 'next' => url()->current()]) }}" method="POST"> @csrf @method('PUT') --}}
                    <div class="table-responsive">
                        <table class="table-bordered table-striped table-hover mb-0 table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Penilaian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjectEvalMeet as $meetEval)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $meetEval->name }}</td>
                                        <td><a class="btn btn-warning" href="{{ route('teacher::evaluation.index', ['meet' => $meet, 'evaluation' => $meetEval->id]) }}"><i class="bx bx-pencil"></i></a>
                                            <form action="{{ route('teacher::evaluation.destroy', ['evaluation' => $meetEval->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jenis penilaian ini?')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Hapus Prestasi">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="4">Tidak ada data jenis penilaian</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                {{-- </form> --}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-book-outline"></i> Kelola Tipe Penilaian
                </div>
                <div class="card-body">
                   @if(!empty($editData))
                        <form action="{{ route('teacher::evaluation.update', ['evaluation' => $editData->id]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="form-group mb-3">
                                <label>Nama Penilaian</label>
                                <input class="form-control" type="text" name="name" value="{{ old('name', $editData->name) }}" required />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('teacher::evaluation.store', ['meet' => $meet]) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label>Nama Penilaian</label>
                                <input class="form-control" type="text" name="name" value="{{ old('name') }}" required />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-book-outline"></i> Salin Dari rombel lain
                </div>

                <div class="card-body">
                    <form action="{{ route('teacher::copy_evaluation', ['meet' => $meet]) }}" method="POST">
                    @csrf

                    <select class="form-select mb-3" name="copy_rombel">
                        <option value="">Pilih Rombel</option>
                        @foreach($sbjMeet as $met)
                            <option value="{{ $met->id }}">{{$met->classroom->name}}</option>
                        @endforeach
                    </select>

                    <button class="btn btn-primary" type="submit" name="rombel">Salin</button>
                    </form>
                </div>
            </div> --}}
        </div>
    </div>
@endsection