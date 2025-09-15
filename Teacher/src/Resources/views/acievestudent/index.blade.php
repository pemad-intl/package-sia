@extends('teacher::layouts.default')

@section('title', 'Daftar prestasi - ' . $cls->name )

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card bg-white mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-badge-horizontal-outline position-absolute" style="top: 10pt; right: 40pt; font-size: 40pt;"></i>
                    <h2><strong>Rombel - {{ $cls->name }}</strong></h3>
                </div>
            </div>
            <h2>
                {{-- <a class="text-decoration-none small text-primary" href="{{ request('next', route('teacher::meet', ['meet' => $meet->id])) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a> --}}
                <i class="mdi mdi-arrow-left-circle-outline"></i> Prestasi Siswa
            </h2>


            <hr>

            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                        <div id="flash-success" class="alert alert-success mt-4">
                            {!! session('success') !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-account-badge-horizontal-outline me-1"></i> Siswa
                    </div>

                    <div>
                        <button type="button" class="btn btn-primary btn-rounded" id="addProject-btn"
                            data-bs-toggle="modal" data-bs-target="#prestasiModal">
                            <i class="mdi mdi-plus me-1"></i> Tambah Prestasi
                        </button>
                    </div>
                </div>


                {{-- <form class="form-block form-confirm" action="{{ route('teacher::supervisor', ['meet' => $meet->id, 'next' => url()->current()]) }}" method="POST"> @csrf @method('PUT') --}}
                    <div class="table-responsive">
                        <table class="table-bordered table-striped table-hover mb-0 table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Prestasi</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($achievementStudent as $achieve)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $achieve->name }}</td>
                                        <td>{{ date('Y-m-d', strtotime($achieve->date)) }}</td>
                                        <td><button 
                                                type="button" 
                                                class="btn btn-info"
                                                title="Edit Prestasi"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editPrestasiModal"
                                                data-id="{{ $achieve->id }}"
                                                data-name="{{ $achieve->name }}"
                                                data-date="{{ \Carbon\Carbon::parse($achieve->date)->format('Y-m-d') }}"
                                                data-action="{{ route('teacher::achievement.update', ['achievement' => $achieve->id]) }}"
                                            >
                                                <i class="bx bxs-edit-alt"></i>
                                            </button>
                                            <form action="{{ route('teacher::achievement.destroy', ['achievement' => $achieve->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')" style="display:inline;">
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
                                        <td class="text-center" colspan="4">Tidak ada data prestasi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                {{-- </form> --}}
            </div>
        </div>
        <div class="col-md-4">
            @include('teacher::includes.classroom-info', ['classroom' => $cls])
            {{-- @include('teacher::includes.subject-info', ['subject' => $meet->subject]) --}}
        </div>
    </div>
@endsection

<div class="modal fade" id="prestasiModal" tabindex="-1" aria-labelledby="prestasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="prestasiModalLabel">Tambah Prestasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      
    <form method="POST" action="{{ route('teacher::achievement.store', ['classroom' => $cls->id, 'student' => $student]) }}">
        @csrf
        <div class="modal-body">
            <!-- Isi form atau konten modal di sini -->
            <div class="mb-3">
                <label for="namaPrestasi" class="form-label">Nama Prestasi</label>
                <input type="text" name="name" class="form-control" id="name">
            </div>

            <div class="mb-3">
                <label for="namaPrestasi" class="form-label">Tanggal Prestasi</label>
                <input type="date" name="date" class="form-control" id="date">
            </div>
            <!-- Tambah input lainnya sesuai kebutuhan -->
            
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form> 
    
    </div>
  </div>
</div>


<div class="modal fade" id="editPrestasiModal" tabindex="-1" aria-labelledby="editPrestasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editPrestasiForm">
      @csrf
      @method('PUT') {{-- Ubah ke PUT karena update --}}
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPrestasiModalLabel">Edit Prestasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editId">
          <div class="mb-3">
            <label for="editName" class="form-label">Nama Prestasi</label>
            <input type="text" class="form-control" id="editName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="editDate" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="editDate" name="date" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editPrestasiModal');

    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const date = button.getAttribute('data-date');
        const action = button.getAttribute('data-action');

        document.getElementById('editName').value = name;
        document.getElementById('editDate').value = date;

        const form = document.getElementById('editPrestasiForm');
        form.action = action;
    });
});

</script>
