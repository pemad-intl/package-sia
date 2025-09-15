@extends('administration::layouts.default')

@section('title', 'Registrasi semester - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item active">Registrasi semester</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-details float-left mr-2"></i>Registrasi semester
                </div>
                <div class="card-body">
                    <form id="filter-form" class="form-inline" action="{{ route('administration::scholar.semesters.index') }}" method="GET">
                        <select class="form-control mb-sm-0 mr-sm-3 mb-2" name="acsem" required data-live-search="true">
                            <option value="">-- Pilih tahun ajaran --</option>
                            @foreach ($acsems as $_acsem)
                                <option value="{{ $_acsem->id }}" @if (request('acsem', $acsem->id) == $_acsem->id) selected @endif>{{ $_acsem->full_name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari rombel disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::scholar.semesters.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>

                    <div class="col-md-12">
                        @if (session('success'))
                            <div id="flash-success" class="alert alert-success mt-4">
                                {!! session('success') !!}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table-hover border-bottom mb-0 table">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th nowrap>Nama lengkap</th>
                                <th class="text-center">Rombel</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stsems as $stsem)
                                <tr>
                                    <td>{{ $loop->iteration + ($stsems->firstItem() - 1) }}</td>
                                    <td nowrap>{{ $stsem->student->nis }}</td>
                                    <td nowrap>
                                        <a href="{{ route('administration::scholar.students.show', ['student' => $stsem->student_id]) }}">{{ $stsem->student->user->profile->name }}</a>
                                    </td>
                                    <td nowrap class="text-center">{{ $stsem->classroom->name ?? '-' }}</td>
                                    <td nowrap class="py-2 text-right align-middle">
                                        <a class="btn btn-primary btn-sm" data-toggle="tooltip" title="Detail siswa" href="{{ route('administration::scholar.students.show', ['student' => $stsem->student_id]) }}"><i class="mdi mdi-eye"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center"><i>Tidak ada data</i></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $stsems->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $stsems_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah siswa aktif</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-transfer float-left mr-2"></i>Impor data semester siswa
                </div>
                <div class="card-body">
                    <form action="{{ route('administration::scholar.semesters.import') }}" method="POST" enctype="multipart/form-data"> @csrf
                        <p>Download template <a href="{{ route('administration::scholar.semesters.export') }}">di sini</a></p>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('file') is-invalid @enderror" name="file" id="file">
                                <label class="custom-file-label" for="file">Pilih file</label>
                            </div>
                        </div>
                        @error('file')
                            <div><small class="text-danger"></small>{{ $message }}</div>
                        @enderror
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
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.semesters.registrations') }}"><i class="mdi mdi-account-multiple-plus-outline"></i> Registrasi baru</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.semesters.promotions', ['acsem' => request('acsem', $acsem->id ?? null)]) }}"><i class="mdi mdi-account-multiple-plus-outline"></i> Kenaikan kelas</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.students.index') }}"><i class="mdi mdi-account-group-outline"></i> Data siswa</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(() => {
            $('[name="acsem"]').on('change', (e) => {
                $('#filter-form').submit();
            })

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
