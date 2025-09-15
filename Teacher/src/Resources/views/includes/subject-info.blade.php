@php
	$__subject = [
		'Kode mapel'	=> $subject->kd,
		'Nama mapel'	=> $subject->name,
		'Jenjang kelas'	=> $subject->level->kd.' - '.$subject->level->name,
		'Kategori'	=> $subject->category->name ?? '-',
	];
@endphp

<div class="card">
	<div class="card-header"><i class="mdi mdi-book-outline"></i> Informasi mapel</div>
	<div class="card-body">
		@foreach($__subject as $k => $v)
			<p @if($loop->last) class="mb-0" @endif>{{ $k }}<br> <strong>{{ $v }}</strong></p>
		@endforeach
	</div>
	<div class="list-group list-group-flush border-top">
		<a class="list-group-item list-group-item-action text-primary" href="{{ route('teacher::subjects.competences.index', ['subject' => $subject->id]) }}"><i class="mdi mdi-cogs"></i> Kelola kompetensi</a>
		<a class="list-group-item list-group-item-action text-primary" href="{{ route('teacher::evaluation.index') }}"><i class="mdi mdi-counter"></i> Tipe penilaian</a>
	</div>
</div>