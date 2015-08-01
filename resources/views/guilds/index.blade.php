@extends('layouts.default')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Guilds</div>
                <div class="panel-body">
                    @include('errors.list')
                    <table class="table table-striped table-hover sortable" id="guilds">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Tag</th>
                                <th>Members</th>
                                <th>Admins</th>
                                @if (Auth::check() && (Auth::user()->guild_id == 0))
                                <th>Action</th>
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
                                    <a href="{{ URL::to('/guilds/'.$guild->id.'/applications/create/') }}" class="btn btn-primary">Join</a>
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
    </div>
</div>
<script>
    $(document).ready( function () {
        $('#guilds').DataTable({
            paging: false
        });
    });
</script>
@stop