@extends('layouts.default')

{{-- Content --}}
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        Lab
    </div>
    <div class="panel-body">
        Welcome to The LM Loot Tracker Lab.<br>
        We're running this section to test out various improvements and concept before replacing them with old ones. Feedback, both good and bad, are very much appreciated.<br>
        Please contact Procat ingame, either by mail or whisper.
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <a href="{{ URL::To('/labs/lab3') }}">Experiment 3</a>
    </div>
    <div class="panel-body">
        A new experiment focusing on displaying various global stats in a userfriendly way. A side project in this experiment is to make
        data exposed by json for others to use.
    </div>
</div>
@stop