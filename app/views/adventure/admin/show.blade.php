@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ $adventure->name }}</div>
    <div class="panel-body">
        <table class="table table-striped table-hover sortable" id="adventures">
            <thead>
            <tr>
                <th>Slot</th>
                <th>Type</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($adventure->loot as $loot)
                <tr>
                    <td>{{ $loot->slot }}</td>
                    <td>{{ $loot->type }}</td>
                    <td>{{ $loot->amount }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>


<a href="{{ URL::to("/admin/adventure") }}" class="btn btn-primary">Back</a>
<a href="{{ URL::to("/admin/adventure/$adventure->id/edit") }}" class="btn btn-warning">Update</a>
@stop