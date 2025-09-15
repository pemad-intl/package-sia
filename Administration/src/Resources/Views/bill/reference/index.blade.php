@extends('administration::layouts.default')

@section('title', 'Gedung - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Tagihan</li>
    <li class="breadcrumb-item active">Referensi</li>
@endsection

{{-- @push('styles')
<style>
.category-slider {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.category-container {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 10px;
    width: 100%;
    padding: 5px 35px; /* ruang kanan kiri buat panah */
}
.category-container::-webkit-scrollbar {
    display: none; /* Chrome, Safari */
}
.category-container {
    -ms-overflow-style: none;  /* IE, Edge lama */
    scrollbar-width: none;     /* Firefox */
}

.shift-btn {
    min-width: 120px;     /* panjang minimum biar kotak persegi panjang */
    padding: 8px 20px;    /* atas-bawah 8px, kiri-kanan 20px */
    white-space: nowrap;  /* teks tidak turun ke bawah */
}

.arrow-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid #ddd;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
}
.arrow-left { left: 0; }
.arrow-right { right: 0; }
</style>

@endpush --}}

 {{-- <div class="category-slider">
    <!-- Tombol panah kiri -->
    <button class="arrow-btn arrow-left" onclick="scrollCategory(-200)">
        ‹
    </button>

    <!-- Container scroll -->
    <div id="categoryContainer" class="category-container">
        <button class="btn btn-outline-primary shift-btn">Gelombang 1</button>
        <button class="btn btn-outline-warning shift-btn">Gelombang 2</button>
        <button class="btn btn-outline-success shift-btn">Gelombang 3</button>
    </div>

    <!-- Tombol panah kanan -->
    <button class="arrow-btn arrow-right" onclick="scrollCategory(200)">
        ›
    </button>
</div> --}}
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Data Referensi Pembayaran</div>
                <div class="card-body">
                    <form action="{{route('administration::bill.references.index')}}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="d-block mb-2">Semester</label>
                                    <div>
                                        <select id="semester_id" name="semester_id" class="form-select">
                                            <option value="">Pilih</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="d-block mb-2">Gelombang</label>

                                    <div>
                                        <select id="batch_id" name="batch_id" class="form-select">
                                            <option value="">Pilih</option>
                                        </select>
                                    </div>
                                
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="d-block mb-2">Kelas</label>
                                    <select id="reference_id" name="class_id" class="form-select">
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <div>
                                        <button class="btn btn-primary">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- <form action="{{ route('administration::bill.references.index') }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::bill.references.index') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form> --}}

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
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Gelombang</th>
                            <th>Kategori Pembayaran</th>
                            <th>Siklus Pembayaran</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bill->kd }}</td>
                                    <td>{{ $bill->name }}</td>
                                    <td>{{ $bill->batch->name}}</td>
                                    <td>{{ $bill->payment_category->label() }}</td>
                                    <td>{{ $bill->payment_cycle->label() }}</td>
                                    <td>{{ 'Rp ' . number_format($bill->price, 0, ',', '.') }}</td>
                                    <td>{{ $bill->type->name }}</td>
                                    <td nowrap>
                                        @if ($bill->trashed())
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.references.restore', ['reference' => $bill->id]) }}" method="POST"> @csrf @method('PUT')
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.references.kill', ['reference' => $bill->id]) }}" method="POST"> @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Permanen"><i class="mdi mdi-delete-outline"></i></button>
                                            </form>
                                        @else
                                            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah Komponen Pembayaran" href="{{ route('administration::bill.references.index', ['edit' => $bill->id]) }}"><i class="mdi mdi-pencil"></i></a>
                                            <form class="d-inline form-block form-confirm" action="{{ route('administration::bill.references.destroy', ['reference' => $bill->id]) }}" method="POST"> @csrf @method('DELETE')
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
                    <p style="margin-top: 10px; margin-left: 10px;">Jumlah Gedung : {{ $billCount }}</p>
                </div>
                <div class="card-body">
                    {{ $bills->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><i class="mdi mdi-office-building float-left mr-2"></i>Kelola Referensi Pembayaran</div>
                <div class="card-body">
                    <form class="form-block" action="{{ isset($editBill) ? route('administration::bill.references.update', $editBill->id) : route('administration::bill.references.store') }}" method="POST">
                        @csrf

                        @if(isset($editBill))
                            @method('PUT')
                        @endif

                        <div class="form-group mb-3">
                            <label>Kode</label>
                            <input type="text" class="form-control" name="kd" 
                                value="{{ old('kd', $editBill->kd ?? '') }}" required autocomplete="off"
                                required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name" 
                                value="{{ old('name', $editBill->name ?? '') }}" required autocomplete="off" 
                                required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Gelombang</label>
                            <select name="batch_id" class="form-select" required>
                                <option value="">Pilih</option>
                                @foreach($academicBatch as $batch)
                                    <option value="{{ $batch->id }}"
                                         @selected(old('batch_id', $editBill->batch_id ?? null) == $batch->id)>
                                        {{$batch->semesters->academic->name }}
                                        {{$batch->semesters->name }} -
                                        {{$batch->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="form-group mb-3">
                            <label>Jenjang Pendidikan</label>
                            <select name="type_class" class="form-select" required>
                                <option value="">Pilih</option>
                                @foreach(\Modules\Core\Enums\StudentEducationEnum::cases() as $education)
                                    <option value="{{ $education->value }}" @selected(old('payment_category', $editBill->type_class->value ?? null) == $education->value)>
                                         {{ $education->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="form-group mb-3">
                            <label>Paket Pembayaran</label>
                            <select class="form-select" name="payment_category" required>
                                <option value="">Pilih</option>
                                @foreach(\Modules\Core\Enums\BillReferencesCategoryEnum::cases() as $package)
                                    <option value="{{ $package->value }}" @selected(old('payment_category', $editBill->payment_category->value ?? null) == $package->value)>
                                        
                                         {{ $package->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Siklus Pembayaran</label>
                            <select class="form-select" name="payment_cycle" required>
                            <option value="">Pilih</option>
                            @foreach(\Modules\Core\Enums\PaymentCycleEnum::cases() as $cycle)
                                <option value="{{ $cycle->value }}" @selected(old('payment_cycle', $editBill->payment_cycle->value ?? null) == $cycle->value)>
                                    {{ $cycle->label() }}
                                </option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Tipe</label>
                            <select class="form-select" name="type" required>
                                <option value="">Pilih</option>
                                @foreach(\Modules\Core\Enums\BillCategoryEnum::cases() as $case)
                                    <option value="{{ $case->value }}" 
                                            @selected(old('type', $editBill->type->value ?? null) == $case->value)>
                                        {{ $case->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="d-block mb-2">Harga</label>
                            <input type="number" class="form-control" name="price" 
                            value="{{ old('price', $editBill->price ?? '') }}" autocomplete="off"
                            required>
                        </div>
                        

                        <div class="form-group mb-0">
                            <button class="btn btn-primary">
                                {{ isset($editBill) ? 'Update' : 'Simpan' }}
                            </button>
                            @if(isset($editBill))
                                <a href="{{ route('administration::bill.references.index') }}" class="btn btn-secondary">Batal</a>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::facility.buildings.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan Referensi Pembayaran yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let semesterSelect = document.getElementById("semester_id");
        let batchSelect    = document.getElementById("batch_id");
        let referenceSelect= document.getElementById("reference_id");

        // Ambil daftar semester
        fetch("{{ route('api::administration.semesters') }}")
            .then(res => res.json())
            .then(data => {
                data.forEach(item => {
                    let opt = new Option(item.name, item.id);
                    semesterSelect.add(opt);
                });
            });

        // Event: pilih semester -> ambil batch berdasarkan semester
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

        // Event: pilih batch -> ambil reference berdasarkan batch
        batchSelect.addEventListener("change", function() {
        let batchId = this.value;
        referenceSelect.innerHTML = '<option value="">Pilih</option>'; // reset

        if (!batchId) return;

        fetch(`{{ route('api::administration.references') }}?batch_id=${batchId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(item => {
                    // pakai type_class sebagai value
                    let opt = new Option(item.type_class_label, item.type_class);
                    referenceSelect.add(opt);
                });
            });
     });

    });
</script>
@endpush