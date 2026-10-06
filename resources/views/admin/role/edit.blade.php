@extends('admin.master.master')
@section('title','Edit Role')
@section('content')
<div class="page-header"><div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.roles.index') }}">Roles</a> / <span class="current">Edit</span></div><h1 class="page-title">Edit Role</h1><p class="page-subtitle">Update {{ $role->name }} and assigned permissions.</p></div></div>
<form action="{{ route('admin.roles.update',$role) }}" method="post">@csrf @method('PUT') @include('admin.role.form',['submitLabel'=>'Update Role'])</form>
@endsection
