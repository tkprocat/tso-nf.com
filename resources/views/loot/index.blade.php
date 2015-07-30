@extends('layouts.default')

@section('content')
@include('errors.list')
@foreach($loots as $loot)
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-3 col-sm-2 col-xs-3">{{ $loot->created_at }}</div>
            <div class="col-lg-3 col-sm-4 col-xs-3"><strong>{{ $loot->User->username }}</strong> <i><small>({{ $loot->User->guild !== null ? $loot->User->guild->name : '' }})</small></i></div>
            <div class="col-lg-3 col-sm-3 col-xs-3"><a href="/stats/global/#{{ str_replace(' ','',$loot->Adventure->name) }}">{{ $loot->Adventure->name }}</a> <i><small>({{ $loot->Adventure->type }})</small></i></div>
            @if (Entrust::hasRole('admin') || (Auth::user()->id == $loot->User->id))
            <div class="col-lg-3 col-sm-3 col-xs-3" style="text-align: right">
                <a href="/loot/{{ $loot->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                <a href="/loot/{{ $loot->id }}" data-method="delete" rel="nofollow" class="btn btn-danger btn-sm" data-confirm="Are you sure you want to delete this loot entry?">Delete</a>
            </div>
            @else
            <div class="col-md-3"></div>
            @endif
        </div>
    </div>
    <div class="panel-body">
        {{ $loot->lootText() }}
    </div>
</div>
@endforeach
{!! $loots->render() !!}
@stop