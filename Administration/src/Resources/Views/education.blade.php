@extends('layouts.guest')

@section('content')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Kelola Administrasi</h5>
                                        <p>Silahkan Pilih Jenjang Pendidikan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0"> 
                            <div>
                                <a href="index.html">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('skote/images/bw.png') }}" alt="" class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                <form action="index.html">
        
                                    <div class="user-thumb text-center mb-4">
                                        <img src="{{ Auth::user()->profile_avatar_path }}" class="rounded-circle img-thumbnail avatar-md" alt="thumbnail">
                                        <h5 class="font-size-15 mt-3">Maria Laird</h5>
                                    </div>
        
                                    <div class="col-sm-12 mb-4">
                                        <div class="btn-group w-100" role="group" aria-label="Jenjang">
                                            <input type="radio" class="btn-check" name="jenjang" id="btnSMP" value="SMP" autocomplete="off" checked>
                                            <label class="btn btn-outline-primary btn-rounded" for="btnSMP">
                                                <i class="mdi mdi-school me-1"></i> SMP
                                            </label>

                                            <input type="radio" class="btn-check" name="jenjang" id="btnSMA" value="SMA" autocomplete="off">
                                            <label class="btn btn-outline-success btn-rounded" for="btnSMA">
                                                <i class="mdi mdi-school-outline me-1"></i> SMA
                                            </label>
                                        </div>
                                    </div>

                                </form>
                            </div>
        
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>Berubah Pikiran ? <a href="{{ route('portal::dashboard-msdm.index') }}" class="fw-medium text-primary"> Kembali Ke Portal </a> </p>
                        <p>© <script>document.write(new Date().getFullYear())</script> DigiBoard. Crafted with <i class="mdi mdi-heart text-danger"></i> by DigiPemad</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
