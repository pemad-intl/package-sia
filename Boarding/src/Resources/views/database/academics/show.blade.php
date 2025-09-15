@extends('administration::layouts.default')

@section('title', 'Tahun ajaran - ')

@section('breadcrumb')
	<li class="breadcrumb-item">Akademik</li>
	<li class="breadcrumb-item"><a href="{{ route('administration::database.academics.index') }}">Tahun ajaran</a></li>
	<li class="breadcrumb-item active">Lihat detail</li>
@endsection

@section('content')
	<h2 class="mb-4">
		<a class="text-decoration-none small" href="{{ request('next', route('administration::database.academics.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
		Lihat detail tahun akademik
	</h2>
	<div class="row">
		<div class="col-md-8">
			<div class="card mb-4">
				<div class="card-header">
					<i class="mdi mdi-school-outline mr-2 float-left"></i>Data semester
				</div>
				@if(request('trash'))
					<div class="card-body">
						<div class="alert alert-warning text-danger mb-0">
							<i class="mdi mdi-alert-circle-outline"></i> Menampilkan data yang dihapus
						</div>
					</div>
				@endif
				<div class="table-responsive">
					<table class="table table-hover mb-0">
						<thead class="thead-dark">
							<tr>
								<th>No</th>
								<th nowrap>Semester</th>
								<th>Status</th>
								<th>Jumlah rombel</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@forelse($semesters as $semester)
								<tr @if($semester->trashed()) class="text-muted bg-light" @endif>
									<td>{{ $loop->iteration }}</td>
									<td nowrap>{{ $semester->name }}</td>
									<td nowrap>
										@if($semester->open)
											<span class="badge badge-success badge-pill px-2">Aktif</span>
										@else
											<span class="badge badge-danger badge-pill px-2">Nonaktif</span>
										@endif
									</td>
									<td nowrap>{{ $semester->classrooms_count }} rombel</td>
									<td nowrap class="py-2 align-middle text-right">
										@if($semester->trashed())
											<form class="d-inline form-block form-confirm" action="{{ route('administration::database.academics.semesters.restore', ['academic' => $academic->id, 'semester' => $semester->id]) }}" method="POST"> @csrf @method('PUT')
												<button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
											</form>
											<form class="d-inline form-block form-confirm" action="{{ route('administration::database.academics.semesters.kill', ['academic' => $academic->id, 'semester' => $semester->id]) }}" method="POST"> @csrf @method('DELETE')
												<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
											</form>
										@else
											<form class="d-inline form-block form-confirm" action="{{ route('administration::database.academics.semesters.update', ['academic' => $academic->id, 'semester' => $semester->id]) }}" method="POST"> @csrf @method('PUT')
												@if($semester->open)
													<input type="hidden" name="open" value="0">
													<button class="btn btn-warning btn-sm" data-toggle="tooltip" title="Nonaktifkan"><i class="mdi mdi-close"></i></button>
												@else
													<input type="hidden" name="open" value="1">
													<button class="btn btn-success btn-sm" data-toggle="tooltip" title="Aktifkan"><i class="mdi mdi-check"></i></button>
												@endif
											</form>
											@if(!$semester->classrooms_count)
												<form class="d-inline form-block form-confirm" action="{{ route('administration::database.academics.semesters.destroy', ['academic' => $academic->id, 'semester' => $semester->id]) }}" method="POST"> @csrf @method('DELETE')
													<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Buang"><i class="mdi mdi-delete-outline"></i></button>
												</form>
											@else
												<span data-toggle="tooltip" title="Tidak dapat menghapus">
													<button class="btn btn-secondary btn-sm disabled"><i class="mdi mdi-delete-outline"></i></button>
												</span>
											@endif
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
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<i class="mdi mdi-school-outline mr-2 float-left"></i>Tambah semester
				</div>
				<div class="card-body">
					<form class="form-block form-confirm" action="{{ route('administration::database.academics.semesters.store', ['academic' => $academic->id]) }}" method="POST"> @csrf
						<div class="form-group">
							<label>Nama semester</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">{{ $academic->name }}</span>
								</div>
								<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off" @if(request('trash')) disabled @endif>
							</div>
							@error('name')
								<small class="text-danger"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="open" name="open" value="1" @if(old('open', 1) && !request('trash')) checked @endif @if(request('trash')) disabled @endif>
								<label class="custom-control-label" for="open"><span id="open-text">Aktifkan</span> semester ini</label>
							</div>
						</div>
						<button type="submit" class="btn btn-primary"  @if(request('trash')) disabled @endif>Tambah semester</button>
						<a class="btn btn-secondary" href="{{ route('administration::database.academics.index') }}">Kembali</a>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					<i class="mdi mdi-pencil mr-2 float-left"></i>Ubah tahun akademik
				</div>
				<div class="card-body">
					<form class="form-block" action="{{ route('administration::database.academics.update', ['academic' => $academic->id]) }}" method="POST"> @csrf @method('PUT')
						<div class="form-group">
							<label>Nama tahun</label>
							<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $academic->name) }}" required autocomplete="off">
							@error('name')
								<small class="text-danger"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group">
							<label>Tahun</label>
							<input type="text" class="form-control @error('year') is-invalid @enderror" name="year" value="{{ old('year', $academic->year) }}" required autocomplete="off">
							@error('year')
								<small class="text-danger"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group mb-0">
							<button class="btn btn-primary">Simpan</button>
						</div>
					</form>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<i class="mdi mdi-cogs mr-2 float-left"></i>Lanjutan
				</div>
				<div class="list-group list-group-flush">
					<a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::database.academics.show', ['academic' => $academic->id, 'trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan semester yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
				</div>
			</div>
		</div>
	</div>
@endsection


@push('script')
	<script>
		$('#open').on('change', (e) => {
		    $('#open-text').text($(e.target).is(':checked') ? 'Aktifkan' : 'Nonaktifkan')
		});
	</script>
@endpush