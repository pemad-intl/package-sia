@extends('counseling::layouts.default')

@section('title', 'Ubah kategori konseling - ')

@section('content')
	<div class="row d-flex justify-content-center">
		<div class="col-md-8 col-lg-6">
			<h2 class="mb-4">
				<a class="text-decoration-none small" href="{{ request('next', route('counseling::manage.counseling.categories.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
				Ubah kategori konseling
			</h2>
			<div class="card">
				<div class="card-body">
					<form class="form-block" action="{{ route('counseling::manage.counseling.categories.update', ['category' => $category->id, 'next' => request('next', route('counseling::manage.counseling.categories.index'))]) }}" method="POST"> @csrf @method('PUT')
						<div class="form-group required">
							<label>Nama kategori</label>
							<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $category->name) }}" required autocomplete="off">
							@error('name')
								<small class="text-danger"> {{ $message }} </small>
							@enderror
						</div>
						<p class="text-muted">Perubahan nama akan diterapkan ke seluruh deskripsi konseling yang berkategori <strong>{{ $category->name }}</strong></p>
						<div class="form-group mb-0">
							<button class="btn btn-primary">Simpan</button>
							<a class="btn btn-secondary" href="{{ request('next', route('counseling::manage.counseling.categories.index')) }}">Kembali</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
