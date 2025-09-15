@extends('counseling::layouts.default')

@section('title', 'Input presensi baru - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-multiple-check-outline float-left mr-2"></i>Input presensi baru
                </div>
                <div class="card-body">
                    <form action="{{ route('counseling::presences.create') }}" method="GET">
                        <div class="input-group mb-2">
                            <select class="form-control" name="classroom" type="text" required>
                                <option value="">Pilih rombel</option>
                                @foreach ($acsem->classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" @if (request('classroom') == $classroom->id) selected @endif>{{ $classroom->full_name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="{{ route('counseling::presences.create') }}"><i class="mdi mdi-refresh"></i></a>
                                <button class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                        <small class="text-muted">Menampilkan data presensi Tahun Ajaran <strong>{{ $acsem->full_name }}</strong></small>
                    </form>
                </div>
                @isset($currentClassroom)
                    <form class="form-block form-confirm" action="{{ route('counseling::presences.store', ['next' => url()->current()]) }}" method="POST"> @csrf
                        <input type="hidden" name="classroom_id" value="{{ $currentClassroom->id }}">
                        <div class="table-responsive bg-white">
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
                                    @forelse($currentClassroom->stsems ?? [] as $stsem)
                                        @php($student = $stsem->student)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $student->nis }}</td>
                                            <td nowrap>{{ $student->full_name }}</td>
                                            @foreach ($presenceList as $k => $v)
                                                <td class="clickable-radio text-center">
                                                    <div class="custom-control custom-radio" style="margin-left: 6px;">
                                                        <input type="hidden" name="presence[{{ $stsem->id }}][student_id]" value="{{ $student->id }}">
                                                        <input type="radio"
                                                            id="presence.{{ $stsem->id . '.' . $k }}"
                                                            name="presence[{{ $stsem->id }}][type]"
                                                            class="custom-control-input"
                                                            value="{{ $k }}"
                                                            @if ($loop->first) checked @endif>

                                                        <label class="custom-control-label" for="presence.{{ $stsem->id . '.' . $k }}"></label>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                        @if ($loop->last)
                                            <tr>
                                                <td colspan="3"></td>
                                                <td class="py-1" colspan="{{ count($presenceList) }}">
                                                    <input type="datetime-local" class="form-control @error('presenced_at') is-invalid @enderror" name="presenced_at" value="{{ old('presenced_at', now()->format('Y-m-d\TH:i')) }}" required>
                                                </td>
                                            </tr>
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
                @else
                    <div class="card-body text-muted border-top">
                        Silahkan pilih rombel terlebih dahulu
                    </div>
                @endisset
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            @include('counseling::includes.employee-info', ['employee' => $user])
            @include('account::includes.account-info')
        </div>
    </div>
@endsection
