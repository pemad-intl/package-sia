@extends('administration::layouts.default')

@section('title', 'Dasbor - ')

@section('breadcrumb')
<li class="breadcrumb-item active">Dasbor</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div class="card mb-4">
				<div class="card-header"><i class="mdi mdi-office-building mr-2 float-left"></i>Data Asset</div>
				<div class="card-body">
					<form action="{{ route('administration::facility.assets.index') }}" method="GET">
						<input type="hidden" name="trash" value="{{ request('trash') }}">
						<div class="input-group">
							<input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
							<div class="input-group-append">
								<a class="btn btn-outline-secondary" href="{{ route('administration::facility.assets.index') }}"><i class="mdi mdi-refresh"></i></a>
								<button class="btn btn-primary">Cari</button>
							</div>
						</div>
					</form>
					@if(request('trash'))
						<div class="alert alert-warning text-danger mt-3 mb-0">
							<i class="mdi mdi-alert-circle-outline"></i> Menampilkan data yang dihapus
						</div>
					@endif
				</div>
				<div class="table-responsive">
					<table class="table table-hover mb-0 border-bottom">
						<thead class="thead-dark">
							<th>No</th>
							<th>Ruang</th>
							<th>Nama</th>
							<th>Jumlah</th>
							<th>Kondisi</th>
							<th></th>
						</thead>
						<tbody>
							@forelse($assets as $asset)
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td>{{ $asset->room['name'] }}</td>
									<td>{{ $asset->name }}</td>
									<td>{{ $asset->count }}</td>
									<td>{{ $asset->condition }}</td>
									<td>
										<a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah Gedung" href="{{ route('administration::facility.assets.show', ['asset' => $asset->id]) }}"><i class="mdi mdi-pencil"></i></a>
										<form class="d-inline form-block form-confirm" action="{{ route('administration::facility.assets.destroy', ['asset' => $asset->id]) }}" method="POST"> @csrf @method('DELETE')
											<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Buang"><i class="mdi mdi-delete-outline"></i></button>
										</form>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="6" class="text-center"><i>Tidak ada data</i></td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<div class="card-body">
					{{ $assets->appends(request()->all())->links() }}
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card mb-3">
				<div class="card-header"><i class="mdi mdi-office-building mr-2 float-left"></i>Tambah Asset</div>
				<div class="card-body">
					<form class="form-block" action="" method="POST"> @csrf
							<div class="form-group">
								<label>Ruang</label>
								<select class="form-control" name="room_id">
									<option>Pilih Ruangan</option>
									@foreach($rooms as $room)
									<option value="{{ $room->id }}">{{ $room->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label>Nama Aset</label>
								<input type="text" class="form-control" name="name" value="" required autocomplete="off">
							</div>
							<div class="form-group">
								<label>Kategori</label>
								<select class="form-control" name="ctg_id" id="ctg_id">
									<option>Pilih Kategori</option>
									@foreach($ctgs as $ctg)
									<option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
									@endforeach
									<option value="addCategory">Tambah Kategori</option>
								</select>
							</div>
							<div class="form-group">
								<label>Jumlah</label>
								<input type="text" class="form-control" name="count" value="" required autocomplete="off">
							</div>
							<div class="form-group">
								<label>Kondisi</label>
								<input type="text" class="form-control" name="condition" value="" required autocomplete="off">
							</div>
							<div class="form-group mb-0">
								<button class="btn btn-primary">Simpan</button>
							</div>
						</form>
				</div>
			</div>
			<div class="card" id="addCategoryForm" style="display: none;">
				<div class="card-header">
					<i class="mdi mdi-cogs mr-2 float-left"></i>Kategori Aset
				</div>
				<div class="card-body">
					<form class="form-block" action="{{ route('administration::facility.categories.store') }}" method="POST"> @csrf
						<div class="form-group">
							<label>Nama Kategori</label>
							<input type="text" class="form-control" name="name" value="" required autocomplete="off">
						</div>
						<div class="form-group mb-0">
							<button class="btn btn-primary">Simpan</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('script')
	<script type="text/javascript">
		$('#ctg_id').on('change',function(){
                if($(this).val()=='addCategory')
                {
                    $('#addCategoryForm').show();
                    console.log('tampil');
                }
                else
                {
                    $('#addCategoryForm').val('').hide();
                    console.log('ilang');
                }
        });
	</script>
@endpush