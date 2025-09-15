@extends('administration::layouts.default')

@section('title', 'Registrasi semester - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::scholar.semesters.index')) }}">Registrasi semester</a></li>
    <li class="breadcrumb-item active">Kenaikan kelas</li>
@endsection

@section('content')
    <h2 class="mb-4">
        <a class="text-decoration-none small" href="{{ request('next', route('administration::scholar.semesters.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i></a>
        Kenaikan kelas
    </h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-details float-left mr-2"></i>Daftar siswa</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if (session('success'))
                                <div id="flash-success" class="alert alert-success mt-4">
                                    {!! session('success') !!}
                                </div>
                            @endif

                            @if (session('danger'))
                                <div id="flash-danger" class="alert alert-danger mt-4">
                                    {!! session('danger') !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    <form id="filter-form" action="{{ route('administration::scholar.semesters.promotions') }}" method="GET">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Siswa T.A.</div>
                                </div>
                                <select class="form-control mb-sm-0 mr-sm-3 mb-2" name="acsem" required data-live-search="true">
                                    <option value="">-- Pilih tahun ajaran --</option>
                                    @foreach ($acsems->where('open', 1) as $_acsem)
                                        <option value="{{ $_acsem->id }}" @if (request('acsem', $acsem->id) == $_acsem->id) selected @endif>{{ $_acsem->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('administration::scholar.semesters.promote') }}" method="POST"> @csrf
                        <div class="form-group mb-2">
                            <select multiple="multiple" size="10" name="students[]">
                                @foreach ($stsems as $stsem)
                                    <option value="{{ $stsem->student->id }}">{{ $stsem->classroom->name ? $stsem->classroom->name . ' - ' . $stsem->student->user->profile->full_name : $stsem->student->user->profile->full_name }}</option>
                                @endforeach
                            </select>
                            @error('students.0')
                                <span class="text-danger">Siswa yang Anda pilih tidak valid</span>
                            @enderror
                        </div>
                        <div class="form-group required mb-2">
                            <label>Tahun Ajaran Baru</label>
                            <select class="form-control @error('semester_id') is-invalid @enderror" name="semester_id" required>
                                @foreach ($acsems->where('open', 1) as $_acsem)
                                    <option value="{{ $_acsem->id }}" data-classrooms="{{ $_acsem->classrooms }}" @if (old('semester_id') == $_acsem->id) selected @endif>{{ $_acsem->full_name }}</option>
                                @endforeach
                            </select>
                            @error('semester_id')
                                <small class="text-danger"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>Rombel yang dituju</label>
                            <select class="form-control @error('classroom_id') is-invalid @enderror" name="classroom_id">
                                <option value="">-- Pilih rombel --</option>
                                @foreach ($aclassRoom ?? [] as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <small class="text-danger"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="alert alert-info d-none" id="msg-alert">
                            Anda akan meregesitrasikan <strong><span id="msg-count">0</span> siswa</strong> ke kelas <strong><span id="msg-classroom"></span></strong>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a class="btn btn-secondary" href="{{ request('next', route('administration::scholar.semesters.index')) }}"> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.students.index') }}"><i class="mdi mdi-account-group-outline"></i> Data siswa</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('administration::scholar.semesters.index') }}"><i class="mdi mdi-account-group-outline"></i> Registrasi semester</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-duallistbox.min.css') }}">
    <script src="{{ asset('js/bootstrap-duallistbox.min.js') }}"></script>

    <script>
        $(() => {
            setClassrooms();

            $('[name="acsem"]').on('change', (e) => {
                $('#filter-form').submit();
            });

            $('[name="semester_id"]').on('change', (e) => {
                setClassrooms();
                setAlert();
            });

            $('[name="students[]"]').bootstrapDualListbox({
                moveOnSelect: false,
                nonSelectedListLabel: 'Siswa Tahun Ajaran <strong>{{ $acsem->full_name }}</strong>',
                selectedListLabel: 'Siswa yang dinaikkan kelasnya'
            });

            $('[name="students[]"],[name="classroom_id"]').on('change', (e) => {
                setAlert();
            })

            function setClassrooms() {
                var s = $('[name="semester_id"]');
                var c = '';
                for (i of s.children('option:selected').data('classrooms')) {
                    c += '<option value="' + i.id + '">' + i.name + '</option>'
                }
                $('[name="classroom_id"]').html(c);
            }

            function setAlert() {
                let count = $('[name="students[]"] :selected').length;
                $('#msg-count').html(count);
                $('#msg-classroom').html($('[name="classroom_id"] :selected').text() + ' - ' + $('[name="semester_id"] :selected').text());
                count ? $('#msg-alert').removeClass('d-none') : $('#msg-alert').addClass('d-none');
            }
        })
    </script>
@endpush
