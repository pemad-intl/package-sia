@extends('administration::layouts.default')

@section('title', 'Mapel - ')

@section('breadcrumb')
	<li class="breadcrumb-item">Kurikulum</li>
	<li class="breadcrumb-item active">Mapel</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div class="card mb-4">
				<div class="card-header">
					<i class="mdi mdi-account-details mr-2 float-left"></i>Mapel
				</div>
				<div class="card-body">
					<form action="{{ route('administration::curriculas.subjects.index', ['academic' => request('academic')]) }}" method="GET">
						<input type="hidden" name="trash" value="{{ request('trash') }}">
						<div class="input-group">
							<input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama mapel disini ...">
							<div class="input-group-append">
								<a class="btn btn-outline-secondary" href="{{ route('administration::curriculas.subjects.index', ['academic' => request('academic')]) }}"><i class="mdi mdi-refresh"></i></a>
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
								<th>Kode mapel</th>
								<th>Nama mapel</th>
								<th>Kelas</th>
								<th>Kategori</th>
								<th>Warna</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@forelse($subjects as $subject)
								<tr @if($subject->trashed()) class="text-muted bg-light" @endif>
									<td class="align-middle">{{ $loop->iteration + ($subjects->firstItem() - 1) }}</td>
									<td nowrap class="align-middle">{{ $subject->kd }}</td>
									<td nowrap> <strong>{{ $subject->name }} </strong> </td>
									<td nowrap class="align-middle">{{ $subject->level->kd ?? '-' }}</td>
									<td nowrap class="align-middle">{{ $subject->category->name ?? '-' }}</td>
									<td nowarp class="align-middle text-center">
										@php
											$color = $subject->color_id; 
										@endphp
										<span class="d-inline-block rounded-circle"
											style="width: 20px; height: 20px; background-color: {{ $color }}; border: 1px solid #aaa;">
										</span>
									</td>
									<td nowrap class="py-2 align-middle text-right">
										@if($subject->trashed())
											<form class="d-inline form-block form-confirm" action="{{ route('administration::curriculas.subjects.restore', ['subject' => $subject->id]) }}" method="POST"> @csrf @method('PUT')
												<button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-restore"></i></button>
											</form>
											<form class="d-inline form-block form-confirm" action="{{ route('administration::curriculas.subjects.kill', ['subject' => $subject->id]) }}" method="POST"> @csrf @method('DELETE')
												<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
											</form>
										@else
											<a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah mapel" href="{{ route('administration::curriculas.subjects.edit', ['subject' => $subject->id]) }}"><i class="mdi mdi-pencil"></i></a>
											<form class="d-inline form-block form-confirm" action="{{ route('administration::curriculas.subjects.destroy', ['subject' => $subject->id]) }}" method="POST"> @csrf @method('DELETE')
												<button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Buang"><i class="mdi mdi-delete-outline"></i></button>
											</form>
										@endif
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="5" class="text-center"><i>Tidak ada data</i></td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<div class="card-body">
					{{ $subjects->appends(request()->all())->links() }}
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card card-body">
				<form class="form-block" action="{{ route('administration::curriculas.subjects.index') }}" method="GET">
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
					<div class="text-value">{{ $subjects_count }}</div>
					<small class="text-muted text-uppercase font-weight-bold">Jumlah mapel</small>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<i class="mdi mdi-cogs mr-2 float-left"></i>Lanjutan
				</div>
				<div class="list-group list-group-flush">
					<a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::curriculas.subjects.create', ['academic' => request('academic', $acsem->id)]) }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah mapel</a>
					<a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::curriculas.subject-categories.index') }}"><i class="mdi mdi-book-outline"></i> Kelola kategori mapel</a>
					<a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::curriculas.subjects.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan mapel yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
				</div>
			</div>
		</div>
	</div>
@endsection