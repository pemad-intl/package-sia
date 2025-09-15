@extends('layouts.pdf')

@section('title', $filename)

@push('styles')
	 <style>
        @page {
            header: page-header;
            footer: page-footer;
            margin-top: 2.5cm;
            margin-bottom: 4cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .page-break {
            page-break-after: always;
        }

        /* Tambahkan padding supaya konten tidak nabrak */
        .content {
            padding: 0 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ffffff;
            padding: 5px;
        }
    </style>
@endpush


@section('content')
	<div style="margin-top: 2cm;">
		<htmlpageheader name="page-header">
			<table>
				<tr>
					<td style="padding: 5px;">Nama</td> <td style="padding: 5px;" width="8px">:</td> <td style="padding: 5px;">{{ $user->profile->fullname }}</td>
					<td style="padding: 5px;">Madrasah</td> <td style="padding: 5px;" width="8px">:</td> <td style="padding: 5px;">{{ config('app.name') }}</td>
				</tr>
				<tr>
					<td style="padding: 5px;">NIS</td> <td style="padding: 5px;" width="8px">:</td> <td style="padding: 5px;">{{ $student->nis }}</td>
					<td style="padding: 5px;">Kelas</td> <td style="padding: 5px;" width="8px">:</td> <td style="padding: 5px;">{{ $stsem->classroom->name }}</td>
				</tr>
				<tr>
					<td style="padding: 5px;">NISN</td> <td style="padding: 5px;" width="8px">:</td> <td style="padding: 5px;">{{ $student->nisn }}</td>
					<td style="padding: 5px;">Tahun Pelajaran</td> <td style="padding: 5px;" width="8px">:</td> <td style="padding: 5px;">{{ $acsem->full_name }}</td>
				</tr>
			</table>
		</htmlpageheader>
	</div>
	<hr>
	<br>
	<h4 class="center">CAPAIAN HASIL BELAJAR</h4>
	<br>
	<br>
	<p><strong>A. PENGETAHUAN</strong></p>
	<br>
	<div>
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">Mapel</th>
					<th colspan="2">Pengetahuan</th>
				</tr>
				<tr>
					<th>Angka</th>
					<th>Pred</th>
				</tr>
			</thead>
			<tbody>
				@if($stsem)
					@forelse($stsem->reports as $report)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $report->subject->name }}</td>
							<td>{{ $report->ki3_value }}</td>
							<td>{{ $report->ki3_description }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="6">Tidak ada nilai raport</td>
						</tr>
					@endforelse

					{{-- @forelse($stsem->reports as $report)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $report->subject->name }}</td>
							<td>{{ $report->ki4_predicate }}</td>
							<td>{{ $report->ki4_description }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="6">Tidak ada nilai raport</td>
						</tr>
					@endforelse --}}
				@else
					<tr>
						<td colspan="6">Tidak ada semester aktif</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
	<br>
	<br>
	<p><strong>B. KETERAMPILAN</strong></p>
	<br>
	<div>
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">Mapel</th>
					<th colspan="2">Keterampilan</th>
				</tr>
				<tr>
					<th>Angka</th>
					<th>Pred</th>
				</tr>
			</thead>
			<tbody>
				@if($stsem)
					@forelse($stsem->reports as $report)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $report->subject->name }}</td>
							<td>{{ $report->ki4_value }}</td>
							<td>{{ $report->ki4_description }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="4">Tidak ada nilai raport</td>
						</tr>
					@endforelse
				@else
					<tr>
						<td colspan="4">Tidak ada semester aktif</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
	<br>
	<br>
	<p><strong>C. PRESTASI</strong></p>
	<br>
	<div>
		<table class="table">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Tanggal</th>
				</tr>
			</thead>
			<tbody>
				@if($stsem)
					@forelse($achievementsSmt as $achieve)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $achieve->name }}</td>
							<td>{{ \Carbon\Carbon::parse($achieve->date)->translatedFormat('d F Y') }}</td>						
							</tr>
					@empty
						<tr>
							<td colspan="3">Tidak ada Prestasi</td>
						</tr>
					@endforelse
				@else
					<tr>
						<td colspan="4">Tidak ada semester aktif</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
	<br>
	<br>
	<p><strong>D. Ekstrakulikuler</strong></p>
	<br>
	<div>
		<table class="table">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
				</tr>
			</thead>
			<tbody>
				@if($stsem)
					@forelse($stsem->extras as $extra)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $extra->name }}</td>					
							</tr>
					@empty
						<tr>
							<td colspan="3">Tidak ada Ekstrakulikuler</td>
						</tr>
					@endforelse
				@else
					<tr>
						<td colspan="4">Tidak ada semester aktif</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
	<br>
	<br>


	<div class="page-break"></div>
	<p><strong>E. Ketidakhadiran</strong></p>
	<br>
	<div>
		<table class="table table-bordered">
			<tbody>
				@if($stsem)
					<tr>
						<td>Sakit</td>
						<td>{{$summary['sakit']}}</td>
						<td>Hari</td>
					</tr>

					<tr>
						<td>Izin</td>
						<td>{{$summary['izin']}}</td>
						<td>Hari</td>
					</tr>

					<tr>
						<td>Alpha</td>
						<td>{{$summary['alpha']}}</td>
						<td>Hari</td>
					</tr>
				@else
					<tr>
						<td colspan="4">Tidak ada semester aktif</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
	<br>
	<br>
	<p><strong>F. Catatan Pendidik</strong></p>

	<br>
	<br>
	
	@if($stsem)
		@forelse($stsem->reportEval as $report)
			<div style="margin-bottom: 20px;">
				<div style="border: 1px solid #000; padding: 10px;">
					{{ $report->subject_note }}
				</div>
			</div>

			<div style="margin-bottom: 20px;">
				<div style="border: 1px solid #000; padding: 10px;">
					{{ $report->recommendation_note }}, yaitu kelas {{ $report->grade }}
				</div>
			</div>
		@empty
			<p>Tidak ada Komentar</p>
		@endforelse
	@else
		<p>Tidak ada semester aktif</p>
	@endif



	{{-- Tambahan tanda tangan --}}
	<div class="ttd-footer" style="margin-top:50px;">
		<table>
			<tr>
				<td align="center">
					Orang Tua/Wali <br><br><br><br><br><br>
					<u>.....................................</u>
				</td>
				<td align="center">
					{{ config('app.location', '..........................') }}, {{ now()->translatedFormat('d F Y') }} <br>
					Wali Kelas <br><br><br><br><br>
					<u>{{ $stsem->classroom->supervisor->name ?? '.....................................' }}</u>
				</td>
			</tr>
		</table>
	</div>

@endsection
