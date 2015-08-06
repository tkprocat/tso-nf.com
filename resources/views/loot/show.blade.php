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
                                    <td style="text-align: right">{{ number_format($userAdventureLoot->loot->amount) }}</td>
                                    <td style="text-align: right">{{ preg_replace("/\.?0*$/",'', number_format($userAdventureLoot->loot->item->currentPrice->avg_price, 3)) }}</td>
                                    <td style="text-align: right">{{ preg_replace("/\.?0*$/",'', number_format($userAdventureLoot->loot->amount * $userAdventureLoot->loot->item->currentPrice->avg_price,3)) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total:</td>
                                    <td style="text-align: right">{{ preg_replace("/\.?0*$/",'', number_format($userAdventure->getEstimatedLootValue(), 3)) }} GC</td>
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
