@extends('counseling::layouts.default')

@section('title', 'Data presensi - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-multiple-outline float-left mr-2"></i>Data baru
                </div>
                <div class="card-body">
                    <form action="{{ route('counseling::presences.index') }}" method="GET">
                        <div class="input-group mb-2">
                            <select class="form-control" name="classroom" type="text" required>
                                <option value="">Pilih rombel</option>
                                @foreach ($acsem->classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" @if (request('classroom') == $classroom->id) selected @endif>{{ $classroom->full_name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('counseling::presences.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                        <small class="text-muted">Menampilkan data presensi Tahun Ajaran <strong>{{ $acsem->full_name }}</strong></small>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table-bordered table-striped table-hover mb-0 table">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal</th>
                                @foreach ($presenceList as $v)
                                    <th class="text-center">{{ strtoupper(substr($v, 0, 1)) }}</th>
                                @endforeach
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presences as $presence)
                                @php($counts = $presence->presence->countBy('name'))
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($presence->presenced_at)->isoFormat('LLL') }}</td>
                                    @foreach ($presenceList as $v)
                                        <td class="text-center">{{ $counts[$v] ?? '-' }}</td>
                                    @endforeach
                                    <td class="py-2 text-center" width="140" nowrap>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-action="{{ route('counseling::presences.update', ['presence' => $presence->id]) }}" data-presence="{{ $presence->presence->pluck('presence', 'semester_id') }}"><i class="mdi mdi-eye"></i></button>
                                        <form class="form-block form-confirm d-inline" action="{{ route('counseling::presences.destroy', ['presence' => $presence->id]) }}" method="POST"> @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm text-nowrap"><i class="mdi mdi-delete"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="{{ count($presenceList) + 3 }}">Tidak ada presensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            @include('counseling::includes.employee-info', ['employee' => $user->employee])
            @include('account::includes.account-info')
        </div>
    </div>
@endsection

@push('scripts')
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail presensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table-bordered table-striped table-hover mb-0 table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    @foreach ($presenceList as $v)
                                        <th class="text-center">{{ strtoupper(substr($v, 0, 1)) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="modal-detail-body">
                                <tr>
                                    <td class="text-center" colspan="{{ count($presenceList) + 3 }}">Tidak ada presensi</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @isset($currentClassroom->stsems)
        <script>
            $(() => {
                let stsemsFullName = {!! $currentClassroom->stsems->pluck('student.full_name', 'id') !!};
                let stsemsNis = {!! $currentClassroom->stsems->pluck('student.nis', 'id') !!};
                $('#exampleModal').on('show.bs.modal', (e) => {
                    let presences = $(e.relatedTarget).data('presence')
                    let i = 1;
                    $('#modal-detail-body').html('');
                    $.each(presences, (semester, value) => {
                        let appender = '<tr><td class="text-center">' + (i++) + '</td><td>' + stsemsNis[semester] + '</td><td>' + stsemsFullName[semester] + '</td><td class="text-center">' + (value == 0 ? '🔵' : '') + '</td><td class="text-center">' + (value == 1 ? '🔵' : '') + '</td><td class="text-center">' + (value == 2 ? '🔵' : '') + '</td><td class="text-center">' + (value == 3 ? '🔵' : '') + '</td></tr>'
                        $('#modal-detail-body').append(appender);
                    })
                })
            })
        </script>
    @endisset
@endpush
