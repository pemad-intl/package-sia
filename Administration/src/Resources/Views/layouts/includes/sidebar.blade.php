<ul class="metismenu list-unstyled" id="side-menu">
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('administration::dashboard') }}"> <i class="nav-icon mdi mdi-speedometer"></i> Dasbor </a>
    </li>
    <li class="menu-title" key="t-menu">Administrasi</li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:void(0)"> <i class="nav-icon mdi mdi-account-group-outline"></i> Kesiswaan</a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a class="nav-main-link" href="{{ route('administration::scholar.classrooms.index') }}"> <i class="nav-icon mdi mdi-account-group-outline"></i> Rombel</a>
            </li>
            <li>
                <a class="nav-main-link" href="{{ route('administration::scholar.students.index') }}"> <i class="nav-icon mdi mdi-account-group-outline"></i> Data siswa</a>
            </li>
            <li>
                <a class="nav-main-link" href="{{ route('administration::scholar.semesters.index') }}"> <i class="nav-icon mdi mdi-account-group-outline"></i> Registrasi semester</a>
            </li>
        </ul>
    </li>
    {{-- <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:void(0)"> <i class="nav-icon mdi mdi-account-circle-outline"></i> Kepegawaian</a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a class="nav-main-link" href="{{ route('administration::employees.teachers.index') }}"> <i class="nav-icon mdi mdi-account-circle-outline"></i> Data guru</a>
            </li>
            <li>
                <a class="nav-main-link disabled" href="javascript:;"> <i class="nav-icon mdi mdi-account-circle-outline"></i> Guru BK</a>
            </li>
        </ul>
    </li> --}}
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:void(0)"> <i class="nav-icon mdi mdi-book-outline"></i> Kurikulum</a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a class="nav-main-link" href="{{ route('administration::curriculas.subjects.index') }}"> <i class="nav-icon mdi mdi-book-outline"></i> Mapel</a>
            </li>
            <li>
                <a class="nav-main-link" href="{{ route('administration::curriculas.meets.index') }}"> <i class="nav-icon mdi mdi-book-outline"></i> Pertemuan</a>
            </li>
        </ul>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:void(0)"> <i class="nav-icon mdi mdi-office-building"></i> Sarpras</a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a class="nav-main-link" href="{{ route('administration::facility.buildings.index') }}"> <i class="nav-icon mdi mdi-office-building"></i> Gedung</a>
            </li>
            <li>
                <a class="nav-main-link" href="{{ route('administration::facility.rooms.index') }}"> <i class="nav-icon mdi mdi-office-building"></i> Ruang</a>
            </li>
            {{-- <li>
                <a class="nav-main-link" href="{{ route('administration::facility.assets.index') }}"> <i class="nav-icon mdi mdi-office-building"></i> Aset</a>
            </li> --}}
        </ul>
    </li>
    <li class="nav-main-item">
        @if(auth()->user()->employee->grade_id == 4)
            <a class="nav-main-link" href="{{ route('administration::bill.students.index', ['education' => 4]) }}"> <i class="nav-icon mdi mdi mdi-google-classroom"></i> Tagihan Siswa SMP</a>
        @else
            <a class="nav-main-link" href="{{ route('administration::bill.students.index', ['education' => 5]) }}"> <i class="nav-icon mdi mdi mdi-google-classroom"></i> Tagihan Siswa SMA</a>
        @endif
    </li>
    <li class="menu-title" key="t-menu">Basis data</li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('administration::database.academics.index') }}"> <i class="nav-icon mdi mdi-school-outline"></i> Tahun ajaran</a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('administration::database.curriculas.index') }}"> <i class="nav-icon mdi mdi-book-cog-outline"></i> Data kurikulum</a>
    </li>
    <li>
        <a class="nav-main-link" href="{{ route('administration::bill.references.index') }}"> <i class="nav-icon  mdi mdi-playlist-plus"></i> Referensi Tagihan</a>
    </li>
    <li>
        <a class="nav-main-link" href="{{ route('administration::bill.batchs.index') }}"> <i class="nav-icon mdi mdi-registered-trademark"></i> Gelombang</a>
    </li>
    <li class="nav-main-item nav-dropdown">
        {{-- <a class="nav-main-link" href="javascript:void(0)"> <i class="nav-icon mdi mdi-puzzle-outline"></i> Kelola</a> --}}
        <ul class="sub-menu mm-collapse">
            @can('access', User::class)
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('administration::database.manage.users.index') }}"> <i class="nav-icon mdi mdi-puzzle-outline"></i> Pengguna</a>
                </li>
            @endcan
            @can('access', Role::class)
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('administration::database.manage.roles.index') }}"> <i class="nav-icon mdi mdi-puzzle-outline"></i> Peran</a>
                </li>
            @endcan
        </ul>
    </li>
</ul>
