@extends('teacher::layouts.default')

@section('title', 'Kelola kompetensi - ')

@php
	$__indicators = $errors->has('indicators.*') ? $errors->get('indicators.*') : [0];
@endphp

@section('content')
    <div class="row">
		<div class="col-md-8">
			<div class="card mb-4">
				<div class="card-header">
					<i class="mdi mdi-account-details mr-2 float-left"></i>Kompetensi mapel
				</div>
				<div class="table-responsive">
					<table class="table table-hover mb-0 border-bottom">
						<thead class="thead-dark">
							<tr>
								<th>KD</th>
								<th>Indikator</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@forelse($subject->competences as $competence)
								<tr>
									<td class="align-middle" style="min-width: 200px;">{{ $competence->full_name }}</td>
									<td class="align-middle" style="min-width: 200px;">
										<ul class="pl-3 mb-0">
											@foreach($competence->indicators as $indicator)
												<li>{{ $indicator }}</li>
											@endforeach
										</ul>
									</td>
									<td nowrap class="py-2 align-middle text-right">
										{{-- <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah mapel" href="{{ route('teacher::subjects.competences.edit', ['subject' => $subject->id, 'competence' => $competence->id]) }}"><i class="mdi mdi-pencil"></i></a> --}}
										<form class="d-inline form-block form-confirm" action="{{ route('teacher::subjects.competences.destroy', ['subject' => $subject->id, 'competence' => $competence->id]) }}" method="POST"> @csrf @method('DELETE')
											<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus"><i class="mdi mdi-delete-outline"></i></button>
										</form>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="3" class="text-center"><i>Tidak ada data kompetensi</i></td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<div class="card-footer border-top-0">
					<small class="text-muted">Menampilkan total {{ $subject->competences->count() }} kompetensi</small>
				</div>
			</div>
			<div class="card mb-4">
				<div class="card-header">
					<i class="mdi mdi-plus-circle-outline mr-2 float-left"></i>Tambah kompetensi
				</div>
				<div class="card-body">
					<form class="form-block" action="{{ route('teacher::subjects.competences.store', ['subject' => $subject->id]) }}" method="POST"> @csrf
						<div class="form-group required row">
							<label class="col-sm-4 col-md-3 col-form-label">Kode KD</label>
							<div class="col-sm-6 col-md-5">
								<input class="form-control @error('kd') is-invalid @enderror" name="kd" type="text" value="{{ old('kd') }}" required>
								<small class="text-muted">Diisi kode kompetensi dasar, misal "3.2."</small>
								@error('kd')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>
						<div class="form-group required row">
							<label class="col-sm-4 col-md-3 col-form-label">Nama</label>
							<div class="col-sm-7 col-md-8">
								<input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}" required>
								<small class="text-muted">Diisi nama kompetensi dasar, misal "Memahami bilangan aljabar"</small>
								@error('name')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>
						<div class="form-group required row">
							<label class="col-sm-4 col-md-3 col-form-label">Indikator</label>
							<div class="col-sm-7 col-md-8">
								<div id="indicators-inputs">
									@foreach($__indicators as $i => $v)
										<div class="input-group {{ !$loop->first ? 'mt-3 indicators-input-news' : '' }}">
											<div class="input-group-prepend">
												<span class="input-group-text text-center">-</span>
											</div>
											<input class="form-control @error($i) is-invalid @enderror" name="indicators[]" type="text" value="{{ old($i) }}" required>
											@if(!$loop->first)
												<div class="input-group-append">
													<button type="button" class="btn btn-danger" onclick="$(this).parents('.indicators-input-news').remove()">&times;</button>
												</div>
											@endif
										</div>
									@endforeach
								</div>
								<div>
									<button id="indicators-add" type="button" class="btn btn-link px-0 text-decoration-none"><i class="mdi mdi-plus"></i>Tambah indikator</button>
								</div>
								<small class="text-muted">Diisi nama indikator, tanpa kode, misal "Siswa dapat menjelaskan bilangan aljabar"</small>
								@error('indicaors')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8 col-md-9 offset-sm-4 offset-md-3">
								<button class="btn btn-primary"><i class="mdi mdi-check-circle-outline"></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			@include('teacher::includes.subject-info', ['subject' => $subject])
		</div>
	</div>
@endsection

@push('script')
	<script>
		$(() => {
			$('#indicators-add').click(() => {
				if ($('#indicators-inputs .input-group').length < 5){
					$('#indicators-inputs').append('<div class="input-group mt-3 indicators-input-news"> <div class="input-group-prepend"> <span class="input-group-text">+</span> </div> <input class="form-control" name="indicators[]" type="text" required> <div class="input-group-append"><button type="button" class="btn btn-danger" onclick="$(this).parents(\'.indicators-input-news\').remove();">&times;</button></div></div>');
				} else {
					alert('Maaf, maksimal hanya 5 indikator!');
				}
			});
		})
	</script>
@endpush