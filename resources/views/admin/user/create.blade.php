@extends('admin.master.master')
@section('title','Add User')
@section('content')
<div class="page-header"><div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.users.index') }}">Users</a> / <span class="current">Add</span></div><h1 class="page-title">Add User</h1><p class="page-subtitle">Create a user and assign branch access and a role.</p></div></div>
<form method="post" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">@csrf @include('admin.user.form',['submitLabel'=>'Save User'])</form>
@endsection
