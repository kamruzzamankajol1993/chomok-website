@extends('admin.master.master')
@section('title','Edit User')
@section('content')
<div class="page-header"><div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.users.index') }}">Users</a> / <span class="current">Edit</span></div><h1 class="page-title">Edit User</h1><p class="page-subtitle">Update {{ $user->name }} and access settings.</p></div></div>
<form method="post" action="{{ route('admin.users.update',$user) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.user.form',['submitLabel'=>'Update User'])</form>
@endsection
