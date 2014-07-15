@extends('layouts.default')

@section('content')

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Date/time</th>
        <th>User</th>
        <th>Adventure</th>
        <th>Loot</th>
        <th colspan="2">Action</th>
    </tr>
    </thead>
    @foreach($loots as $loot)
        <tr>
            <td>{{ $loot->created_at }}</td>
            <td><strong>{{ $loot->User->username }}</strong><br><i><small>{{ $loot->User->guildname }}</small></i></td>
            <td>{{ $loot->Adventure->name }}</td>
            <td>{{ $loot->lootText() }}</td>
            @if (Sentry::hasAccess('admin') || (Sentry::getUser()->id == $loot->User->id))
            <td>
                <button class="btn btn-primary"
                        onClick="location.href='{{ action('LootController@edit', array($loot->id)) }}'">Edit
                </button>
            </td>
            <td>
                <button class="btn btn-primary btn-deleteUserAdventure" data-toggle="modal" data-target="#deleteModal"
                        data-id="{{ $loot->id }}">Delete
                </button>
            </td>
            @endif
        </tr>
    @endforeach
</table>
{{ $loots->links('layouts.slider') }}
@stop