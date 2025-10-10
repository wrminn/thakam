@extends('admin.layout')
@section('title','เนื้อหา')
@section('content')
    เนื้อหา
    <h1> {{$name}}</h1>
    @foreach ($ct as $item)
    @if($item["status"] == true)
       <h2>{{$item["title"]}}</h2> 
       <p>{{$item["t1"]}}</p>
    @endif
    @endforeach
@endsection