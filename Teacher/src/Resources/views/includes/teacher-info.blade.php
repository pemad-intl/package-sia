@php
	$__teacher = [
		'NIP'	=> $teacher->employee->nip ?? '-',
		'NUPTK'	=> $teacher->nuptk ?? 'Tidak ada',
		'Mengajar sejak'	=> $teacher->teaching_at ? $teacher->teaching_at->diffForHumans() : '-',
	];
@endphp

<div class="card">
	<div class="card-header"><i class="mdi mdi-account-question-outline"></i> Informasi {{ $teacher->user->name }} </div>
	<div class="card-body">
		@foreach($__teacher as $k => $v)
			<p @if($loop->last) class="mb-0" @endif>{{ $k }}<br> <strong>{{ $v }}</strong></p>
		@endforeach
	</div>
</div>