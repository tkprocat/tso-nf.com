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
                <td><strong>{{ $loot->User->username }}</strong><br><i>
                        <small>{{ $loot->User->guildname }}</small>
                    </i></td>
                <td>{{ $loot->Adventure->name }}</td>
                <td>{{ $loot->lootText() }}</td>
                @if (Sentry::hasAccess('admin') || (Sentry::getUser()->id == $loot->User->id))
                    <td>
                        <button class="btn btn-primary"
                                onClick="location.href='{{ action('LootController@edit', array($loot->id)) }}'">Edit
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-deleteUserAdventure" data-toggle="modal"
                                data-target="#deleteModal"
                                data-id="{{ $loot->id }}">Delete
                        </button>
                    </td>
                @endif
            </tr>
        @endforeach
    </table>
    {{ $loots->links('layouts.slider') }}


    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                    <input type="hidden" id="deleteItemID">

                    <h2>Please confirm deletion</h2>

                    <h3 id="deleteItemName"></h3>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">
                        Cancel
                    </button>
                    <button id="deleteItem" class="btn btn-danger">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var deleteID = 0;
        $('.btn-deleteUserAdventure').click(function(event) {
            deleteID = event.target.getAttribute('data-id');
        });

        $('#deleteItem').click(function(event) {
            ajaxManager.addReq({
                type : 'POST',
                url : '/loot/',
                data : {
                    _method: 'DELETE',
                    id : deleteID,
                },
                statusCode : {
                    401 : function() {
                        self.location = "/loot/";
                        return false;
                    }
                },
                success : function() {
                    $("table").find("[data-id='" + deleteID + "']").closest('tr').remove();
                    $('#deleteModal').modal('hide');
                },
                error : function(msg, status) {
                    if (status != 'error')
                        alert('Update failed.');
                },
            });
        });

        var ajaxManager = ( function() {
            var requests = [];

            return {
                addReq : function(opt) {
                    requests.push(opt);
                },
                removeReq : function(opt) {
                    if ($.inArray(opt, requests) > -1)
                        requests.splice($.inArray(opt, requests), 1);
                },
                run : function() {
                    var self = this;
                    var oriSuc;

                    if (requests.length) {
                        oriSuc = requests[0].complete;

                        requests[0].complete = function() {
                            if ( typeof oriSuc === 'function')
                                oriSuc();
                            requests.shift();
                            self.run.apply(self, []);
                        };

                        $.ajax(requests[0]);
                    } else {
                        self.tid = setTimeout(function() {
                            self.run.apply(self, []);
                        }, 1000);
                    }
                },
                stop : function() {
                    requests = [];
                    clearTimeout(this.tid);
                }
            };
        }());
        ajaxManager.run();
    </script>
@stop