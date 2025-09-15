@extends('administration::layouts.default')

@section('title', 'Kelola peran - ')

@section('breadcrumb')
	<li class="breadcrumb-item">Kelola</li>
	<li class="breadcrumb-item"><a href="{{ route('administration::database.manage.roles.index') }}">Peran</a></li>
	<li class="breadcrumb-item active">{{ $role->display_name }}</li>
@endsection

@section('content')
	<h2 class="mb-4">
		<a class="text-decoration-none small" href="{{ request('next', route('administration::database.manage.roles.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
		Lihat detail peran
	</h2>
	<div class="row">
		<div class="col-sm-4">
			<div class="card">
				<div class="card-header"><i class="mdi mdi-star"></i> Informasi peran</div>
				<div class="card-body">
					@foreach([
						'Nama peran' => $role->display_name ?? '-',
						'Kode' => $role->name,
						'Dibuat pada' => $role->created_at->isoFormat('LLLL'),
					] as $__k => $__v)
						<p @if($loop->last) class="mb-0" @endif>{{ $__k }} <br> <strong>{!! $__v !!}</strong></p>
					@endforeach
				</div>
			</div>	
			<div class="card">
				<div class="card-body">
					<div class="h1 text-muted text-right mb-4">
						<i class="mdi mdi-account-box-multiple-outline float-right"></i>
					</div>
					<div class="text-value">{{ $role->users_count }}</div>
					<small class="text-muted text-uppercase font-weight-bold">Jumlah anggota</small>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="card">
				<div class="card-header"><i class="mdi mdi-pencil"></i> Ubah peran</div>
				<div class="card-body">
					<form class="form-block" action="{{ route('administration::database.manage.roles.update', ['role' => $role->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
				        <div class="form-group required">
				            <label class="col-form-label text-md-right pt-0">Kode peran</label>
				            <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $role->name) }}" required autofocus @cannot('update', $role) readonly disabled @endcannot>
				            @if ($errors->has('name')) 
				                <span class="invalid-feedback"> {{ $errors->first('name') }} </span>
				            @endif
				        </div>
				        <div class="form-group required">
				            <label class="col-form-label text-md-right pt-0">Nama peran</label>
				            <input type="text" class="form-control{{ $errors->has('display_name') ? ' is-invalid' : '' }}" name="display_name" value="{{ old('display_name', $role->display_name) }}" required autofocus @cannot('update', $role) readonly disabled @endcannot>
				            @if ($errors->has('display_name')) 
				                <span class="invalid-feedback"> {{ $errors->first('display_name') }} </span>
				            @endif
				        </div>
				        <div class="form-group">
				            <label class="col-form-label text-md-right">Hak akses</label>
				            @if ($errors->has('permissions.0')) 
				                <div>
				                    <small class="text-danger"> {{ $errors->first('permissions.0') }} </small>
				                </div>
				            @endif
				            @foreach ($permissions->chunk(2) as $chunk)
				                <div class="row">
				                    @foreach ($chunk as $permission)
				                        <div class="col-md-6">
				                            <div class="custom-control custom-checkbox">
				                                <input class="custom-control-input autocheck" type="checkbox" id="permissions-{{ $permission->id }}" value="{{ $permission->id }}" name="permissions[]" autocheck="{{ $permission->group }}" @if($role->hasPermissions([$permission->name])) checked @endif @cannot('update', $role) disabled @endcannot>
				                                <label class="custom-control-label" for="permissions-{{ $permission->id }}">{{ $permission->display_name ?: $permission->name }}</label>
				                            </div>
				                        </div>
				                    @endforeach
				                </div>
				            @endforeach
				        </div>
					    <div class="form-group mb-0">
					        @can('update', $role)
					            <button class="btn btn-primary" type="submit">Simpan</button>
					        @endcan
					        <a class="btn btn-secondary" href="{{ request('next', route('administration::database.manage.roles.index')) }}">Kembali</a>
					    </div>
					</form>
				</div>
			</div>
			{{-- <div class="card">
				<div class="card-header text-center">
					<ul class="nav nav-pills">
						<li class="nav-item"> <a class="nav-link @if($page == null) active bg-success @endif" href="{{ url()->current() }}">Profil</a> </li>
						<li class="nav-item"> <a class="nav-link @if($page == 'account') active bg-success @endif" href="?page=account">Akun</a> </li>
						<li class="nav-item"> <a class="nav-link @if($page == 'email') active bg-success @endif" href="?page=email">Alamat e-mail</a> </li>
						<li class="nav-item"> <a class="nav-link @if($page == 'phone') active bg-success @endif" href="?page=phone">Nomor HP</a> </li>
					</ul>
				</div>
				<div class="card-body">
					@if($page == null)
						<form class="form-block" action="{{ route('administration::database.manage.roles.update.profile', ['role' => $role->id]) }}" method="POST"> @csrf @method('PUT')
							@include('account::role.profile.includes.form', ['role' => $role])
						</form>
					@endif
					@if($page == 'account')
						<form class="form-block" action="{{ route('administration::database.manage.roles.update', ['role' => $role->id]) }}" method="POST"> @csrf @method('PUT')
							<div class="row">
								<div class="col-md-10 col-lg-8">
									<div class="form-group required">
										<label>Username</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">@</span>
											</div>
											<input type="text" class="form-control @error('rolename') is-invalid @enderror" name="rolename" value="{{ old('rolename', $role->rolename) }}" required>
										</div>
										<small class="form-text text-muted">Nama unik peran (bukan nama lengkap), digunakan untuk login, terdiri dari huruf kecil, titik, dan angka, tanpa spasi.</small>
										@error('rolename')
										<small class="text-danger"> {{ $message }} </small>
										@enderror
									</div>
								</div>
							</div>
							<div class="form-group mb-0">
								<button class="btn btn-primary" type="submit">Simpan</button>
							</div>
						</form>
					@endif
					@if($page == 'email')
						<form class="form-block" action="{{ route('administration::database.manage.roles.update.email', ['role' => $role->id]) }}" method="POST"> @csrf @method('PUT')
							<div class="row">
								<div class="col-md-10 col-lg-8">
									@include('account::role.email.includes.form', ['role' => $role, 'next' => url()->full()])
								</div>
							</div>
						</form>
					@endif
					@if($page == 'phone')
						<form class="form-block" action="{{ route('administration::database.manage.roles.update.phone', ['role' => $role->id]) }}" method="POST"> @csrf @method('PUT')
							<div class="row">
								<div class="col-md-10 col-lg-8">
									@include('account::role.phone.includes.form', ['role' => $role])
								</div>
							</div>
						</form>
					@endif
				</div>
			</div> --}}
		</div>
	</div>
@endsection