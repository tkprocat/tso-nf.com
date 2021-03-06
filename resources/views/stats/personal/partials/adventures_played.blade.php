<?php $displayed = 0; ?>
<table style="float: left; width: 49%" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    <tbody>
    {{--*/ $no = 0 /*--}}
    @foreach($adventures->slice(0, count($adventures)/2) as $adventure)
        <tr>
            <td><a href="#{{ str_replace(' ','',$adventure->name) }}">{{ $adventure->type . ' - ' . $adventure->name }}</a></td>
            <td>{{ $adventure->played->count() }}</td>
            <td>{{ number_format($adventure->played->count() / $total_played * 100) }}%</td>
        </tr>

        {{--*/ $no++ /*--}}
    @endforeach
    </tbody>
</table>

@if (count($adventures) > 1)
<table style="float: right; width: 49%" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    <tbody>
    @foreach($adventures->slice($no, count($adventures)/2) as $adventure)
        <tr>
            <td><a href="#{{ str_replace(' ','',$adventure->name) }}">{{ $adventure->type . ' - ' . $adventure->name }}</a></td>
            <td>{{ $adventure->played->count() }}</td>
            <td>{{ number_format($adventure->played->count() / $total_played * 100) }}%</td>
        </tr>

        {{--*/ $no++ /*--}}
    @endforeach
    @if ($no % 2 != 0)
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
    @endif
    </tbody>
</table>
@endif

<div class="clearfix"></div>
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
                    <div style="text-align: center">{{$adventure->type}} - {{$adventure->name}}</div>
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
            @if ($last_slot != $loot->slot)
            @if ($last_slot > 0)
            </tbody>
            @endif
            <tbody style="border-width: 3px">
            <?php $last_slot = $loot->slot; ?>
            @endif
            <tr>
                <td>{{$loot->slot}}</td>
                <td>{{$loot->name}}</td>
                <td>{{$loot->amount}}</td>
                <td>{{ (array_key_exists($loot->id, $drop_count_list) ? $drop_count_list[$loot->id] : '0') }}</td>
                <td>{{ (array_key_exists($loot->id, $drop_count_list) ? number_format($drop_count_list[$loot->id] / $adventure->played->count() * 100) : '0')}}%</td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5"><a href="#">Back to top</a> - <a href="/loot/{{$username}}/{{urlencode($adventure->name)}}">See latest loot</a></td>
            </tr>
            </tfoot>
        </table>
    </div>
    @if ($displayed % 2 == 0)
    <div style="clear: both"></div>
    @endif
    @endforeach