@extends('admin.master.master')

@section('title', 'Add Menu Item')

@section('content')
@include('admin.menu-item.form', ['menuItem' => null])
@endsection
