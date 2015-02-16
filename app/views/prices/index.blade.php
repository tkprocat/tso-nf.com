@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
    <h4>Pricelist:</h4>

    <ul>
    @foreach($items as $item)
        <li>{{$item->name}} - {{$item->price}}</li>
    @endforeach
    </ul>
@stop
