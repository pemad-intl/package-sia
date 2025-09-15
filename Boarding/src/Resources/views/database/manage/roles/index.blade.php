@extends('administration::layouts.default')

@section('title', 'Kelola peran - ')

@section('breadcrumb')
	<li class="breadcrumb-item">Kelola</li>
	<li class="breadcrumb-item active">Peran</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div class="card mb-4">
				<div class="card-header">
					<i class="mdi mdi-account-details mr-2 float-left"></i>Data peran
				</div>
				<div class="card-body">
					<form action="{{ route('administration::database.manage.roles.index') }}" method="GET">
						<input type="hidden" name="trash" value="{{ request('trash') }}">
						<div class="input-group">
							<input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
							<div class="input-group-append">
								<a class="btn btn-outline-secondary" href="{{ route('administration::database.manage.roles.index') }}"><i class="mdi mdi-refresh"></i></a>
								<button class="btn btn-primary">Cari</button>
							</div>
						</div>
					</form>
				</div>
				<div class="list-group list-group-flush border-top">
					@forelse($roles as $role)
						<div class="list-group-item">
							<div class="row">
								<div class="col-10">
									<h5>{{ $role->display_name }} <small class="text-muted">{{ $role->name }}</small></h5>
									<p>
										@forelse($role->permissions->take(8) as $permission)
											<span class="badge badge-dark">{{ $permission->name }}</span>
										@empty
											<span class="text-muted font-italic">Tidak ada hak akses yang diberikan</span>
										@endforelse
										@if($role->permissions->count() > 8)
											<span class="badge badge-secondary">+{{ $role->permissions->count() - 8 }} lainnya</span>
										@endif
									</p>
								</div>
								<div class="col-2">
									<h1 class="text-right mb-0"><i class="mdi mdi-account-badge" data-toggle="tooltip" title="{{ $role->users_count }} pengguna"></i></h1>
								</div>
							</div>
							@can('update', $role)
								<a class="btn btn-sm btn-primary" href="{{ route('administration::database.manage.roles.show', ['role' => $role->id]) }}">Lihat detail</a>
							@endcan
							@can('delete', $role)
								<form class="d-inline form-block form-confirm" action="{{ route('administration::database.manage.roles.destroy', ['role' => $role->id]) }}" method="POST"> @csrf @method('DELETE')
									<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
								</form>
							@endcan
						</div>
					@empty
						<div class="list-group-item text-muted">Tidak ada data</div>
					@endforelse
				</div>
				@if($roles->hasPages())
					<div class="card-body">
						{{ $roles->links() }}
					</div>
				@endif
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-body">
					<div class="h1 text-muted text-right mb-4">
						<i class="mdi mdi-account-box-multiple-outline float-right"></i>
					</div>
					<div class="text-value">{{ $roles_count }}</div>
					<small class="text-muted text-uppercase font-weight-bold">Jumlah peran</small>
				</div>
			</div>
			@can('store', Role::class)
				<div class="card">
					<div class="card-header">
						<i class="mdi mdi-account-plus mr-2 float-left"></i>Tambah peran
					</div>
					<div class="card-body">
						<form class="form-block" action="{{ route('administration::database.manage.roles.store') }}" method="POST"> @csrf
							<div class="form-group">
								<label>Kode</label>
								<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off">
								@error('name')
									<small class="text-danger"> {{ $message }} </small>
								@enderror
							</div>
							<div class="form-group">
								<label>Nama peran</label>
								<input type="text" class="form-control @error('display_name') is-invalid @enderror" name="display_name" value="{{ old('display_name') }}" required autocomplete="off">
								@error('display_name')
									<small class="text-danger"> {{ $message }} </small>
								@enderror
							</div>
							<div class="form-group mb-0">
								<button class="btn btn-primary">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			@endcan
		</div>
	</div>
@endsection