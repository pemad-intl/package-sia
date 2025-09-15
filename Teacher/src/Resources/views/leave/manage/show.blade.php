@extends('teacher::layouts.default')

@section('title', 'Kelola pengajuan | ')

@section('content')
    @include('boarding::layouts.component.index_manage_submission_show', ['module' => 'teacher'])
@endsection
