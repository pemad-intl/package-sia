@extends('academic::layouts.default')

@section('title', 'Data konseling - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-file-cabinet float-left mr-2"></i>Data konseling
                </div>
                <div class="card-body">

                    <form action="{{ route('academic::counselings.index') }}" method="GET">
                        <div class="input-group mb-2">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama/deskripsi disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('academic::counselings.index') }}"><i class="mdi mdi-refresh"></i></a>
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
                                <th nowrap>Tipe</th>
                                {{-- <th>Nama Siswa</th> --}}
                                <th>Keterangan</th>
                                {{-- <th></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activityStudent as $activity)
                                <tr>
                                    <td>
                                        {{ $loop->iteration + ($activityStudent->currentPage() - 1) * $activityStudent->perPage() }}
                                    </td>
                                    <td>
                                        @if($activity->modelable_type === \Modules\Boarding\Models\BoardingStudentsLeave::class)
                                            <span class="badge bg-info">Izin Pulang</span>
                                        @elseif($activity->modelable_type === \Modules\Boarding\Models\BoardingStudents::class)
                                            <span class="badge bg-success">Pondok</span>
                                        @endif
                                    </td>
                                    <td>{!! $activity->message ?? '-' !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $activityStudent->links() }}
                    {{-- {{ $counselings->appends(request()->all())->links() }} --}}
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-file-cabinet float-right"></i>
                    </div>
                    <h1 class="text-value">{{ $activityStudentNum }}</h1>
                    <small class="text-muted text-uppercounseling font-weight-bold">Jumlah activity saat ini </small>
                </div>
            </div>
            {{-- <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::counselings.create', ['next' => url()->full()]) }}"><i class="mdi mdi-file-plus-outline"></i> Input konseling baru</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.counseling.categories.index') }}"><i class="mdi mdi-file-cabinet"></i> Kelola kategori</a>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
