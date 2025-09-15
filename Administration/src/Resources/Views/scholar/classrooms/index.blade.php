@extends('administration::layouts.default')

@section('title', 'Rombel - ')

@section('breadcrumb')
	<li class="breadcrumb-item">Kesiswaan</li>
	<li class="breadcrumb-item active">Rombel</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div class="card mb-4">
				<div class="card-header">
					<i class="mdi mdi-account-details mr-2 float-left"></i>Rombel
				</div>
				<div class="card-body">
					<form action="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic')]) }}" method="GET">
						<input type="hidden" name="trash" value="{{ request('trash') }}">
						<div class="input-group">
							<input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama rombel disini ...">
							<div class="input-group-append">
								<a class="btn btn-outline-secondary" href="{{ route('administration::scholar.classrooms.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-refresh"></i></a>
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

				<div class="col-12 p-2">
					<div class="container">
						@if (Session::has('success'))
							<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
								<div class="alert alert-success">
									{!! Session::get('success') !!}
								</div>
							</div>
						@endif 

						@if (Session::has('danger'))
							<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
								<div class="alert-danger alert">
									{!! Session::get('danger') !!}
								</div>
							</div>
						@endif
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-hover mb-0 border-bottom">
						<thead class="thead-dark">
							<tr>
								<th>No</th>
								<th>Jenjang</th>
								<th>Nama rombel</th>
								<th>Ruang</th>
								<th>Jumlah siswa</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@forelse($classrooms as $classroom)
								<tr @if($classroom->trashed()) class="text-muted bg-light" @endif>
									<td class="align-middle">{{ $loop->iteration + ($classrooms->firstItem() - 1) }}</td>
									<td nowrap class="align-middle">{{ $classroom->level->kd }}</td>
									<td nowrap>
										<strong>{{ $classroom->name }} </strong> {{ $classroom->major->name.' '.$classroom->superior->name }} <br>
										<small class="text-muted">{{ $classroom->supervisor_id ? $classroom->supervisor->full_name : 'Tidak ada wali kelas' }}</small>
									</td>
									<td nowrap class="align-middle">{{ $classroom->room->name ?? '-' }}</td>
									<td nowrap class="align-middle">{{ $classroom->stsems_count }} siswa</td>
									<td nowrap class="py-2 align-middle text-right">
										@if($classroom->trashed())
											<form class="d-inline form-block form-confirm" action="{{ route('administration::scholar.classrooms.restore', ['classroom' => $classroom->id]) }}" method="POST"> @csrf @method('PUT')
												<button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
											</form>
											<form class="d-inline form-block form-confirm" action="{{ route('administration::scholar.classrooms.kill', ['classroom' => $classroom->id]) }}" method="POST"> @csrf @method('DELETE')
												<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
											</form>
										@else
											<a class="btn btn-primary btn-sm" data-toggle="tooltip" title="Detail rombel" href="{{ route('administration::scholar.classrooms.show', ['classroom' => $classroom->id]) }}"><i class="mdi mdi-eye"></i></a>
											<a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah rombel" href="{{ route('administration::scholar.classrooms.edit', ['classroom' => $classroom->id]) }}"><i class="mdi mdi-pencil"></i></a>
											<form class="d-inline form-block form-confirm" action="{{ route('administration::scholar.classrooms.destroy', ['classroom' => $classroom->id]) }}" method="POST"> @csrf @method('DELETE')
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
				</div>
				<div class="card-body">
					{{ $classrooms->appends(request()->all())->links() }}
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card card-body">
				<form class="form-block" action="{{ route('administration::scholar.classrooms.index') }}" method="GET">
					<div class="form-group mb-0">
						<label>Tahun ajaran</label>
						<div class="input-group w-100">
							<select name="academic" class="form-control">
								@foreach($acsems as $_acsem)
									<option value="{{ $_acsem->id }}" @if(request('academic', $acsem->id) == $_acsem->id) selected @endif>{{ $_acsem->full_name }}</option>
								@endforeach
							</select>
							<div class="input-group-append">
								<button class="btn btn-primary">Tetapkan</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="h1 text-muted text-right mb-4">
						<i class="mdi mdi-account-box-multiple-outline float-right"></i>
					</div>
					<div class="text-value">{{ $classrooms_count }}</div>
					<small class="text-muted text-uppercase font-weight-bold">Jumlah rombel</small>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<i class="mdi mdi-cogs mr-2 float-left"></i>Lanjutan
				</div>
				<div class="list-group list-group-flush">
					<a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.classrooms.create', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah rombel</a>
					<a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.majors.index', ['academic' => request("academic")]) }}"><i class="mdi mdi-folder-settings-variant-outline"></i> Kelola jurusan</a>
					<a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.superiors.index', ['academic' => request("academic")]) }}"><i class="mdi mdi-file-settings-variant-outline"></i> Kelola unggulan</a>
					<a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::scholar.classrooms.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan rombel yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
				</div>
			</div>
		</div>
	</div>
@endsection