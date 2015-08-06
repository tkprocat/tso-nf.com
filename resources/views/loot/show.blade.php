@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">#{{ $userAdventure->id }} by {{ $userAdventure->user->username }}</div>
                <div class="panel-body">
                    <div class="form-group">
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Item name:</th>
                                    <th>Amount</th>
                                    <th>Avg. market price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($userAdventure->loot as $userAdventureLoot)
                                <tr>
                                    <td><a href="/prices/{{ urlencode($userAdventureLoot->loot->item->name) }}">{{ $userAdventureLoot->loot->item->name }}</a></td>
                                    <td>{{ $userAdventureLoot->loot->amount }}</td>
                                    <td>{{ $userAdventureLoot->loot->item->currentPrice->avg_price }}</td>
                                    <td>{{ $userAdventureLoot->loot->amount * $userAdventureLoot->loot->item->currentPrice->avg_price }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total:</td>
                                    <td>{{ $userAdventure->getEstimatedLootValue() }} GC</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
