@extends('administration::layouts.default')

@section('title', 'Mohon maaf - ')

@section('breadcrumb')
	<li class="breadcrumb-item active">Mohon maaf</li>
@endsection

@section('content')
	<div class="row align-items-center pt-5" style="margin-top: 10%;">
		<div class="col text-center">
			<div class="display-2 text-primary"><i class="mdi mdi-information-outline"></i></div>
			<h3>Mohon maaf!</h3>
			<p class="text-muted">Tidak ada tahun ajaran yang dibuka, silahkan hubungi Administrator untuk informasi lebih lanjut.</p>
			<a class="btn btn-secondary" href="{{ url()->previous() }}">Kembali</a>
		</div>
	</div>
@endsection