@extends('errors::layout')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __($exception->getMessage() ?: 'Terjadi Kesalahan Pada Server'))
