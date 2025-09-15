@extends('teacher::layouts.default')

@section('title', 'Input raport - ' . $meet->subject->name . ' ' . $meet->classroom->full_name . ' - ')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card bg-{{ $meet->props->color ?? 'white border' }} mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-badge-horizontal-outline position-absolute" style="top: 10pt; right: 40pt; font-size: 40pt;"></i>
                    <h2><strong>{{ $meet->subject->name }}</strong></h3>
                        <p class="mb-0">Rombel {{ $meet->classroom->full_name }}</p>
                </div>
            </div>
            <h2>
                <a class="text-decoration-none small text-{{ $meet->props->color ?? 'primary' }}" href="{{ request('next', route('teacher::meet', ['meet' => $meet->id])) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
                Input nilai raport
            </h2>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Rincian Materi
                        </div>

                        <div class="card-body">
                            <h4 class="card-title">TP/LM Ke</h4>
                            <input type="text" class="form-control mb-3" name="tp-lm" value="{{ $subject->id }}" disabled />

                            <h4 class="card-title">Materi</h4>

                            @php
                                $materi = $subject->competences->map(fn($sbj) => $sbj->kd . '. ' . $sbj->name)->join("\n");
                                $materiDesc = $subject->competences->map(fn($sbj) => $sbj->name);

                                $materiKalimat = '';

                                if ($materiDesc->count() === 1) {
                                    $materiKalimat = $materiDesc->first();
                                } elseif ($materiDesc->count() === 2) {
                                    $materiKalimat = $materiDesc[0] . ' dan ' . $materiDesc[1];
                                } elseif ($materiDesc->count() > 2) {
                                    $last = $materiDesc->pop();
                                    $materiKalimat = $materiDesc->implode(', ') . ' dan ' . $last;
                                }
                            @endphp

                            <textarea style="height:118px;" class="form-control" name="materi">{{ $materi }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Rincian Penilaian
                        </div>

                        <div class="card-body">
                            <h4 class="card-title">Masukkan nilai ketercapaian</h4>
                            <input type="text" class="form-control mb-3" id="nilai-standar" name="nilai" value="{{ $subject->score_standard ?? '' }}" />

                            <h4 class="card-title mb-2">Rentang Nilai</h4>
                            <ul class="list-unstyled" id="rentang-nilai">
                                <li>
                                    @foreach(config('modules.teacher.report.grade') as $value)
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <h5 class="font-size-14">{{ $value['min']. '-'. $value['max'] }} <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div>
                                                    {{ $value['deskripsi'] }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </li>
                            </ul>
                        </div>  
                    </div>
                </div>

            </div>


            <hr>

            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                        <div id="flash-success" class="alert alert-success mt-4">
                            {!! session('success') !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-account-badge-horizontal-outline"></i> Penilaian siswa
                </div>
                <form class="form-block form-confirm" action="{{ route('teacher::report', ['meet' => $meet->id, 'next' => url()->current()]) }}" method="POST"> @csrf @method('PUT')
                    <div class="table-responsive">
                        <table class="table-bordered table-striped table-hover mb-0 table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Nilai KI-3 Pengetahuan</th>
                                    <th>Nilai KI-4 Keterampilan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meet->classroom->stsems as $stsem)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $stsem->student->nis }}</td>
                                        <td nowrap>{{ $stsem->student->full_name }}</td>
                                        <td class="py-1" style="min-width: 200px;">
                                            <input class="form-control mb-2 nilai-field-ki3" data-predicate-target="predicate-{{ $stsem->id }}-ki3" data-description-target="description-{{ $stsem->id }}-ki3" type="number" min="0" max="100" name="value[{{ $stsem->id }}][ki3_value]" value="{{ old("value.{$stsem->id}.ki3_value", $stsem->reports->firstWhere('subject_id', $meet->subject_id)->ki3_value ?? null) }}" required placeholder="Nilai">
                                            <input class="form-control mb-2" id="predicate-{{ $stsem->id }}-ki3" type="text" name="value[{{ $stsem->id }}][ki3_predicate]" value="{{ old("value.{$stsem->id}.ki3_predicate", $stsem->reports->firstWhere('subject_id', $meet->subject_id)->ki3_predicate ?? null) }}" required placeholder="Predikat">
                                            <input class="form-control" id="description-{{ $stsem->id }}-ki3" type="text" name="value[{{ $stsem->id }}][ki3_description]" value="{{ old("value.{$stsem->id}.ki3_description", $stsem->reports->firstWhere('subject_id', $meet->subject_id)->ki3_description ?? null) }}" required placeholder="Deskripsi">
                                        </td>
                                        <td class="py-1" style="min-width: 200px;">
                                            <input class="form-control mb-2 nilai-field-ki4" data-predicate-target="predicate-{{ $stsem->id }}-ki4" data-description-target="description-{{ $stsem->id }}-ki4" type="number" min="0" max="100" name="value[{{ $stsem->id }}][ki4_value]" value="{{ old("value.{$stsem->id}.ki4_value", $stsem->reports->firstWhere('subject_id', $meet->subject_id)->ki4_value ?? null) }}" required placeholder="Nilai">
                                            <input class="form-control mb-2" id="predicate-{{ $stsem->id }}-ki4" type="text" name="value[{{ $stsem->id }}][ki4_predicate]" value="{{ old("value.{$stsem->id}.ki4_predicate", $stsem->reports->firstWhere('subject_id', $meet->subject_id)->ki4_predicate ?? null) }}" required placeholder="Predikat">
                                            <input class="form-control" id="description-{{ $stsem->id }}-ki4" type="text" name="value[{{ $stsem->id }}][ki4_description]" value="{{ old("value.{$stsem->id}.ki4_description", $stsem->reports->firstWhere('subject_id', $meet->subject_id)->ki4_description ?? null) }}" required placeholder="Deskripsi">
                                        </td>
                                    </tr>
                                    @if ($loop->last)
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="py-1">
                                                <button type="submit" class="btn btn-{{ $meet->props->color ?? 'primary' }} btn-block">
                                                    <i class="mdi mdi-check-circle-outline"></i> Simpan
                                                </button>
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
        </div>
        <div class="col-md-4">
            @include('teacher::includes.classroom-info', ['classroom' => $meet->classroom])
            @include('teacher::includes.subject-info', ['subject' => $meet->subject])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const standarInput = document.getElementById('nilai-standar');
            const rentangList = document.getElementById('rentang-nilai');
            const originalGradeConfig = @json(config('modules.teacher.report.grade'));

            function generateDynamicGradeRange(nilaiStandar) {
                const baseNilai = parseInt(nilaiStandar);
                if (isNaN(baseNilai)) {
                    rentangList.innerHTML = '<li class="text-muted">Masukkan nilai standar terlebih dahulu</li>';
                    return;
                }

                const rangeWidths = originalGradeConfig.map(g => g.max - g.min + 1);
                const dynamicGrades = [];

                const indexB = 1;

                let startB = baseNilai;
                let endB = baseNilai + rangeWidths[indexB] - 1;

                dynamicGrades[indexB] = {
                    predikat: originalGradeConfig[indexB].predikat,
                    deskripsi: originalGradeConfig[indexB].deskripsi,
                    min: startB,
                    max: Math.min(100, endB)
                };

                let startA = dynamicGrades[indexB].max + 1;
                let endA = startA + rangeWidths[0] - 1;
                dynamicGrades[0] = {
                    predikat: originalGradeConfig[0].predikat,
                    deskripsi: originalGradeConfig[0].deskripsi,
                    min: startA,
                    max: Math.min(100, endA)
                };

                let endC = startB - 1;
                let startC = endC - rangeWidths[2] + 1;
                dynamicGrades[2] = {
                    predikat: originalGradeConfig[2].predikat,
                    deskripsi: originalGradeConfig[2].deskripsi,
                    min: Math.max(0, startC),
                    max: endC
                };

                let endD = startC - 1;
                let startD = 0;
                dynamicGrades[3] = {
                    predikat: originalGradeConfig[3].predikat,
                    deskripsi: originalGradeConfig[3].deskripsi,
                    min: startD,
                    max: Math.max(startD, endD)
                };

                // Tampilkan ke HTML
                rentangList.innerHTML = '';
                dynamicGrades.forEach(item => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <div class="d-flex mb-2">
                            <div class="flex-shrink-0 me-3">
                                <h5 class="font-size-14">${item.min}–${item.max}
                                    <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                </h5>
                            </div>
                            <div class="flex-grow-1">
                                ${item.deskripsi}
                            </div>
                        </div>
                    `;
                    rentangList.appendChild(li);
                });
            }

            // Inisialisasi awal
            generateDynamicGradeRange(standarInput.value);

            // Update saat input berubah
            standarInput.addEventListener('input', function () {
                generateDynamicGradeRange(this.value);
            });
        });

        const gradeConfig = @json(config('modules.teacher.report.grade'));
        const materiKalimat = @json($materiKalimat);
        
        function handleNilaiInput(selector) {
            document.querySelectorAll(selector).forEach(function (input) {
                input.addEventListener('input', function () {
                    const value = parseInt(this.value);
                    const predicateField = document.getElementById(this.dataset.predicateTarget);
                    const descriptionField = document.getElementById(this.dataset.descriptionTarget);

                    if (!isNaN(value)) {
                        const matched = gradeConfig.find(item => value >= item.min && value <= item.max);
                        if (matched) {
                            if (predicateField) predicateField.value = matched.predikat;
                            if (descriptionField) descriptionField.value = matched.deskripsi + ' dalam materi ' + materiKalimat;
                        } else {
                            if (predicateField) predicateField.value = '';
                            if (descriptionField) descriptionField.value = '';
                        }
                    } else {
                        if (predicateField) predicateField.value = '';
                        if (descriptionField) descriptionField.value = '';
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            handleNilaiInput('.nilai-field-ki3');
            handleNilaiInput('.nilai-field-ki4');
        });
    </script>
@endpush