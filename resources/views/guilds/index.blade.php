@extends('layouts.default')

{{-- Content --}}
@section('content')
<h4>Guilds:</h4>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-hover sortable" id="users">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Tag</th>
                    <th>Members</th>
                    <th>Admins</th>
                    @if (Auth::check() && (Auth::user()->guild_id == 0))
                    <th></th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($guilds as $guild)
                <tr>
                    <td><a href="{{ URL::to('/guilds/'.$guild->id) }}">{{ $guild->name }}</a></td>
                    <td>{{ $guild->tag }}</td>
                    <td>{{ $guild->members()->count() }}</td>
                    <td>
                    @for($i = 0; $i < count($guild->admins()); $i++)
                        @if (count($guild->admins()) > $i+1)
                                {{ $guild->admins()[$i]->username }},
                        @else
                                {{ $guild->admins()[$i]->username }}
                        @endif

                    @endfor
                    </td>
                    @if (Auth::check() && (Auth::user()->guild_id == 0))
                    <td>
                    <a href="{{ URL::to('/guilds/applications/create/'.$guild->id) }}" class="btn btn-primary">Join</a>
                    </td>
                    @endif
                </tr>
                @endforeach
                </tbody>
                @if ((Auth::check()) && (Auth::user()->guild_id == 0))
                <tfoot>
                    <tr><td colspan="5"><a href="{{ URL::to('/guilds/create') }}" class="btn btn-primary">Create guild</a></td></tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@stop