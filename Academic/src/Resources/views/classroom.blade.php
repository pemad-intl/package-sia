@extends('academic::layouts.default')

@section('title', 'Data konseling - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-file-cabinet float-left mr-2"></i>Data murid
                </div>
                <div class="card-body">

                    <form action="{{ route('academic::classroom.index') }}" method="GET">
                        <div class="input-group mb-2">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama/deskripsi disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('academic::classroom.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                        <small class="text-muted">Menampilkan data konseling Tahun Ajaran <strong>{{ $acsem->full_name }}</strong></small>
                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            @if (session('success'))
                                <div id="flash-success" class="alert alert-success mt-4">
                                    {!! session('success') !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table-hover border-bottom mb-0 table">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th nowrap>Nama Lengkap</th>
                                <th>Nisn</th>
                                {{-- <th></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration + ($students->firstItem() - 1) }}</td>
                                    <td>
                                        {{ $student->student->user->name }}
                                    </td>
                                    <td>{{ $student->student->nisn }}</td>
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
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-file-cabinet float-right"></i>
                    </div>
                    <div class="text-value">{{ $studentsCount }}</div>
                    <small class="text-muted text-uppercounseling font-weight-bold">Jumlah murid di kelas ini </small>
                </div>
            </div>
        </div>
    </div>
@endsection
