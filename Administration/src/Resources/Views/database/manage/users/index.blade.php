@extends('administration::layouts.default')

@section('title', 'Kelola pengguna - ')

@section('breadcrumb')
	<li class="breadcrumb-item">Kelola</li>
	<li class="breadcrumb-item active">Pengguna</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div class="card mb-4">
				<div class="card-header">
					<i class="mdi mdi-account-details mr-2 float-left"></i>Data pengguna
				</div>
				<div class="card-body">
					<form action="{{ route('administration::database.manage.users.index') }}" method="GET">
						<input type="hidden" name="trash" value="{{ request('trash') }}">
						<div class="input-group">
							<input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
							<div class="input-group-append">
								<a class="btn btn-outline-secondary" href="{{ route('administration::database.manage.users.index') }}"><i class="mdi mdi-refresh"></i></a>
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
								<th>User ID</th>
								<th></th>
								<th nowrap>Nama lengkap</th>
								<th>Username</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@forelse($users as $user)
								<tr @if($user->trashed()) class="text-muted bg-light" @endif>
									<td>{{ $loop->iteration + ($users->firstItem() - 1) }}</td>
									<td nowrap>{{ $user->id }}</td>
									<td class="py-2" width="35">
										<img class="rounded-circle" src="{{ $user->profile->avatar_path }}" height="32" alt="">
									</td>
									<td nowrap>
										@if($user->trashed() || $user->is(auth()->user()) || auth()->user()->cannot('update', $user))
											{{ $user->profile->name }}
										@else
											<a href="{{ route('administration::database.manage.users.show', ['user' => $user->id]) }}">{{ $user->profile->name }}</a>
										@endif
									</td>
									<td nowrap>{{ $user->username }}</td>
									<td nowrap class="py-2 align-middle text-right">
										@if($user->isnot(auth()->user()))
											@if($user->trashed())
												@can('delete', $user)
													<form class="d-inline form-block form-confirm" action="{{ route('administration::database.manage.users.restore', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
														<button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
													</form>
													<form class="d-inline form-block form-confirm" action="{{ route('administration::database.manage.users.kill', ['user' => $user->id]) }}" method="POST"> @csrf @method('DELETE')
														<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
													</form>
												@endcan
											@else
												@can('update', $user)
													<form class="d-inline form-block form-confirm" action="{{ route('administration::database.manage.users.repass', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
														<button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Atur ulang sandi"><i class="mdi mdi-lock-open-variant"></i></button>
													</form>
												@endcan
												@can('update', $user)
													<a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah pengguna" href="{{ route('administration::database.manage.users.show', ['user' => $user->id]) }}"><i class="mdi mdi-pencil"></i></a>
												@endcan
												@can('remove', $user)
													<form class="d-inline form-block form-confirm" action="{{ route('administration::database.manage.users.destroy', ['user' => $user->id]) }}" method="POST"> @csrf @method('DELETE')
														<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Buang"><i class="mdi mdi-delete-outline"></i></button>
													</form>
												@endcan
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
					{{ $users->appends(request()->all())->links() }}
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-body">
					<div class="h1 text-muted text-right mb-4">
						<i class="mdi mdi-account-box-multiple-outline float-right"></i>
					</div>
					<div class="text-value">{{ $users_count }}</div>
					<small class="text-muted text-uppercase font-weight-bold">Jumlah pengguna</small>
				</div>
			</div>
			@can('store', User::class)
				<div class="card">
					<div class="card-header">
						<i class="mdi mdi-account-plus mr-2 float-left"></i>Tambah pengguna
					</div>
					<div class="card-body">
						<form class="form-block" action="{{ route('administration::database.manage.users.store') }}" method="POST"> @csrf
							<div class="form-group">
								<label>Nama lengkap</label>
								<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off">
								@error('name')
									<small class="text-danger"> {{ $message }} </small>
								@enderror
							</div>
							<div class="form-group">
								<label>Username</label>
								<input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="off">
								@error('username')
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
			<div class="card">
				<div class="card-header">
					<i class="mdi mdi-cogs mr-2 float-left"></i>Lanjutan
				</div>
				<div class="list-group list-group-flush">
					<a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::database.manage.users.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan pengguna yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
				</div>
			</div>
		</div>
	</div>
@endsection