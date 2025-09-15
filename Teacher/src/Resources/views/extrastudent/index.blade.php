@extends('teacher::layouts.default')

@section('title', 'Daftar extrakulikuler rombel - ' . $classroom->name)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white border mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-badge-horizontal-outline position-absolute" style="top: 10pt; right: 40pt; font-size: 40pt;"></i>
                    <h2><strong>Extrakulikuler </strong></h3>
                        <p class="mb-0">Rombel {{ $classroom->name }}</p>
                </div>
            </div>
            <h2>
                {{-- <a class="text-decoration-none small text-{{ $meet->props->color ?? 'primary' }}" href="{{ request('next', route('teacher::meet', ['classroom' => $meet->id])) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a> --}}
                Extrakulikuler Siswa
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
                            data-bs-toggle="modal" data-bs-target="#extraModal">
                            <i class="mdi mdi-plus me-1"></i> Tambah Extrakulikuler
                        </button>
                    </div>
                </div>


                {{-- <form class="form-block form-confirm" action="{{ route('teacher::extras', ['classroom' => $classroom->id, 'next' => url()->current()]) }}" method="POST"> @csrf @method('PUT') --}}
                    <div class="table-responsive">
                        <table class="table-bordered table-striped table-hover mb-0 table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Extrakulikuler</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($extraStudent as $extra)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $extra->name }}</td>
                                        <td><button 
                                                type="button" 
                                                class="btn btn-info"
                                                title="Edit Prestasi"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editExtraModal"
                                                data-id="{{ $extra->id }}"
                                                data-name="{{ $extra->name }}"
                                                data-action="{{ route('teacher::extras.update', ['extra' => $extra->id]) }}"
                                            >
                                                <i class="bx bxs-edit-alt"></i>
                                            </button>
                                           <form action="{{ route('teacher::extras.destroy', ['extra' => $extra->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus extrakulikuler ini?')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Hapus Ekstrakurikuler">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="4">Tidak ada data extrakulikuler</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                {{-- </form> --}}
            </div>
        </div>
        {{-- <div class="col-md-4">
            {{-- @include('teacher::includes.classroom-info', ['classroom' => $meet->classroom])
            @include('teacher::includes.subject-info', ['subject' => $meet->subject]) --}}
        {{-- </div>  --}}
    </div>
@endsection

<div class="modal fade" id="extraModal" tabindex="-1" aria-labelledby="extraModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="prestasiModalLabel">Tambah Extrakulikuler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      
    <form method="POST" action="{{ route('teacher::extras.store', ['classroom' => $classroom->id, 'student' => $student]) }}">
        @csrf
        <div class="modal-body">
            <!-- Isi form atau konten modal di sini -->
            <div class="mb-3">
                <label for="namaPrestasi" class="form-label">Nama Extrakulikuler</label>
                <input type="text" name="name" class="form-control" id="name">
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


<div class="modal fade" id="editExtraModal" tabindex="-1" aria-labelledby="editExtraModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editExtraForm">
      @csrf
      @method('PUT') {{-- Ubah ke PUT karena update --}}
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editExtraModalLabel">Edit Extrakulikuler</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editId">
          <div class="mb-3">
            <label for="editName" class="form-label">Nama Extrakulikuler</label>
            <input type="text" class="form-control" id="editName" name="name" required>
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
    const editModal = document.getElementById('editExtraModal');

    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const action = button.getAttribute('data-action');

        document.getElementById('editName').value = name;

        const form = document.getElementById('editExtraForm');
        form.action = action;
    });
});

</script>
