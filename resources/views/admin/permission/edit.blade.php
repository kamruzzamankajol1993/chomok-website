@extends('admin.master.master')
@section('title','Edit Permission Group')
@section('content')
<div class="page-header"><div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.permissions.index') }}">Permissions</a> / <span class="current">Edit</span></div><h1 class="page-title">Edit Permission Group</h1><p class="page-subtitle">Update the group and add or remove permission rows.</p></div></div>
<form action="{{ route('admin.permissions.update-group',['group'=>$group]) }}" method="post">@csrf @method('PUT') @include('admin.permission.form',['submitLabel'=>'Update Permissions'])</form>
@endsection
