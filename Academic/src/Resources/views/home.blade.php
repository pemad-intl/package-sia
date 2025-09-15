@extends('academic::layouts.default')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="jumbotron mb-4 border bg-white p-4">

                @if (session('login_as_nik'))
                    <h2>Assalamu'alaikum Wali,  {{ \Str::title($user->name) }}!</h2>
                @else
                    <h2>Assalamu'alaikum {{ \Str::title($user->name) }}!</h2>
                @endif

                <p class="text-muted">Selamat datang di {{ config('academic.home.name') }}</p>
                <hr>
                Tahun Ajaran <strong>{{ $acsem->full_name }}</strong>
            </div>
            <div class="card">
                <div class="card-header"><i class="mdi mdi-school-outline"></i> <strong>Data nilai</strong></div>
                <div class="list-group list-group-flush">
                    @if (!empty($stsem))
                        @forelse($stsem->classroom->meets as $meet)
                            <a class="list-group-item list-group-item-action bg-light" data-toggle="collapse" data-target="#accordion-assessment-{{ $meet->id }}">
                                <div class="font-weight-bold mr-4"><i class="mdi mdi-label text-{{ $meet->props->color ?? 'dark' }}"></i> {{ $meet->subject->name }}</div>
                            </a>
                            @php($assessments = $stsem->assessments->where('subject_id', $meet->subject_id))
                            <div id="accordion-assessment-{{ $meet->id }}" class="@if ($loop->first) show @endif list-group list-group-item list-group-flush collapse p-0">
                                @forelse($assessments as $assessment)
                                    <div class="list-group-item d-flex justify-content-between flex-row">
                                        <div class="font-weight-bold mr-4">{{ $assessment->type_name }}</div>
                                        <div>{{ $assessment->value }}</div>
                                    </div>
                                @empty
                                    <div class="list-group-item">Tidak ada data nilai</div>
                                @endforelse
                            </div>
                        @empty
                            <div class="list-group-item">Tidak ada jadwal yang diterapkan</div>
                        @endforelse
                    @else
                        <div class="list-group-item">Tidak ada semester aktif</div>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header"><i class="mdi mdi-school-outline"></i> <strong>Data kasus</strong></div>
                <div class="list-group list-group-flush mb-0">
                    @if (!empty($stsem))
                        @forelse($stsem->cases as $case)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center flex-row p-1">
                                    <div class="p-1">
                                        <i class="mdi mdi-briefcase-account-outline mdi-36px text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong>{{ $case->semester->classroom->name . ' - ' . $case->semester->student->full_name }}</strong> <br>
                                        {{ $case->category->name }} &mdash; {{ $case->description }} <br>
                                        <small class="text-muted">Saksi: {{ $case->witness }} - {{ \Carbon\Carbon::parse($case->break_at)->diffForHumans() }}</small>
                                    </div>
                                    <div class="pl-3 text-center">
                                        <h2 class="text-danger mb-0">{{ $case->point ?: '?' }}</h2>
                                        <small class="text-muted">Poin</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item">Tidak ada data kasus.</div>
                        @endforelse
                    @else
                        <div class="list-group-item">Tidak ada semester aktif.</div>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header"><i class="mdi mdi-school-outline"></i> <strong>Data prestasi</strong></div>
                <div class="card-body">
                    @forelse(optional(optional($student)->user)->achievements ?? [] as $achievement)
                        <div class="d-flex justify-content-between mb-2 flex-row">
                            <div class="flex-grow-1 mr-3">
                                <span class="badge badge-dark">{{ $achievement->type->name }}</span><br>
                                <strong>{{ $achievement->name }}</strong><br>
                                Peringkat {{ $achievement->num->name }} di {{ $achievement->territory->name }}<br>
                                <span class="text-muted">Tahun {{ $achievement->year }}</span>
                            </div>
                            <form class="form-block form-confirm" action="{{ route('administration::scholar.students.achievements.destroy', ['student' => $student->id, 'achievement' => $achievement->id]) }}" method="POST"> @csrf @method('DELETE')
                                @if (Storage::exists($achievement->file))
                                    <a class="btn btn-primary btn-sm" href="{{ Storage::url($achievement->file) }}" target="_blank">Lihat berkas</a>
                                @endif
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</a>
                            </form>
                        </div>
                    @empty
                        <div>Tidak ada data prestasi, silahkan tekan tombol dibawah untuk menambahkan data prestasi.</div>
                    @endforelse
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
            </div>
        @else
            <div class="col-md-4">
                <div class="alert alert-info"> User ini Tidak terdaftar sebagai murid</div>
            </div>
        @endif
    </div>
@endsection
