@extends('boarding::layouts.default')

@section('title', 'Dasbor - ')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dasbor</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="jumbotron bg-light p-2">
                <h2>Assalamu'alaikum {{ auth()->user()->name }}!</h2>
                <p class="text-muted">Selamat datang di Digi-Boarding</p>
            </div>
        </div>
        <div class="col-md-4">
            @include('account::includes.account-info')
        </div>
    </div>
@endsection
