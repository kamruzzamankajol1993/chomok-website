@extends('admin.master.master')

@section('title', 'Edit Menu Item')

@section('content')
@include('admin.menu-item.form', ['menuItem' => $menuItem])
@endsection
