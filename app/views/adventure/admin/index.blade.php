@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
    @parent
    Home
@stop

{{-- Content --}}
@section('content')
<h4>Adventures</h4>

<table class="table table-striped table-hover sortable" id="adventures">
    <thead>
        <tr>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach($adventures as $adventure)
        <tr>
            <td>{{ link_to("/admin/adventure/$adventure->id", $adventure->name) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop