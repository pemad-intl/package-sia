@extends('teacher::layouts.default')

@section('title', 'Data extrakulikuler - Rombel ' . $cls->name)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card bg-white border mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-badge-horizontal-outline position-absolute" style="top: 10pt; right: 40pt; font-size: 40pt;"></i>
                    <h3><strong>Rombel {{ $cls->name }}</strong></h3>
                </div>
            </div>
            <h2>
                <a class="text-decoration-none small text-primary" href=""><i class="mdi mdi-arrow-left-circle-outline"></i></a>
                Ekstrakulikuler Siswa
            </h2>


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
                    <i class="mdi mdi-account-badge-horizontal-outline"></i> siswa
                </div>
                <form class="form-block form-confirm" action="{{ route('teacher::extras', ['classroom' => $cls->id, 'next' => url()->current()]) }}" method="POST"> @csrf @method('PUT')
                    <div class="table-responsive">
                        <table class="table-bordered table-striped table-hover mb-0 table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cls->stsems as $stsem)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $stsem->student->nis }}</td>
                                        <td nowrap>{{ $stsem->student->full_name }}</td>
                                        <td><a href="{{ route('teacher::extras.index', ['classroom' => $cls->first()->id, 'student' => $stsem->id]) }}" class="btn btn-info" title="Isikan Prestasi">
                                                <i class="bx bxs-edit-alt"></i> 
                                            </a>
                                        </td>
                                    </tr>
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
            @include('teacher::includes.classroom-info', ['classroom' => $cls])
            {{-- @include('teacher::includes.subject-info', ['subject' => $meet->subject]) --}}
        </div>
    </div>
@endsection