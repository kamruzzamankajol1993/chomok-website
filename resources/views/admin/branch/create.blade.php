@extends('admin.master.master')
@section('title', 'Add Branch')
@section('content')
<div class="page-header"><div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.branches.index') }}">Branches</a> / <span class="current">Add</span></div><h1 class="page-title">Add Branch</h1><p class="page-subtitle">Create a new restaurant location.</p></div></div>
<form method="post" enctype="multipart/form-data" action="{{ route('admin.branches.store') }}">@csrf @include('admin.branch.form', ['submitLabel'=>'Save Branch'])</form>
@endsection
