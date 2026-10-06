@extends('admin.master.master')
@section('title','Add Permissions')
@section('content')
<div class="page-header"><div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.permissions.index') }}">Permissions</a> / <span class="current">Add</span></div><h1 class="page-title">Add Permission Group</h1><p class="page-subtitle">Enter a group first, then add permissions with the plus button.</p></div></div>
<form action="{{ route('admin.permissions.store') }}" method="post">@csrf @include('admin.permission.form',['submitLabel'=>'Save Permissions'])</form>
@endsection
