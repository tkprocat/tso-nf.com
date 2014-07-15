<!--suppress ALL -->
<table style="float: left; width: 49%" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    @foreach ($topAdventures1 as $adventure)
    <tr>
        <td><a href="#{{ str_replace(' ','',$adventure->Name); }}">{{ $adventure->Name }}</a></td>
        <td>{{ $adventure->Played }}</td>
        <td>{{ number_format($adventure->Played / $totalPlayed * 100) }}%</td>
    </tr>
    @endforeach
</table>


@if (isset($topAdventures2) && count($topAdventures2) > 1)
<table style="float: right; width: 49%" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    @foreach ($topAdventures2 as $adventure)
    <tr>
        <td><a href="#{{ str_replace(' ','',$adventure->Name); }}">{{ $adventure->Name }}</a></td>
        <td>{{ $adventure->Played }}</td>
        <td>{{ number_format($adventure->Played / $totalPlayed * 100) }}%</td>
    </tr>
    @endforeach
</table>
@endif
<div style="clear: both"></div>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="text-align: center">Total adventures registered: {{ $totalPlayed }}</th>
    </tr>
    </thead>
</table>


{{-- */ $displayed = 0; /* --}}
@foreach ($adventures as $adventure)
{{-- */ $displayed++; /* --}}
@if ($displayed % 2 == 0)
<div style="float: right; width: 49%">
    @else
    <div style="float: left; width: 49%">
        @endif
        <table class="table table-striped table-bordered" id="{{ str_replace(' ','',$adventure->Name) }}">
            <thead>
            <tr>
                <th colspan="5">
                    <div style="text-align: center">{{$adventure->Name}}</div>
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
            {{-- */ $lastSlot = 0; /* --}}
            @foreach ($adventureloot as $loot)
            @if ($loot->AdventureID == $adventure->ID)
            @if ($lastSlot != $loot->Slot)
            @if ($lastSlot > 0)
            </tbody>
            @endif
            <tbody style="border-width: 3px">
            {{-- */ $lastSlot = $loot->Slot; /* --}}
            @endif
            <tr>
                <td>{{$loot->Slot}}</td>
                <td>{{$loot->Type}}</td>
                <td>{{$loot->Amount}}</td>
                <td>{{$loot->Dropped}}</td>
                @if ($adventure->Played > 0)
                <td>{{number_format($loot->Dropped / $adventure->Played* 100)}}%</td>
                @else
                <td>0%</td>
                @endif
            </tr>
            @endif
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5"><a href="#adventuresplayed">Back to adventures played</a></td>
            </tr>
            </tfoot>
        </table>
    </div>
    @if ($displayed % 2 == 0)
    <div style="clear: both"></div>
    @endif
    @endforeach