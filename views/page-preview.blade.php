@extends('admin-ui::layout.box')

@section('box-header')
    Page Preview: {{ $page->name }}
@overwrite

@section('box-content')
    <iframe src="{{$page->url}}" style="min-height:700px;border:0;width:100%"></iframe>
@overwrite