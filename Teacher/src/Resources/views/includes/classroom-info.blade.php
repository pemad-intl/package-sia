@php
	$classroom->loadCount('students');
	$__classroom = [
		'Nama rombel'	=> $classroom->name,
		'Jurusan'	=> $classroom->major_superior ?: '-',
		'Wali kelas' => $classroom->supervisor->full_name ?: '-',
		'Jumlah siswa' => $classroom->students_count.' siswa'
	];
@endphp

<div class="card">
	<div class="card-header"><i class="mdi mdi-account-group-outline"></i> Informasi rombel</div>
	<div class="card-body">
		@foreach($__classroom as $k => $v)
			<p @if($loop->last) class="mb-0" @endif>{{ $k }}<br> <strong>{{ $v }}</strong></p>
		@endforeach
	</div>
</div>