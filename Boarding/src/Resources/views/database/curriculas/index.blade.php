@extends('administration::layouts.default')

@section('title', 'Kurikulum - ')

@section('breadcrumb')
	<li class="breadcrumb-item">Akademik</li>
	<li class="breadcrumb-item active">Kurikulum</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div class="card mb-4">
				<div class="card-header">
					<i class="mdi mdi-account-details mr-2 float-left"></i>Kurikulum
				</div>
				<div class="card-body">
					<form action="{{ route('administration::database.curriculas.index') }}" method="GET">
						<input type="hidden" name="trash" value="{{ request('trash') }}">
						<div class="input-group">
							<input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
							<div class="input-group-append">
								<a class="btn btn-outline-secondary" href="{{ route('administration::database.curriculas.index') }}"><i class="mdi mdi-refresh"></i></a>
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
							<tr>
								<th>No</th>
								<th nowrap>Kode</th>
								<th nowrap>Nama</th>
								<th>Tahun</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@forelse($curriculas as $curricula)
								<tr @if($curricula->trashed()) class="text-muted bg-light" @endif>
									<td>{{ $loop->iteration + ($curriculas->firstItem() - 1) }}</td>
									<td nowrap>{{ $curricula->kd }}</td>
									<td nowrap>{{ $curricula->name }}</td>
									<td nowrap>{{ $curricula->year }}</td>
									<td nowrap class="py-2 align-middle text-right">
										@if($curricula->trashed())
											<form class="d-inline form-block form-confirm" action="{{ route('administration::database.curriculas.restore', ['curricula' => $curricula->id]) }}" method="POST"> @csrf @method('PUT')
												<button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
											</form>
											<form class="d-inline form-block form-confirm" action="{{ route('administration::database.curriculas.kill', ['curricula' => $curricula->id]) }}" method="POST"> @csrf @method('DELETE')
												<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
											</form>
										@else
											<a class="btn btn-primary btn-sm disabled" data-toggle="tooltip" title="Lihat detail" href="{{ route('administration::database.curriculas.show', ['curricula' => $curricula->id]) }}"><i class="mdi mdi-eye"></i></a>
											@if(!$curricula->semesters_count)
												<form class="d-inline form-block form-confirm" action="{{ route('administration::database.curriculas.destroy', ['curricula' => $curricula->id]) }}" method="POST"> @csrf @method('DELETE')
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
				<div class="card-body">
					{{ $curriculas->appends(request()->all())->links() }}
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-body">
					<div class="h1 text-muted text-right mb-4">
						<i class="mdi mdi-account-box-multiple-outline float-right"></i>
					</div>
					<div class="text-value">{{ $curriculas_count }}</div>
					<small class="text-muted text-uppercase font-weight-bold">Jumlah tahun akademik</small>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<i class="mdi mdi-account-plus mr-2 float-left"></i>Tambah tahun akademik
				</div>
				<div class="card-body">
					<form class="form-block" action="{{ route('administration::database.curriculas.store') }}" method="POST"> @csrf
						<div class="form-group">
							<label>Nama kurikulum</label>
							<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off">
							@error('name')
								<small class="text-danger"> {{ $message }} </small>
							@enderror
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Kode kurikulum</label>
									<input type="text" class="form-control @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd') }}" required autocomplete="off">
									@error('kd')
										<small class="text-danger"> {{ $message }} </small>
									@enderror
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Tahun</label>
									<input type="number" class="form-control @error('year') is-invalid @enderror" name="year" value="{{ old('year') }}" required autocomplete="off">
									@error('year')
										<small class="text-danger"> {{ $message }} </small>
									@enderror
								</div>
							</div>
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
					<a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::database.curriculas.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan tahun akademik yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
				</div>
			</div>
		</div>
	</div>
@endsection