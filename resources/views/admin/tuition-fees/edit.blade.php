@extends('adminlte::page')
@section('title', 'Edit Biaya Kuliah')
@section('content_header')
    <h1>Edit Biaya Kuliah</h1>
@stop
@section('content')
    <livewire:admin.tuition-fee.tuition-fee-form :tuitionFee="$tuitionFee" />
@stop