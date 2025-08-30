@extends('adminlte::page')
@section('title', 'Edit Komponen Biaya')
@section('content_header')
    <h1>Edit Komponen Biaya</h1>
@stop
@section('content')
    <livewire:admin.fee-component.fee-component-form :feeComponent="$feeComponent" />
@stop