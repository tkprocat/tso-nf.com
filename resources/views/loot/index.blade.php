@extends('layouts.default')

@section('content')
@foreach($loots as $loot)
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-3 col-sm-2 col-xs-3">{{ $loot->created_at }}</div>
            <div class="col-lg-3 col-sm-4 col-xs-3"><strong>{{ $loot->User->username }}</strong> <i><small>({{ $loot->User->guild !== null ? $loot->User->guild->name : '' }})</small></i></div>
            <div class="col-lg-3 col-sm-3 col-xs-3"><a href="/stats/global/#{{ str_replace(' ','',$loot->Adventure->name) }}">{{ $loot->Adventure->name }}</a> <i><small>({{ $loot->Adventure->type }})</small></i></div>
            @if (Entrust::hasRole('admin') || (Auth::user()->id == $loot->User->id))
            <div class="col-lg-3 col-sm-3 col-xs-3" style="text-align: right">
                <button class="btn btn-primary btn-sm"
                        onClick="location.href='{{ action('LootController@edit', array($loot->id)) }}'">Edit
                </button>
                <button class="btn btn-primary btn-deleteUserAdventure btn-sm" data-toggle="modal"
                        data-target="#deleteModal"
                        data-id="{{ $loot->id }}">Delete
                </button>
            </div>
            @else
            <div class="col-md-3"></div>
            @endif
        </div>
    </div>
    <div class="panel-body">
        {{ $loot->lootText() }}
    </div>
</div>
@endforeach
{!! $loots->render() !!}


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
            url : '/loot/'+deleteID,
            data : {
                _method: 'DELETE'
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
            }
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