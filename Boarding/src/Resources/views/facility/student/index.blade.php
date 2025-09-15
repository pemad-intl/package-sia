@extends('boarding::layouts.default')

@section('title', 'Gedung - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Gedung</li>
    <li class="breadcrumb-item active">Siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Data Asrama Siswa</div>
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

                    @if (session('error'))
                        <div id="flash-danger" class="alert alert-danger mt-4">
                            {!! session('error') !!}
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
                            <th>Nama Siswa</th>
                            <th>Gedung</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse($boardingFacilityStdn as $building)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $building->student->user->profile->name }}</td>
                                    <td>{{ $building->ground->name }}</td>
                                    <td>
                                        @if ($building->trashed())
                                            <form class="d-inline form-block form-confirm" action="{{ route('boarding::facility.buildings.restore', ['building' => $building->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('boarding::facility.buildings.kill', ['building' => $building->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Permanen"><i class="mdi mdi-delete-outline"></i></button>
                                            </form>
                                        @else
                                            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah Asrama Siswa" href="{{ route('boarding::facility.student.edit', ['student' => $building->id]) }}"><i class="mdi mdi-pencil"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('boarding::facility.student.destroy', ['student' => $building->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    <p style="margin-top: 10px; margin-left: 10px;">Jumlah Murid terdaftar : {{ $boardingFacilityStdn->total() }}</p>
                </div>
                <div class="card-body">
                    {{ $boardingFacilityStdn->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Tambah Gedung</div>
                <div class="card-body">
                    <form class="form-block" action="{{ isset($editItem) ? route('boarding::facility.student.update', ['student' => $editItem->id, 'next' => request()->fullUrl()]) : route('boarding::facility.student.store', ['next' => request()->fullUrl()]) }}" method="POST">
                        @csrf
                        @if (isset($editItem))
                            @method('PUT')
                        @endif

                        <input type="hidden" id="selected-room-id" value="{{ $editItem->room_id ?? '' }}">
                        <input type="hidden" id="selected-building-id" value="{{ $editItem->building_id ?? '' }}">

                        <div class="form-group mb-3">
                            <label>Gedung</label>
                            <select name="building_id" id="building-select" class="select-2 form-select">
                                <option value="">Pilih Gedung</option>
                                @foreach ($buildings as $value)
                                    <option value="{{ $value->id }}" {{ isset($editItem) && $editItem->building_id == $value->id ? 'selected' : '' }}>
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Ruang</label>
                            <select name="room_id" id="room-select" class="select-2 form-select">
                                <option value="">Pilih Ruang</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Pengasuh</label>
                            <select name="empl_id" id="empl-select" class="select-2 form-select">
                                <option value="">Pilih Pengasuh</option>
                                @foreach ($empBoarding as $value)
                                    <option value="{{ $value->id }}" {{ isset($editItem) && $editItem->empl_id == $value->id ? 'selected' : '' }}>
                                        {{ $value->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Siswa</label>
                            <select name="student_id" class="select-2 form-select">
                                <option value="">Pilih Siswa</option>
                                @foreach ($students as $value)
                                    <option value="{{ $value->id }}" {{ !empty($editItem) && is_object($editItem) && $editItem->student_id == $value->id ? 'selected' : '' }}>
                                        {{ $value->user->profile->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <button class="btn btn-primary">{{ isset($editItem) ? 'Update' : 'Simpan' }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('boarding::facility.student.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan Asrama Siswa yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const buildingId = $('#selected-building-id').val();
                const selectedRoomId = $('#selected-room-id').val();

                $('#building-select').on('change', function() {
                    let buildingId = $(this).val();

                    $('#room-select').empty().append('<option value="">Loading...</option>');

                    if (buildingId) {
                        let url = `{{ route('boarding::building-rooms', ['building_id' => 'BUILDING_ID_PLACEHOLDER']) }}`;
                        url = url.replace('BUILDING_ID_PLACEHOLDER', buildingId);

                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function(data) {
                                $('#room-select').empty().append('<option value="">Pilih Ruang</option>');

                                $.each(data, function(key, value) {
                                    const selected = value.id == selectedRoomId ? 'selected' : '';
                                    $('#room-select').append(`<option value="${value.id}" ${selected}>${value.name}</option>`);
                                });

                                $('#room-select').val(selectedRoomId).trigger('change');
                            }
                        });
                    } else {
                        $('#room-select').empty().append('<option value="">Pilih Ruang</option>');
                    }
                });

                if (buildingId) {
                    $('#building-select').val(buildingId).trigger('change');
                }
            });
        </script>
    @endpush
@endsection
