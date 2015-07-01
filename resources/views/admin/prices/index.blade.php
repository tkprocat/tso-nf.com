@extends('layouts.default-admin')

@section('content')
@include('admin.menu', array('active' => 'Prices'))
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1 class="page-header">Prices</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover sortable" id="adventures">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Min. price</th>
                        <th>Avg. price</th>
                        <th>Max. price</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td><a href="{{ url("/admin/prices/$item->id") }}">{{ $item->name }}</a></td>
                            <td>{{ $item->current_price()->min_price }}</td>
                            <td>{{ $item->current_price()->avg_price }}</td>
                            <td>{{ $item->current_price()->max_price }}</td>
                            <td>
                            <td><a href="{{ url("/admin/prices/$item->id/newprice") }}" class="btn btn-primary">Change price</a></td>
                            <td><a href="{{ url("/admin/prices/$item->id/edit") }}" class="btn btn-warning">Update item</a></td>
                            <button class="btn btn-danger btn-delete" data-toggle="modal"
                                    data-target="#deleteModal"
                                    data-id="{{ $item->id }}">Delete
                            </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
    $('.btn-delete').click(function(event) {
        deleteID = event.target.getAttribute('data-id');
    });

    $('#deleteItem').click(function() {
        ajaxManager.addReq({
            type : 'POST',
            url : '/admin/prices/'+deleteID,
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