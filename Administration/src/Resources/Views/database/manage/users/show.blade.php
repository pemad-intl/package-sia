@extends('administration::layouts.default')

@section('title', $user->profile->name.' - ')

@section('breadcrumb')
	<li class="breadcrumb-item">Kelola</li>
	<li class="breadcrumb-item"><a href="{{ route('administration::database.manage.users.index') }}">Pengguna</a></li>
	<li class="breadcrumb-item active">{{ $user->id }}</li>
@endsection

@php
$page = request('page');

if(!in_array($page, ['account', 'email', 'phone', 'role'])) $page = null;
@endphp

@section('content')
	<h2 class="mb-4">
		<a class="text-decoration-none small" href="{{ request('next', route('administration::database.manage.users.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
		Lihat detail pengguna
	</h2>
	<div class="row">
		<div class="col-sm-4">
			<div class="card mb-4">
				<div class="card-body text-center">
					<div class="py-4">
						<img class="rounded-circle" src="{{ asset('img/default-avatar.svg') }}" alt="" width="128">
					</div>
					<h5 class="mb-1"><strong>{{ $user->profile->name }}</strong></h5>
					<p>{{ $user->username }}</p>
					<h4 class="mb-0">
						@if($user->phone->whatsapp)
							<a class="text-primary px-1" href="https://wa.me/{{ $user->phone->number }}" target="_blank"><i class="mdi mdi-whatsapp"></i></a>
						@endif
						@if($user->email->verified_at)
							<a class="text-danger px-1" href="mailto:{{ $user->email->address }}"><i class="mdi mdi-email-outline"></i></a>
						@endif
					</h4>
				</div>
				<div class="list-group list-group-flush border-top">
					<a class="list-group-item list-group-item-action text-primary border-0" href="javascript:;" onclick="event.preventDefault(); $('#reset-password').submit();"><i class="mdi mdi-lock-open"></i> Atur ulang sandi</a>
				</div>
			</div>
			<div class="card mb-4">
				<div class="card-body">
					<h4 class="mb-1">Info akun</h4>
					<p class="mb-2 text-muted">Informasi tentang akun {{ $user->profile->display_name }}</p>
				</div>
				<div class="list-group list-group-flush">
					@foreach([
						'Bergabung pada' => $user->created_at->diffForHumans(),
					] as $k => $v)
						<div class="list-group-item border-0">
							{{ $k }} <br>
							<span class="{{ $v ? 'font-weight-bold' : 'text-muted' }}">
								{{ $v ?? 'Belum diisi' }}
							</span>
						</div>
					@endforeach
					<div class="list-group-item border-0 text-muted">
						<i class="mdi mdi-account-circle"></i> User ID : {{ $user->id }}
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="card">
				<div class="card-header text-center">
					<ul class="nav nav-pills">
						<li class="nav-item"> <a class="nav-link @if($page == null) active bg-primary @endif" href="{{ url()->current() }}">Profil</a> </li>
						<li class="nav-item"> <a class="nav-link @if($page == 'account') active bg-primary @endif" href="?page=account">Akun</a> </li>
						<li class="nav-item"> <a class="nav-link @if($page == 'email') active bg-primary @endif" href="?page=email">Alamat e-mail</a> </li>
						<li class="nav-item"> <a class="nav-link @if($page == 'phone') active bg-primary @endif" href="?page=phone">Nomor HP</a> </li>
						@can('assign-user-roles', $user)
							<li class="nav-item"> <a class="nav-link @if($page == 'role') active bg-primary @endif" href="?page=role">Peran</a> </li>
						@endcan
					</ul>
				</div>
				<div class="card-body">
					@if($page == null)
						<form class="form-block" action="{{ route('administration::database.manage.users.update.profile', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
							@include('account::user.profile.includes.form', ['user' => $user])
						</form>
					@endif
					@if($page == 'account')
						<form class="form-block" action="{{ route('administration::database.manage.users.update', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
							<div class="row">
								<div class="col-md-10 col-lg-8">
									<div class="form-group required">
										<label>Username</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">@</span>
											</div>
											<input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required>
										</div>
										<small class="form-text text-muted">Nama unik pengguna (bukan nama lengkap), digunakan untuk login, terdiri dari huruf kecil, titik, dan angka, tanpa spasi.</small>
										@error('username')
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
						<form class="form-block" action="{{ route('administration::database.manage.users.update.email', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
							<div class="row">
								<div class="col-md-10 col-lg-8">
									@include('account::user.email.includes.form', ['user' => $user, 'next' => url()->full()])
								</div>
							</div>
						</form>
					@endif
					@if($page == 'phone')
						<form class="form-block" action="{{ route('administration::database.manage.users.update.phone', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
							<div class="row">
								<div class="col-md-10 col-lg-8">
									@include('account::user.phone.includes.form', ['user' => $user])
								</div>
							</div>
						</form>
					@endif
					@if($page == 'role')
						@can('assign-user-roles', $user)
							<form class="form-block" action="{{ route('administration::database.manage.users.update.roles', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
								<div class="row">
									<div class="col-md-10 col-lg-8">
										@include('administration::database.manage.users.includes.roles', ['user' => $user])
									</div>
								</div>
							</form>
						@else
							Maaf anda tidak berhak mengakses halaman ini
						@endcan
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection

@push('script')
<form class="d-inline form-block form-confirm" action="{{ route('administration::database.manage.users.repass', ['user' => $user->id]) }}" method="POST" id="reset-password"> @csrf @method('PUT')</form>
@endpush