@extends('administration::layouts.default')

@section('title', 'Kategori mapel - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kurikulum</li>
    <li class="breadcrumb-item active">Kategori mapel</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-details float-left mr-2"></i>Kategori mapel
                </div>
                <div class="card-body">
                    <form action="{{ route('administration::curriculas.subject-categories.index', ['academic' => request('academic')]) }}" method="GET">
                        <input type="hidden" name="trash" value="{{ request('trash') }}">
                        <div class="input-group">
                            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama kategori mapel disini ...">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('administration::curriculas.subject-categories.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-refresh"></i></a>
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
                            <tr>
                                <th>No</th>
                                <th>Nama kategori</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration + ($categories->firstItem() - 1) }}</td>
                                    <td nowrap>{{ $category->name }}</td>
                                    <td nowrap class="py-2 text-right align-middle">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-action="{{ route('administration::curriculas.subject-categories.update', ['subject_category' => $category->id]) }}"><i class="mdi mdi-pencil"></i></button>
                                        <form class="d-inline form-block form-confirm" action="{{ route('administration::curriculas.subject-categories.destroy', ['subject_category' => $category->id]) }}" method="POST"> @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Buang"><i class="mdi mdi-delete-outline"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center"><i>Tidak ada data</i></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body border-bottom">
                    {{ $categories->appends(request()->all())->links() }}
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::curriculas.subject-categories.store') }}" method="POST"> @csrf
                        <div class="form-group mb-2">
                            <label>Tambah kategori mapel</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="name" value="{{ request('name') }}" placeholder="Nama kategori ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-account-box-multiple-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $categories_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Jumlah kategori mapel</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::curriculas.subjects.index') }}"><i class="mdi mdi-book-outline"></i> Kelola mapel</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form class="modal-content form-block" id="modal-edit-form" method="POST"> @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Ubah kategori</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Nama kategori</label>
                        <input id="modal-edit-input-name" class="form-control" type="text" name="name" value="" placeholder="Nama kategori ...">
                    </div>
                    <div class="form-group mb-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection


@push('scripts')
    <script>
        const exampleModal = document.getElementById('exampleModal');

        exampleModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Tombol yang diklik
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const action = button.getAttribute('data-action')
            // Set nilai input di dalam modal
            const nameInput = exampleModal.querySelector('#modal-edit-input-name');
            nameInput.value = name;

            // (Opsional) Set action form jika butuh
            const form = exampleModal.querySelector('#modal-edit-form');
            form.action = action; // Ganti dengan route yang sesuai
        });
    </script>
@endpush
