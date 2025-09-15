@extends('boarding::layouts.default')

@section('title', 'Kegiatan - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kelola</li>
    <li class="breadcrumb-item active">Kegiatan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Data Referensi Kegiatan</div>
                <div class="card-body">
                    <form action="{{ route('boarding::event.event-reference.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('boarding::event.event-reference.index') }}"><i class="mdi mdi-refresh"></i></a>
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

                @php
                    use Modules\Boarding\Enums\BoardingEventTypeEnum;
                @endphp

                <div class="table-responsive">
                    <table class="table-hover border-bottom mb-0 table text-center">
                        <thead class="thead-dark">
                            <th>No</th>
                            <th>Nama Kagiatan</th>
                            <th>Tipe</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Akhir</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Peserta</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse($boardingEvent as $event)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $event->name }}</td>
                                    <td>
                                       {{ $event->type->label() }}
                                    </td>
                                    <td>{{ !empty($event->start_date) ? \Carbon\Carbon::parse($event->start_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '-' }}</td>
                                    <td>{{ !empty($event->end_date) ? \Carbon\Carbon::parse($event->end_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($event->in)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($event->out)->format('H:i') }}</td>
                                    <td>{{ !empty($event->type_participant) && $event->type_participant == 1 ? 'Per Siswa' : 'Rombel' }}</td>
                                    <td nowrap>
                                        @if ($event->trashed())
                                            {{-- <form class="d-inline form-block form-confirm" action="{{ route('boarding::facility.buildings.restore', ['building' => $event->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('boarding::facility.buildings.kill', ['building' => $event->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Permanen"><i class="mdi mdi-delete-outline"></i></button>
                                            </form> --}}
                                        @else
                                            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah Referensi Kegiatan" href="{{ route('boarding::event.event-reference.edit', ['event_reference' => $event->id]) }}"><i class="mdi mdi-pencil"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('boarding::event.event-reference.destroy', ['event_reference' => $event->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    <p style="margin-top: 10px; margin-left: 10px;">Jumlah Kegiatan terdaftar : {{ $boardingEvent->total() }}</p>
                </div>
                <div class="card-body">
                    {{ $boardingEvent->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Kelola Refrensi Event</div>
                <div class="card-body">
                    <form class="form-block" action="{{ isset($editItem) ? route('boarding::event.event-reference.update', ['event_reference' => $editItem->id, 'next' => request()->fullUrl()]) : route('boarding::event.event-reference.store', ['next' => request()->fullUrl()]) }}" method="POST">
                        @csrf
                        @if (isset($editItem))
                            @method('PUT')
                        @endif

                        <div class="form-group mb-3">
                            <label>Nama Kegiatan</label>
                            <input type="text" class="form-control" name="name" value="{{ isset($editItem) ? $editItem->name : '' }}"  required />
                        </div>

                        <div class="form-group mb-3">
                            <label>Tipe Kegiatan</label>
                            <select name="type" class="form-select" id="type-select" required> 
                                @foreach (BoardingEventTypeEnum::cases() as $type)
                                    <option {{ isset($editItem) && $editItem->type->value == $type->value ? 'selected' : ''  }} 
                                        value="{{ $type->value }}">
                                        {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="date-input-container" style="display: none;">
                            <div class="form-group mb-3">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start-date" class="form-control" 
                                    value="{{ old('start_date', isset($editItem) ? $editItem->start_date : '') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end-date" class="form-control" 
                                    value="{{ old('end_date', isset($editItem) ? $editItem->end_date : '') }}">
                            </div>
                        </div>


                        <div class="form-group mb-3">
                            <label>Jam Mulai Kegiatan</label>
                            <input type="time" class="form-control" name="in" 
                                value="{{ isset($editItem) ? \Carbon\Carbon::parse($editItem->in)->format('H:i') : '' }}"  required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Jam Selesai Kegiatan</label>
                            <input type="time" class="form-control" name="out" 
                                value="{{ isset($editItem) ? \Carbon\Carbon::parse($editItem->out)->format('H:i') : '' }}"  required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Peserta Kegiatan</label>
                            <select class="form-control" name="type_participant" required>
                                <option value="">Pilih Peserta Kegiatan</option>
                                <option value="1" {{ isset($editItem) && $editItem->type_participant == 1 ? 'selected' : '' }}>Per Siswa</option>
                                <option value="2" {{ isset($editItem) && $editItem->type_participant == 2 ? 'selected' : '' }}>Rombel</option>
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
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('boarding::event.event-reference.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan Asrama Siswa yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectType = document.getElementById('type-select');
    const dateContainer = document.getElementById('date-input-container');
    const startDate = document.getElementById('start-date');
    const endDate = document.getElementById('end-date');

    function toggleDateInputs() {
        if (selectType.value == 2) {
            dateContainer.style.display = 'block';
            startDate.setAttribute('required', 'required');
            endDate.setAttribute('required', 'required');
        } else {
            dateContainer.style.display = 'none';
            startDate.removeAttribute('required');
            endDate.removeAttribute('required');
            startDate.value = ''; 
            endDate.value = '';
        }
    }

    toggleDateInputs(); 

    selectType.addEventListener('change', toggleDateInputs);
});
</script>
