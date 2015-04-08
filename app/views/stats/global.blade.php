@extends('layouts.default')
@section('content')
<table style="float: left; width: 48%" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    @for($i=0; $i < (count($adventures) / 2) ; $i++)
        <tr>
            <td><a href="#{{ str_replace(' ','',$adventures[$i]->name); }}">{{ $adventures[$i]->name }}</a></td>
            <td>{{ $adventures[$i]->played->count() }}</td>
            <td>{{ number_format($adventures[$i]->played->count() / $total_played * 100) }}%</td>
        </tr>
        {{--*/ $no = $i /*--}}
    @endfor
</table>

<table style="float: right; width: 48%" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    @for($i = $no+1 ; $i < count($adventures); $i++)
        <tr>
            <td><a href="#{{ str_replace(' ','',$adventures[$i]->name); }}">{{ $adventures[$i]->name }}</a></td>
            <td>{{ $adventures[$i]->played->count() }}</td>
            <td>{{ number_format($adventures[$i]->played->count() / $total_played * 100) }}%</td>
        </tr>
        {{--*/ $no = $i /*--}}
    @endfor
    @if ($no % 2 == 0)
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
    @endif
</table>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="text-align: center">Total adventures registered: {{ $total_played }}</th>
    </tr>
    </thead>
</table>

<?php $displayed = 0; ?>
@foreach ($adventures as $adventure)
<?php $displayed++; ?>
@if ($displayed % 2 == 0)
<div style="float: right; width: 49%">
    @else
    <div style="float: left; width: 49%">
        @endif
        <table class="table table-striped table-bordered" id="{{ str_replace(' ','',$adventure->name) }}">
            <thead>
            <tr>
                <th colspan="5">
                    <div style="text-align: center">{{$adventure->name}}</div>
                </th>
            </tr>
            <tr>
                <td>Slot</td>
                <td>Loot</td>
                <td>Amount</td>
                <td>Drops</td>
                <td>Drop chance</td>
            </tr>
            </thead>
            <?php $last_slot = 0; ?>
            @foreach ($adventure->loot as $loot)
            @if ($loot->adventure_id == $adventure->id)
            @if ($last_slot != $loot->slot)
            @if ($last_slot > 0)
            </tbody>
            @endif
            <tbody style="border-width: 3px">
            <?php $last_slot = $loot->slot; ?>
            @endif
            <tr>
                <td>{{$loot->slot}}</td>
                <td>{{$loot->type}}</td>
                <td>{{$loot->amount}}</td>
                <td>{{$drop_count_list[$loot->id]}}</td>
                @if ($adventure->played->count() > 0)
                <td>{{number_format($drop_count_list[$loot->id] / $adventure->played->count() * 100)}}%</td>
                @else
                <td>0%</td>
                @endif
            </tr>
            @endif
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5"><a href="#">Back to top</a> - <a href="/loot/adventure/{{ urlencode($adventure->name) }}">See latest loot</a></td>
            </tr>
            </tfoot>
        </table>
    </div>
    @if ($displayed % 2 == 0)
    <div style="clear: both"></div>
    @endif
    @endforeach
    @stop
