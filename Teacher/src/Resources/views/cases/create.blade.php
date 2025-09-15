@extends('teacher::layouts.default')

@section('title', 'Input kasus baru - ')

@section('content')
	<div class="row">
		<div class="col-md-7 col-lg-8">
			<div class="card mb-4">
				<div class="card-header">
					<i class="mdi mdi-briefcase-plus-outline mr-2 float-left"></i>Input kasus baru
				</div>
				<div class="card-body">
					<form action="{{ route('teacher::case', ['next' => request('next')]) }}" method="POST"> @csrf
						<div class="form-group required">
							<label>Nama siswa</label>
							<select class="form-control @error('smt_id') is-invalid @enderror" name="smt_id[]" data-placeholder="Cari nama siswa disini ..." multiple="multiple" required>
								@foreach($classrooms as $classroom => $semesters)
									@foreach($semesters as $semester)
										<option value="{{ $semester->id }}" @if(in_array($semester->id, old('smt_id', []))) selected @endif>{{ $classroom.' - '.$semester->student->full_name }}</option>
									@endforeach
								@endforeach
							</select>
							@error('smt_id')
								<small class="invalid-feedback"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group required w-75 mb-3">
							<label>Kategori kasus</label>
							<select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
								<option value="">-- Pilih --</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}" @if($category->id == old('category_id')) selected @endif data-descriptions="{{ $category->descriptions }}">{{ $category->name }}</option>
								@endforeach
							</select>
							@error('category_id')
								<small class="invalid-feedback"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group required w-75 mb-3">
							<label>Deskripsi</label>
							<select class="form-control @error('description') is-invalid @enderror" name="description" data-placeholder="Pilih kategori terlebih dahulu" required>
								@if(old('description'))
									@foreach($categories->firstWhere('id', old('category_id'))->descriptions as $description)
										<option value="{{ $description->name }}" @if(old('description') == $description->name) selected @endif>{{ $description->name }}</option>
									@endforeach
								@endif
							</select>
							@error('description')
								<small class="invalid-feedback"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group required w-75 mb-3">
							<label>Saksi</label>
							<input class="form-control @error('witness') is-invalid @enderror" name="witness" type="text" value="{{ old('witness') }}" required>
							@error('witness')
								<small class="invalid-feedback"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group required w-50 mb-3">
							<label>Tanggal dan waktu</label>
							<input class="form-control @error('break_at') is-invalid @enderror" name="break_at" type="datetime-local" value="{{ old('break_at', date('Y-m-d\TH:i')) }}" required>
							@error('break_at')
								<small class="invalid-feedback"> {{ $message }} </small>
							@enderror
						</div>
						<div class="form-group mb-0">
							<button class="btn btn-primary">Simpan</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-5 col-lg-4">
			@include('account::includes.account-info')
		</div>
	</div>
@endsection

@push('style')
	<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.min.css') }}">
@endpush

@push('script')
	<script type="text/javascript" src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
	<script>
		$('[name="smt_id[]"]').select2({
			minimumInputLength: 1,
			theme: 'bootstrap4'
		});
		$('[name="category_id"]').on('change', (e) => {
			var descs = $('[name="category_id"] > option:selected').data('descriptions');
			$('[name="description"]').html('')
			$.each(descs, (k, v) => {
				$('[name="description"]').append(`<option value="${v.name}" data-point="${v.point}">${v.name}</option>`);
			});
		});
		$('[name="description"]').select2({
			theme: 'bootstrap4',
			tags: true
		});
	</script>
@endpush
