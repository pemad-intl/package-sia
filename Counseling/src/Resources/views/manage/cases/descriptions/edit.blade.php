@extends('counseling::layouts.default')

@section('title', 'Ubah deskripsi kasus - ')

@section('content')
	<div class="row d-flex justify-content-center">
		<div class="col-md-8 col-lg-6">
			<h2 class="mb-4">
				<a class="text-decoration-none small" href="{{ request('next', route('counseling::manage.cases.descriptions.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
				Ubah deskripsi kasus
			</h2>
			<div class="card">
				<div class="card-body">
					<form class="form-block" action="{{ route('counseling::manage.cases.descriptions.update', ['description' => $description->id, 'next' => request('next', route('counseling::manage.cases.descriptions.index'))]) }}" method="POST"> @csrf @method('PUT')
						<div class="form-group required">
							<label>Kategori</label>
							<select name="ctg_id" class="form-control @error('name') is-invalid @enderror" required>
								<option value="">-- Pilih --</option>
								@foreach($categories as $_category)
									<option value="{{ $_category->id }}" @if(old('ctg_id', $description->ctg_id) == $_category->id) selected @endif>{{ $_category->name }}</option>
								@endforeach
							</select>
							@error('name')
								<small class="text-danger"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group required">
							<label>Deskripsi</label>
							<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $description->name) }}" required autocomplete="off">
							@error('name')
								<small class="text-danger"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group required">
							<label>Poin</label>
							<input type="number" class="form-control w-50 @error('point') is-invalid @enderror" name="point" value="{{ old('point', $description->point) }}" required autocomplete="off">
							@error('point')
								<small class="text-danger"> {{ $message }} </small>
							@enderror
						</div>
						<p class="text-muted">Perubahan nama tidak berpengaruh terhadap riwayat kasus siswa</p>
						<div class="form-group mb-0">
							<button class="btn btn-primary">Simpan</button>
							<a class="btn btn-secondary" href="{{ request('next', route('counseling::manage.cases.descriptions.index')) }}">Kembali</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
