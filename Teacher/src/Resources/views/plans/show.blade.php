@extends('teacher::layouts.default')

@section('title', $meet->subject->name . ' ' . $meet->classroom->full_name . ' - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card bg-{{ $meet->props->color ?? 'white border' }} mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-badge-horizontal-outline position-absolute" style="top: 10pt; right: 40pt; font-size: 40pt;"></i>
                    <h2><strong>{{ $meet->subject->name }}</strong></h3>
                        <p class="mb-0">Rombel {{ $meet->classroom->full_name }}</p>
                </div>
            </div>
            <h2>
                <a class="text-decoration-none small text-{{ $meet->props->color ?? 'primary' }}" href="{{ request('next', route('teacher::meet', ['meet' => $meet->id])) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
                Pertemuan {{ $plan->az }}
            </h2>
            <hr>
            <div class="card">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="card-header">
                    <i class="mdi mdi-account-badge-horizontal-outline"></i> Presensi siswa
                    @if ($plan->presence)
                        @if (request('action') != 'presence')
                            <a href="{{ route('teacher::plan', ['plan' => $plan->id, 'action' => 'presence']) }}" class="position-absolute badge badge-{{ $meet->props->color ?? 'primary' }} badge-pill" style="right:15px; top: 15px;"><i class="mdi mdi-pencil"></i> Ubah</a>
                        @else
                            <a href="{{ route('teacher::plan', ['plan' => $plan->id]) }}" class="position-absolute badge badge-secondary badge-pill" style="right:15px; top: 15px;"><i class="mdi mdi-close"></i> Batal</a>
                        @endif
                    @endif
                </div>
                <form class="form-block form-confirm" action="{{ route('teacher::plan.presence', ['plan' => $plan->id, 'next' => url()->current()]) }}" method="POST"> @csrf @method('PUT')
                    <div class="table-responsive">
                        <table class="table-bordered table-striped table-hover mb-0 table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    @foreach ($presenceList as $v)
                                        <th class="text-center">{{ strtoupper(substr($v, 0, 1)) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plan->meet->classroom->students as $student)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td nowrap>{{ $student->full_name }}</td>
                                        @foreach ($presenceList as $k => $v)
                                            <td class="clickable-radio text-center">
                                                <div class="custom-control custom-radio" style="margin-left: 6px;">
                                                <input type="radio" id="presence.{{ $student->pivot->id . '.' . $k }}" name="presence[{{ $student->pivot->id }}]" class="custom-control-input" value="{{ $k }}" @if ($plan->presence) @if ($plan->presence->firstWhere('semester_id', $student->pivot->id)['presence'] == $k) checked @endif @if (!request('action') == 'presence') disabled @endif @else @if ($loop->first) checked @endif @endif>
                                                    <label class="custom-control-label" for="presence.{{ $student->pivot->id . '.' . $k }}"></label>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                    @if ($loop->last && (!$plan->presence || request('action') == 'presence'))
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="py-1" colspan="{{ count($presenceList) }}">
                                                <button type="submit" class="btn btn-{{ $meet->props->color ?? 'primary' }} btn-block"><i class="mdi mdi-check-circle-outline"></i> Simpan</button>
                                            </td>
                                        </tr>
                                    @endif
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="{{ count($presenceList) + 3 }}">Tidak ada data siswa</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="card-footer border-0">
                        <div class="row">
                            @foreach ($presenceList as $v)
                                <div class="col-6 col-sm"><strong>{{ strtoupper(substr($v, 0, 1)) }}</strong> : {{ $v }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if ($plan->test)
                    <div class="card">
                        @if(session('success-asses'))
                            <div class="alert alert-success">
                                {{ session('success-asses') }}
                            </div>
                        @endif

                        <div class="card-header">
                            <i class="mdi mdi-account-badge-horizontal-outline"></i> Penilaian siswa
                        </div>
                        <form class="form-block form-confirm" action="{{ route('teacher::plan.assessment', ['plan' => $plan->id, 'next' => url()->current()]) }}" method="POST"> @csrf @method('PUT')
                            <div class="table-responsive">
    
                                <table class="table-bordered table-striped table-hover mb-0 table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($plan->meet->classroom->students as $student)
                                            @php
                                               // $assessment = $plan->meet->classroom->students->first() ?? null;
                                                $assessment = $plan->assessments->first() ?? null;
                                            @endphp

                                            @if ($loop->first)
                                                <tr>
                                                    <td colspan="3"><strong>Jenis penilaian</strong></td>
                                                    
                                                    <td class="py-1" width="200">
                                                        <select class="form-control" name="type" required>
                                                            <option value="">-- Pilih --</option>
                                                            @foreach ($types as $value => $type)
                                                                <option value="{{ $type->id }}" @if (!empty($assessment->type) && $assessment->type == $type->id) selected @endif>{{ $type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $student->nis }}</td>
                                                <td nowrap>{{ $student->full_name }}</td>
                                                <td class="py-1" style="min-width: 200px;">
                                                    <input class="form-control" type="number" min="0" max="100" name="value[{{ $student->pivot->id }}]" value="{{ old('value[' . $student->pivot->id . ']', $plan->assessments->firstWhere('smt_id', $student->pivot->id)->value ?? null) }}" required>
                                                </td>
                                            </tr>
                                            @if ($loop->last)
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="py-1">
                                                        <button type="submit" class="btn btn-{{ $meet->props->color ?? 'primary' }} btn-block"><i class="mdi mdi-check-circle-outline"></i> Simpan</button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="4">Tidak ada data siswa</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
            <div class="col-md-5 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <i class="mdi mdi-book-outline"></i> Detail pertemuan
                    </div>
                    <div class="card-body">
                        @if ($plan->realized_at)
                            <p>Rencana <br> <strong>{{ $plan->plan_at ? \Carbon\Carbon::parse($plan->plan_at)->formatLocalized('%A, %d %B %Y') : '-' }}</strong> <br> <small class="text-muted">{{ $plan->hour }} jam pelajaran </small></p>
                            <p>
                                Kompetensi <br>
                                <strong>{{ $plan->test ? 'Ulangan' : $plan->competence->full_name ?? '-' }}</strong>
                            </p>
                            <p class="mb-0">
                                Realisasi <br>
                                <strong>{{ $plan->realized_at ? \Carbon\Carbon::parse($plan->realized_at)->isoFormat('LLLL') : '-' }}</strong>
                            </p>
                        @else
                            <form class="form-block form-confirm" action="{{ route('teacher::plan', ['plan' => $plan->id, 'next' => url()->current()]) }}" method="POST"> @csrf @method('PUT')
                                <div class="form-group mb-3">
                                    <label>Rencana</label>
                                    <input type="date" class="form-control @error('plan_at') is-invalid @enderror" name="plan_at" value="{{ old('plan_at', $plan->plan_at ? \Carbon\Carbon::parse($plan->plan_at)->format('Y-m-d') : null) }}" required>
                                    @error('plan_at')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('hour') is-invalid @enderror" name="hour" value="{{ old('hour', $plan->hour) }}" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">jam <span class="d-md-none d-lg-block">&nbsp;pelajaran</span></div>
                                        </div>
                                    </div>
                                    @error('hour')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label>Kompetensi</label>
                                    <select class="form-control @error('comp_id') is-invalid @enderror" type="number" name="comp_id" value="{{ old('comp_id', $plan->comp_id) }}" @if (old('test', $plan->test)) disabled @endif>
                                        <option value="">-- Pilih kompetensi --</option>
                                        @foreach ($meet->subject->competences as $competence)
                                            <option value="{{ $competence->id }}" @if ($plan->comp_id == $competence->id) selected @endif>{{ $competence->full_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('comp_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input bg-danger" name="test" id="test" value="1" @if (old('test', $plan->test)) checked @endif>
                                        <label class="custom-control-label" for="test">Pertemuan ini <strong><span id="test-text">
                                                    @if (!old('test', $plan->test)) tidak @endif
                                                </span> ulangan</strong></label>
                                    </div>
                                    @error('test')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-check-circle-outline"></i> Simpan</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                @include('teacher::includes.classroom-info', ['classroom' => $meet->classroom])
                @include('teacher::includes.subject-info', ['subject' => $meet->subject])
            </div>
        </div>
    @endsection

    @push('script')
        <script>
            $(() => {
                $('#test').on('change', (e) => {
                    $('#test-text').text(($('#test').is(':checked')) ? '' : 'tidak')
                    $('#test').is(':checked') ?
                        $('[name="comp_id"]').attr('disabled', 'disabled').val(null) :
                        $('[name="comp_id"]').removeAttr('disabled');
                });
                @if (!$plan->presence || request('action') == 'presence')
                    $('.clickable-radio').on('click', (e) => {
                        $(e.target).find('input[type="radio"]').prop('checked', true);
                    });
                @endif
            })
        </script>
    @endpush
