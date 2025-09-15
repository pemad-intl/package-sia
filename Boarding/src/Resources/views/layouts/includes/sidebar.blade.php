<ul class="metismenu list-unstyled" id="side-menu">
    <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('administration::dashboard') }}"> <i class="nav-icon mdi mdi-speedometer"></i> Dasbor </a>
    </li>
    <li class="menu-title" key="t-menu">Pengelolaan</li>
    <li class="nav-main-item">
    <li>
        <a class="nav-main-link" href="{{ route('boarding::facility.student.index') }}"> <i class="nav-icon mdi mdi-book-outline"></i> Asrama Siswa</a>
    </li>

    <li>
        <a class="nav-main-link" href="{{ route('boarding::leave.manage.index') }}"> <i class="nav-icon mdi mdi-logout"></i> Izin Siswa</a>
    </li>

    <li>
        <a class="nav-main-link" href="{{ route('boarding::event.event-student.index') }}"> <i class="nav-icon mdi mdi-calendar-edit"></i> Kegiatan Siswa</a>
    </li>
    </li>

    <li class="menu-title" key="t-menu">Pengelolaan</li>
    <li class="nav-main-item">
    <li>
        <a class="nav-main-link" href="{{ route('boarding::event.event-reference.index') }}"> <i class="nav-icon mdi mdi-calendar"></i> Daftar Kegiatan</a>
    </li>
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
    {{-- <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:void(0)"> <i class="nav-icon mdi mdi-book-outline"></i> Referensi</a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a class="nav-main-link" href="{{ route('administration::curriculas.subjects.index') }}"> <i class="nav-icon mdi mdi-book-outline"></i> Mapel</a>
            </li>
            <li>
                <a class="nav-main-link" href="{{ route('administration::curriculas.meets.index') }}"> <i class="nav-icon mdi mdi-book-outline"></i> Pertemuan</a>
            </li>
        </ul>
    </li> --}}
</ul>
