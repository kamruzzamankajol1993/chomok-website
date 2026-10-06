@extends('admin.master.master')
@section('title','Add Role')
@section('content')
<div class="page-header"><div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.roles.index') }}">Roles</a> / <span class="current">Add</span></div><h1 class="page-title">Add Role</h1><p class="page-subtitle">Select all, group-wise or individual permissions.</p></div></div>
<form action="{{ route('admin.roles.store') }}" method="post">@csrf @include('admin.role.form',['submitLabel'=>'Save Role'])</form>
@endsection
