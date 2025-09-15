@extends('academic::layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="jumbotron mb-4 border bg-white p-4">
                <h2>Assalamu'alaikum {{ \Str::title(auth()->user()->name) }}!</h2>
                <p class="text-muted">Selamat datang di {{ config('academic.home.name') }}</p>
                <hr>
                Tahun Ajaran <strong>{{ $acsem->full_name }}</strong>
            </div>
            <div class="card">
                <div class="card-header"><i class="mdi mdi-school-outline"></i> <strong>Nilai raport</strong> T.A. <strong>{{ $acsem->full_name }}</strong></div>
                <div class="table-responsive">
                    <table class="table-bordered mb-0 table">
                        <thead class="thead-dark">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Mapel</th>
                                <th colspan="2">KI-3 Pengetahuan</th>
                                <th colspan="2">KI-4 Keterampilan</th>
                                <th>KI-3 Pengetahuan</th>
                                <th>KI-4 Keterampilan</th>
                            </tr>
                            <tr>
                                <th>Angka</th>
                                <th>Pred</th>
                                <th>Angka</th>
                                <th>Pred</th>
                                <th>Deskripsi</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($stsem))
                                @forelse($stsem->reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $report->subject->name }}</td>
                                        <td>{{ $report->ki3_value }}</td>
                                        <td>{{ $report->ki3_predicate }}</td>
                                        <td>{{ $report->ki4_value }}</td>
                                        <td>{{ $report->ki4_predicate }}</td>
                                        <td>{{ $report->ki3_description }}</td>
                                        <td>{{ $report->ki4_description }}</td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="8">Tidak ada nilai raport</td>
                                    </tr>
                                @endforelse
                            @else
                                <tr class="text-center">
                                    <td colspan="8">Tidak ada semester aktif</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if (!empty($student->nis))
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="py-4">
                            <img class="rounded-circle" src="{{ asset('img/default-avatar.svg') }}" alt="" width="128">
                        </div>
                        <h5 class="mb-1"><strong>{{ $user->profile->full_name }}</strong></h5>
                        <p>
                            NIS. {{ $student->nis ?: '-' }}
                            @if ($student->nisn)
                                <br> NISN. {{ $student->nisn }}
                            @endif
                        </p>
                        <h4 class="mb-0">
                            @if (!empty($user->phone->whatsapp) && $user->phone->whatsapp)
                                <a class="text-primary px-1" href="https://wa.me/{{ $user->phone->number }}" target="_blank"><i class="mdi mdi-whatsapp"></i></a>
                            @endif
                            @if (!empty($user->email->verified_at) && $user->email->verified_at)
                                <a class="text-danger px-1" href="mailto:{{ $user->email->address }}"><i class="mdi mdi-email-outline"></i></a>
                            @endif
                        </h4>
                    </div>
                    <div class="list-group list-group-flush border-top">
                        @foreach ([
            'Angkatan ke' => optional($student->generation)->name ?: '-',
            'Masuk pada' => optional($student->entered_at)->diffForHumans() ?: '-',
            'Bergabung pada' => $user->created_at->diffForHumans(),
        ] as $k => $v)
                            <div class="list-group-item border-0">
                                {{ $k }} <br>
                                <span class="{{ $v ? 'font-weight-bold' : 'text-muted' }}">
                                    {{ $v ?? 'Belum diisi' }}
                                </span>
                            </div>
                        @endforeach
                        <div class="list-group-item text-muted border-0">
                            <i class="mdi mdi-account-circle"></i> User ID : {{ $user->id }}
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                    </div>
                    <div class="list-group list-group-flush">
                        <a class="list-group-item list-group-item-action text-primary" href="{{ route('academic::report.print') }}" target="_blank"><i class="mdi mdi-printer"></i> Cetak Raport {{ $acsem->full_name }}</a>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-4">
                <div class="alert alert-info"> User ini Tidak terdaftar sebagai murid</div>
            </div>
        @endif
    </div>
@endsection
