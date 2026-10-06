@extends('admin.master.master')
@section('title', 'Edit Branch')
@section('content')
<div class="page-header"><div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.branches.index') }}">Branches</a> / <span class="current">Edit</span></div><h1 class="page-title">Edit Branch</h1><p class="page-subtitle">Update {{ $branch->name }}.</p></div></div>
<form method="post" enctype="multipart/form-data" action="{{ route('admin.branches.update', $branch) }}">@csrf @method('PUT') @include('admin.branch.form', ['submitLabel'=>'Update Branch'])</form>
@endsection
