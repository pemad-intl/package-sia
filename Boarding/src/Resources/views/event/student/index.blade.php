@extends('boarding::layouts.default')

@section('title', 'Gedung - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kegiatan</li>
    <li class="breadcrumb-item active">Siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Data Kegiatan Siswa</div>
                <div class="card-body">
                    <form action="{{ route('boarding::event.event-student.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('boarding::event.event-student.index') }}"><i class="mdi mdi-refresh"></i></a>
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
                            <th>Peserta</th>
                            <th>Guru Pengampu</th>
                            <th>Guru Pengasuh</th>
                            <th>Kegiatan</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse($boardingEventStdn as $event)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
        
                                    <td>
                                        @if ($event->modelable_type == 'Modules\Academic\Models\AcademicClassroom')
                                            {{ optional($event->modelable)->name ?? '-' }}
                                        @else
                                            {{ optional(optional($event->modelable)->user)->name ?? '-' }}
                                        @endif
                                    </td>
                                    <td>{{ $event->teacher->user->name ?? '-' }}</td>
                                    <td>{{ $event->supervisor->user->name ?? '-' }}</td>
                                    <td>
                                        {{ $event->event->name ?? 'Kegiatan dihapus' }}
                                        @if($event->event->type->value == 2)
                                            @if(!empty($event->event->start_date) && !empty($event->event->end_date))
                                               @if($event->event->end_date)
                                                    @php
                                                        $today = \Carbon\Carbon::today();
                                                        $endDate = \Carbon\Carbon::parse($event->event->end_date);
                                                    @endphp

                                                    @if ($today->greaterThan($endDate))
                                                        <p><span class="badge bg-danger">
                                                            <small>Selesai</small>
                                                        </span></p>
                                                    @else
                                                        <p>
                                                        <span class="badge bg-secondary">
                                                            <small>
                                                                {{ $endDate->format('d M Y') }}
                                                            </small>
                                                        </span>
                                                        </p>
                                                    @endif
                                                @endif
                                            @endif                                     
                                        @endif
                                    </td>
                                    <td>
                                        @if ($event->trashed())
                                            {{-- <form class="d-inline form-block form-confirm" action="{{ route('boarding::facility.buildings.restore', ['building' => $building->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('boarding::facility.buildings.kill', ['building' => $building->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Permanen"><i class="mdi mdi-delete-outline"></i></button>
                                            </form> --}}
                                        @else
                                            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah Event Siswa" href="{{ route('boarding::event.event-student.edit', ['event_student' => $event->id]) }}"><i class="mdi mdi-pencil"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('boarding::event.event-student.destroy', ['event_student' => $event->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    <p style="margin-top: 10px; margin-left: 10px;">Jumlah Murid terdaftar pada kegiatan : {{ $boardingEventStdn->total() }}</p>
                </div>
                <div class="card-body">
                    {{ $boardingEventStdn->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Kelola Kegiatan Siswa</div>
                <div class="card-body">
                    <form class="form-block" action="{{ isset($editItem) ? route('boarding::event.event-student.update', ['event_student' => $editItem->id, 'next' => request()->fullUrl()]) : route('boarding::event.event-student.store', ['next' => request()->fullUrl()]) }}" method="POST">
                        @csrf
                        @if (isset($editItem))
                            @method('PUT')
                        @endif

                        <div class="form-group mb-3">
                            <label>Kegiatan</label>
                            @php
                                use Modules\Boarding\Enums\BoardingEventTypeEnum;
                                $groupedEvents = $events->groupBy(fn($event) => $event->type->value);
                            @endphp

                            <select name="event_id" id="event-select" class="form-select select-2" required>
                                <option value="">Pilih Kegiatan</option>
                                @foreach ($groupedEvents as $typeValue => $group)
                                    @php $enumType = BoardingEventTypeEnum::tryFrom((int) $typeValue); @endphp
                                    @if ($enumType)
                                        <optgroup label="{{ $enumType->label() }}">
                                            @foreach ($group as $value)
                                                <option data-participant="{{ $value->type_participant }}" value="{{ $value->id }}"
                                                    {{ isset($editItem) && $editItem->event_id == $value->id ? 'selected' : '' }}>
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                            <input type="hidden" name="participant_type" id="participant-type-input" value="{{ old('participant_type', $editItem->event->type_participant ?? '') }}">

                        </div>

                        <div class="form-group mb-3" id="participant-student-container" style="display:none;">
                            <label>Siswa</label>
                            <select id="student-select" name="student_id" class="form-select select-2">
                                <option value="">Pilih Siswa</option>
                                @foreach ($students as $value)
                                    <option value="{{ $value->id }}" {{ !empty($editItem) && is_object($editItem) && $editItem->modelable_id == $value->id ? 'selected' : '' }}>
                                        {{ $value->user->profile->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3" id="participant-rombel-container" style="display:none;width: 100%;">
                            <label>Rombel</label>
                            <select id="participant-select" name="academic_id" class="form-select select-2">
                                <option value="">Pilih Rombel</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Guru Pengampu</label>
                            <select name="teacher_id" class="form-select select-2" required>
                                <option value="">Pilih Guru Pengampu</option>
                                @foreach ($employeeTeacher as $value)
                                    <option value="{{ $value->id }}" {{ !empty($editItem) && is_object($editItem) && $editItem->teacher_id == $value->id ? 'selected' : '' }}>
                                        {{ $value->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Pengasuh</label>
                            <select name="supervisor_id" class="form-select select-2" required>
                                <option value="">Pilih Pengasuh</option>
                                @foreach ($employeeSupervisor as $value)
                                    <option value="{{ $value->id }}" {{ !empty($editItem) && is_object($editItem) && $editItem->supervisor_id == $value->id ? 'selected' : '' }}>
                                        {{ $value->user->name }}
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
@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#event-select').select2({ width: '100%' });
    $('#student-select').select2({ width: '100%' });
    $('#participant-select').select2({ width: '100%' });

    const participantTypeInput = document.getElementById('participant-type-input');

    function updateParticipantTypeInput() {
        const eventSelect = document.getElementById('event-select');
        const selectedOption = eventSelect.options[eventSelect.selectedIndex];
        const participantType = selectedOption ? selectedOption.getAttribute('data-participant') : '';
        participantTypeInput.value = participantType;
    }

    function toggleParticipantFields() {
        const eventSelect = document.getElementById('event-select');
        const selectedOption = eventSelect.options[eventSelect.selectedIndex];
        const participantType = selectedOption ? selectedOption.getAttribute('data-participant') : null;

        const studentContainer = document.getElementById('participant-student-container');
        const rombelContainer = document.getElementById('participant-rombel-container');

        if (participantType === '2') {
            rombelContainer.style.display = 'block';
            fillRombelOptions();
            $('#participant-select').prop('required', true).select2({ width: '100%' });

            studentContainer.style.display = 'none';
            $('#student-select').prop('required', false).val(null).trigger('change');
        } else if (participantType === '1') {
            studentContainer.style.display = 'block';
            $('#student-select').prop('required', true).select2({ width: '100%' });

            rombelContainer.style.display = 'none';
            $('#participant-select').prop('required', false).val(null).trigger('change');
        } else {
            studentContainer.style.display = 'none';
            $('#student-select').prop('required', false).val(null).trigger('change');

            rombelContainer.style.display = 'none';
            $('#participant-select').prop('required', false).val(null).trigger('change');
        }
    }

   const acdmcClassData = @json($acdmcClass);
    const modelableType = @json(isset($editItem) ? $editItem->modelable_type : null);
    const selectedRombelId = @json(old('academic_id', isset($editItem) ? $editItem->modelable_id : null));

    function fillRombelOptions() {
        const rombelSelect = document.getElementById('participant-select');
        rombelSelect.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Pilih Rombel';
        rombelSelect.appendChild(defaultOption);

        // Kalau mode tambah data, atau modelable_type adalah AcademicClassroom
        if (!modelableType || modelableType === "Modules\\Academic\\Models\\AcademicClassroom") {
            acdmcClassData.forEach(function (rombel) {
                const option = document.createElement('option');
                option.value = rombel.id;
                option.textContent = rombel.name;

                if (selectedRombelId && rombel.id == selectedRombelId) {
                    option.selected = true;
                }

                rombelSelect.appendChild(option);
            });
        }

        $('#participant-select').trigger('change.select2');
    }


    $('#event-select').on('select2:select', function () {
        updateParticipantTypeInput();
        toggleParticipantFields();
    });

    // Jalankan saat load, pastikan input hidden ikut terupdate dan fields sesuai data edit
    updateParticipantTypeInput();
    toggleParticipantFields();
});


</script>
@endpush
