@extends('administration::layouts.default')

@section('title', 'Gedung - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Tagihan</li>
    <li class="breadcrumb-item active">Referensi</li>
@endsection

@section('content')
    <div class="row align-items-stretch">
        <div class="col-xl-12">
            @if (session('success'))
                <div id="flash-success" class="alert alert-success mt-4">
                    {!! session('success') !!}
                </div>
            @endif

            @if (request('trash'))
                <div class="alert alert-warning text-danger mb-0 mt-3">
                    <i class="mdi mdi-alert-circle-outline"></i> Menampilkan data yang dihapus
                </div>
            @endif
        </div>
       <div class="col-xl-6 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex">
                    <!-- Logo di kiri -->
                    <div class="me-3 d-flex align-items-center justify-content-center" 
                         style="width:60px; height:60px; border-radius:12px; background:#f5f6fa;">
                        <i class="mdi mdi-account-group text-primary" style="font-size:32px;"></i>
                    </div>

                    <!-- Konten di kanan -->
                    <div class="d-flex flex-column w-100">
                        <h5 class="fs-17 mb-2">
                            <a href="javascript:void(0);" class="text-dark mb-3">
                                Semua pembayaran akan didistribusikan bagi semua murid 
                                
                            </a>
                            <p>
                                <small class="text-muted fw-normal">
                                    Jenjang 
                                    {{ auth()->user()->employee->education->name }}
                               
                                </small>
                            </p>
                        </h5>

                        <div class="mt-auto hstack gap-2">
                            {{-- <a href="#!" data-bs-toggle="modal" class="btn btn-soft-success w-100">Lihat Murid</a> --}}
                            @if(count($semesterStudent) > 0)
                                <a href="#applyAll" data-bs-toggle="modal" class="btn btn-soft-primary w-100">Kelola</a>
                            @else
                                <p class="text-danger">Belum ada siswa di semester ini</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <!-- Logo di kiri -->
                   <div class="me-3 d-flex align-items-start justify-content-center" 
                        style="width:60px; height:60px; border-radius:12px; background:#f5f6fa;">
                        <i class="mdi mdi-google-classroom text-success" style="font-size:32px;"></i>
                    </div>

                    <!-- Konten di kanan -->
                    <div class="d-flex flex-column w-100">
                        <h5 class="fs-17 mb-2">
                            <a href="javascript:void(0);" class="text-dark mb-3">Pembayaran Per Kelas</a>
                            <p>
                                <small class="text-muted fw-normal">
                                    
                                    Jenjang {{ auth()->user()->employee->education->name }}
                                    {{-- 0 Jumlah Kelas didistribusikan pembayaran --}}
                                </small>
                            </p>
                        </h5>

                        <div class="mt-auto hstack gap-2">
                            {{-- <a href="#!" data-bs-toggle="modal" class="btn btn-soft-success w-100">Lihat Kelas</a> --}}
                            @if(count($semesterStudent) > 0)
                                <a href="{{ !empty($semesterStudent) ? '#applyClass' : 'javascript:void(0);' }}" data-bs-toggle="modal" class="btn btn-soft-primary w-100">Kelola</a>
                            @else
                                <p class="text-danger">Belum ada siswa di semester ini</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div class="col-xl-6 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex">
                   <div class="me-3 d-flex align-items-start justify-content-center" 
                        style="width:60px; height:60px; border-radius:12px; background:#f5f6fa;">
                        <i class="mdi mdi mdi-account-outline text-danger" style="font-size:32px;"></i>
                    </div>


                    <div class="d-flex flex-column w-100">
                        <h5 class="fs-17 mb-2">
                            <a href="javascript:void(0);" class="text-dark mb-3">Pembayaran Extra Per Murid</a>
                            <p>
                                <small class="text-muted fw-normal">
                                    0 Jumlah komponen extra dipasang 
                                </small>
                            </p>
                        </h5>

                        <div class="mt-auto hstack gap-2">

                            <a href="#applyStudent" data-bs-toggle="modal" class="btn btn-soft-primary w-100">Kelola</a>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Data Referensi Pembayaran</div>
                <div class="card-body">
                    <form action="{{ route('administration::bill.students.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::bill.students.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>

                    @if (session('success'))
                        <div id="flash-success" class="alert alert-success mt-4">
                            {!! session('success') !!}
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
                            <th>Semester</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$student->semester->student->user->name}}</td>
                                    <td>{{ $student->semester->semester->name }}</td>
                                    <td>
                                        @if ($student->trashed())
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.students.restore', ['student' => $student->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.students.kill', ['student' => $student->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Permanen"><i class="mdi mdi-delete-outline"></i></button>
                                            </form>
                                        @else
                                            <a class="btn btn-info btn-sm" data-toggle="tooltip" title="Kelola Komponen Pembayaran" href="{{ route('administration::bill.students.edit', ['student' => $student->id]) }}"><i class="mdi mdi-eye"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.students.destroy', ['student' => $student->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    <p style="margin-top: 10px; margin-left: 10px;">Jumlah Siswa : {{ $studentCount }}</p>
                </div>
                <div class="card-body">
                    {{ $students->appends(request()->all())->links() }}
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-md-4">

            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::bill.students.create') }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah Pembayaran Siswa</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::facility.buildings.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan Gedung yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="modal fade" id="applyAll" tabindex="-1" aria-labelledby="applyJobsLabel" aria-modal="false" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyJobsLabel">Pembayaran Keseluruhan Murid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAll" method="POST" action="{{ route('administration::bill.students.store') }}">
                        @csrf
                        <input type="hidden" name="status" value="1" />
                        <input type="hidden" name="education" value="{{ request()->education }}" />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Masukkan Semester</label>
                                    <select id="semester_id" name="semester_id" class="form-select" required>
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Masukkan Gelombang</label>
                                    <select id="batch_id" class="form-select" name="batch_id" required>
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Masukkan Paket Pembayaran</label>
                                    <select id="reference_id" class="form-select" name="package" required>
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button class="btn btn-success">Proses <i class="bx bx-send align-middle"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="applyClass" tabindex="-1" aria-labelledby="applyJobsLabel" aria-modal="false" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyJobsLabel">Pembayaran Per Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formApply" method="POST" action="{{ route('administration::bill.students.store') }}">
                        @csrf
                        <input type="hidden" name="status" value="2" />
                        <input type="hidden" name="education" value="{{ request()->education }}" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Kelas</label>
                                    <select id="class_id" name="class_id" class="form-select" required>
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Rombel</label>
                                    <select id="classroom_id" name="classroom_id" class="form-select" required>
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Masukkan Semester</label>
                                    <select id="semester_id" name="semester_id" class="form-select" required>
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Masukkan Gelombang</label>
                                    <select id="batch_id" class="form-select" name="batch_id" required>
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Masukkan Paket Pembayaran</label>
                                    <select id="reference_id" class="form-select" name="package" required>
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button class="btn btn-success">Proses <i class="bx bx-send align-middle"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    function initSelects($modal) {
        let modalId = $modal.id;

        let gradeSelect = $modal.querySelector('#class_id');
        let classSelect = $modal.querySelector('#classroom_id');
        let semesterSelect = $modal.querySelector("#semester_id");
        let batchSelect    = $modal.querySelector("#batch_id");
        let referenceSelect= $modal.querySelector("#reference_id");

        if (!semesterSelect || !batchSelect || !referenceSelect) return;

        if(modalId === 'applyClass') {
            if(gradeSelect){
                gradeSelect.innerHTML = '<option value="">Pilih</option>';
            }

            if(classSelect){
                classSelect.innerHTML = '<option value="">Pilih</option>';
            }

            fetch("{{ route('api::administration.grade_class') }}")
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        let opt = new Option(item.name, item.id);
                        gradeSelect.add(opt);
                    });
                });


            gradeSelect.addEventListener("change", function() {
                let gradeId = this.value;
        
                if (!gradeId) return;

                fetch(`{{ route('api::administration.classrooms') }}?class_id=${gradeId}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            let opt = new Option(item.name, item.id);
                            classSelect.add(opt);
                        });
                    });
            });
        }

        semesterSelect.innerHTML  = '<option value="">Pilih</option>';
        batchSelect.innerHTML     = '<option value="">Pilih</option>';
        referenceSelect.innerHTML = '<option value="">Pilih</option>';

        fetch("{{ route('api::administration.semesters') }}")
            .then(res => res.json())
            .then(data => {
                data.forEach(item => {
                    let opt = new Option(item.name, item.id);
                    semesterSelect.add(opt);
                });
            });

        semesterSelect.addEventListener("change", function() {
            let semesterId = this.value;
            batchSelect.innerHTML = '<option value="">Pilih</option>'; 
            referenceSelect.innerHTML = '<option value="">Pilih</option>'; 

            if (!semesterId) return;

            fetch(`{{ route('api::administration.batches') }}?semester_id=${semesterId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        let opt = new Option(item.name, item.id);
                        batchSelect.add(opt);
                    });
                });
        });

        // Event: pilih batch -> ambil reference
        batchSelect.addEventListener("change", function() {
            let batchId = this.value;
            referenceSelect.innerHTML = '<option value="">Pilih</option>';

            if (!batchId) return;

            fetch(`{{ route('api::administration.references_category') }}?batch_id=${batchId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        let opt = new Option(item.payment_category_label, item.payment_category);
                        referenceSelect.add(opt);
                    });
                });
        });
    }

    // Saat modal dibuka, panggil ulang initSelects
    ['applyAll', 'applyClass'].forEach(modalId => {
        let modalEl = document.getElementById(modalId);
        modalEl.addEventListener('shown.bs.modal', function() {
            initSelects(modalEl);
        });
    });

    // ['formAll', 'formClass'].forEach(formId => {
    //     let form = document.getElementById(formId);
    //     if (!form) return;

    //     form.addEventListener('submit', function(e) {
    //         e.preventDefault();

    //         let formData = new FormData(form);
    //         let modalEl = form.closest('.modal');
    //         let modalId = modalEl.id;

    //         // Ambil status
    //         let status = formData.get('status');
    //         let education = formData.get('education')
    //         let payload = { status: status, education: education };

    //         if(modalId === 'applyClass') {
    //             payload.class_id = formData.get('classroom_id');
    //             payload.semester_id = formData.get('semester_id');
    //             payload.batch_id = formData.get('batch_id');
    //             payload.package_id = formData.get('package');
    //         } else if(modalId === 'applyAll') {
    //             payload.semester_id = formData.get('semester_id');
    //             payload.batch_id = formData.get('batch_id');
    //             payload.package_id = formData.get('package');
    //         }

    //         fetch("{{ route('administration::bill.students.store') }}", {
    //             method: "POST",
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //             },
    //             body: new URLSearchParams(payload)
    //         })
    //         .then(res => res.json())
    //         .then(data => {
    //             console.log('Response:', data);
    //             // tampilkan notifikasi sukses
    //         })
    //         .catch(err => console.error(err));
    //     });
    // });

});
</script>

@endpush