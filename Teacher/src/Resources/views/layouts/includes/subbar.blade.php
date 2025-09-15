@php
    use Modules\Academic\Models\AcademicSubject;    
    use Modules\Academic\Models\AcademicClassroom;

    $subjects = AcademicSubject::all();
    
    $teacher = optional(auth()->user()->teacher)->load([
        'meets' => function ($query) use ($ACSEM) {
            return $query->whereAcsemIn($ACSEM->id);
        },
    ]);

    $classRoom = \Modules\Academic\Models\AcademicClassroom::where('supervisor_id', auth()->user()->teacher->id)->first();
    //$classRoomsByLevel = $classRooms->groupBy('level_id');

    $meets = $teacher->meets ?? collect();
    $meetsBySubject = $meets->groupBy('subject_id');
    $subjects = AcademicSubject::whereIn('id', $meetsBySubject->keys())->get()->keyBy('id');

    if (empty($classRoom?->id)) {
        session()->flash('msg-gagal', 'Data kelas tidak ditemukan.');
        abort(redirect()->back());
    }
@endphp


<ul class="metismenu list-unstyled" id="side-menu">
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('teacher::home') }}"><i class="nav-icon mdi mdi-speedometer"></i> Beranda</a>
    </li>
    <li class="menu-title" key="t-menu">Pengelolaan</li>

    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;">
            <i class="nav-icon bx bx-calendar-event"></i> Jadwal
        </a>
        <ul class="sub-menu mm-collapse">
            @forelse($meetsBySubject as $subjectId => $meetsGroup)
                @php
                    $subject = $meetsGroup->first()->subject;
                @endphp

                <li class="nav-main-item">
                    <a href="javascript:;" class="has-arrow">
                        <i class="nav-icon mdi mdi-book-outline"></i> {{ $subject->name }}
                    </a>
                    <ul class="sub-menu mm-collapse">
                        @foreach($meetsGroup as $meet)
                            <li>
                                <a class="nav-main-link ms-4" href="{{ route('teacher::meet', ['meet' => $meet->id]) }}">
                                    <div class="d-inline-block rounded-circle me-2"
                                        style="background-color: {{ $meet->props->color ?? '#333' }}; width: 14px; height: 14px;"></div>
                                    Rombel - {{ $meet->classroom->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @empty
                <li>
                    <a class="nav-main-link disabled" href="javascript:;">Tidak ada pertemuan</a>
                </li>
            @endforelse
        </ul>
    </li>

    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;">
            <i class="bx bx-sort"></i> Kompetensi
        </a>
        <ul class="sub-menu mm-collapse">
            @php
                $meets = collect(optional($teacher)->meets);
                $meetsByLevel = $meets->groupBy(fn($meet) => optional($meet->classroom)->level_id);
            @endphp

            @forelse ([10, 11, 12] as $level)
                @if ($meetsByLevel->has($level))
                    <li class="nav-main-item">
                        <a class="has-arrow" href="javascript:;">
                            <i class="bx bxs-school"></i> Kelas {{ $level }}
                        </a>
                        <ul class="sub-menu mm-collapse">
                            @foreach ($meetsByLevel[$level]->pluck('subject')->unique('id') as $subject)
                                <li>
                                    <a class="nav-main-link" href="{{ route('teacher::subjects.competences.index', ['subject' => $subject->id]) }}">
                                        <i class="bx bx-book-content"></i> {{ $subject->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @empty
                <li>
                    <a class="nav-main-link disabled" href="javascript:;">Tidak ada pertemuan</a>
                </li>
            @endforelse
        </ul>
    </li>

    <li class="menu-title" key="t-menu">Report</li>

    <li class="nav-main-item">
        @forelse($meetsBySubject as $subjectId => $meetsGroup)
            @php
                $subject = $meetsGroup->first()->subject;
            @endphp
                <a href="javascript:;" class="has-arrow">
                    <i class="nav-icon mdi mdi-book-outline"></i> {{ $subject->name }}
                </a>
                <ul class="sub-menu mm-collapse">
                    @foreach($meetsGroup as $meet)
                        <li>
                            <a class="nav-main-link" href="{{ route('teacher::report', ['meet' => $meet->id]) }}">
                                <div class="d-inline-block rounded-circle me-2"
                                    style="background-color: {{ $meet->props->color ?? '#333' }}; width: 14px; height: 14px;"></div>
                                Rombel - {{ $meet->classroom->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @empty
                <li>
                    <a class="nav-main-link disabled" href="javascript:;">Tidak ada pertemuan</a>
                </li>
            @endforelse
    </li>

    <li class="menu-title" key="t-menu">Wali Kelas</li>

    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('teacher::supervisor', ['classroom' => $classRoom->id]) }}">
            <i class="bx bxs-report me-1"></i>
            Catatan - {{ $classRoom->name }}
        </a>
    </li>

    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('teacher::extras', ['classroom' => $classRoom->id]) }}">
            <i class=" bx bx-run me-1"></i>
            Ekstrakulikuler - {{ $classRoom->name }}
        </a>
    </li>

    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('teacher::achievement', ['classroom' => $classRoom->id]) }}">
            <i class="bx bx-award me-1"></i>
            Prestasi - {{ $classRoom->name }}
        </a>
    </li>

    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('teacher::recommended', ['classroom' => $classRoom->id]) }}">
            <i class="bx bx-up-arrow-alt  me-1"></i>
            Kenaikan - {{ $classRoom->name }}
        </a>
    </li>

    <li class="menu-title" key="t-menu">Perizinan</li>
    <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('teacher::leave.manage.index') }}"><i class="nav-icon  bx bx-band-aid"></i> Perizinan</a> </li>

    <li class="menu-title" key="t-menu">Khasus</li>

    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"><i class="bx bxs-bookmark"></i> Kasus</a>
        <ul class="sub-menu mm-collapse">
            <li><a class="nav-main-link" href="{{ route('teacher::case') }}"><i class="bx bxs-bookmark"></i> Input kasus baru</a></li>
        </ul>
    </li>
</ul>

@push('script')
    <script>
        $(() => {
            let u = window.location.href;
            $('#subbar').find('a').each((i, e) => {
                if (u.includes($(e).attr('href'))) {
                    $(e).hasClass('dropdown-item') ?
                        $(e).parents('.dropdown').find('.dropdown-toggle').addClass('text-white') :
                        $(e).addClass('text-white');
                }
            })
        })
    </script>
@endpush
