@extends('academic::layouts.default')

@section('title', 'Data konseling - ')

@section('content')

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Asrama</h4>
                <div class="text-center">
                    <div class="avatar-sm mx-auto mb-4">
                        <span class="avatar-title rounded-circle bg-primary-subtle font-size-24">
                                <i class="mdi mdi-domain text-primary"></i>
                            </span>
                    </div>
                    <p class="font-16 text-muted mb-2"></p>
                    <h5><a href="javascript: void(0);" class="text-dark">Asrama</h5>
                    <p class="text-muted">Informasi Asrama anda</p>
                    <a href="javascript: void(0);" class="text-primary font-16"></a>
                </div>
                <div class="row mt-4">
                    <div class="col-4">
                        <div class="social-source text-center mt-3">
                            <div class="avatar-xs mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-primary font-size-16">
                                        <i class="mdi mdi-office-building text-white"></i>
                                    </span>
                            </div>
                            <h5 class="font-size-15">Asrama</h5>
                            <p class="text-muted mb-0">{{$boardStatus->room->building->name}}</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="social-source text-center mt-3">
                            <div class="avatar-xs mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-info font-size-16">
                                        <i class="mdi mdi-alpha-j-box text-white"></i>
                                    </span>
                            </div>
                            <h5 class="font-size-15">Jenis Asrama</h5>
                            <p class="text-muted mb-0">
                                @if($boardStatus->room->building->id == 1)
                                     <span class="badge badge-pill badge-soft-danger font-size-11">Putri</span>
                                @else
                                     <span class="badge badge-pill badge-soft-success font-size-11">Putra</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="social-source text-center mt-3">
                            <div class="avatar-xs mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-pink font-size-16">
                                        <i class="mdi mdi-card-account-details text-white"></i>
                                    </span>
                            </div>
                            <h5 class="font-size-15">Kepala Asrama</h5>
                            <p class="text-muted mb-0">{{$boardStatus->employee->user->name}}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Ruang</h4>
                <div class="text-center">
                    <div class="avatar-sm mx-auto mb-4">
                        <span class="avatar-title rounded-circle bg-primary-subtle font-size-24">
                                <i class="bx bx-bed text-primary"></i>
                            </span>
                    </div>
                    <p class="font-16 text-muted mb-2"></p>
                    <h5><a href="javascript: void(0);" class="text-dark">Nama Ruang </a></h5>
                    <p class="text-muted">Informasi Ruang Anda.</p>
                    <a href="javascript: void(0);" class="text-primary font-16"></a>
                </div>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="social-source text-center mt-3">
                            <div class="avatar-xs mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-primary font-size-16">
                                        <i class="mdi mdi-office-building text-white"></i>
                                    </span>
                            </div>
                            <h5 class="font-size-15">Nama Ruang</h5>
                            <p class="text-muted mb-0">{{$boardStatus->room->name}}</p>
                        </div>
                    </div>

                     <div class="col-6">
                        <div class="social-source text-center mt-3">
                            <div class="avatar-xs mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-pink font-size-16">
                                        <i class="mdi mdi-human-capacity-increase text-white"></i>
                                    </span>
                            </div>
                            <h5 class="font-size-15">Kapasitas</h5>
                            <p class="text-muted mb-0">{{$boardStatus->room->capacity}}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Penghuni Kamar</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Penghuni</a></li>
                        <li class="breadcrumb-item active">Daftar Penghuni</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-3 col-sm-6">
            @foreach($boardFriends as $friend)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="text-lg-center">
                                <div class="avatar-sm me-3 mx-lg-auto mb-3 mt-1 float-start float-lg-none">
                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-16">
                                        {{ \Illuminate\Support\Str::substr($friend->student->user->name, 0, 1) }}
                                    </span>
                                </div>
                                {{-- <a href="javascript: void(0);" class="text-muted">{{$friend->student->user->email}}</a> --}}
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div>
                                <h5 class="mb-1 font-size-15 text-truncate">{{$friend->student->user->name}}</h5>
                                <h5 class="text-muted font-size-10 mb-4 mb-lg-5">{{$friend->student->user->email ?? 'tidak ada email'}}</h5>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            @endforeach
        </div>
        
    </div>
    <!-- end row -->
</div>
@endsection