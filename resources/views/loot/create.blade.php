@extends('layouts.default')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add loot</div>
                <div class="panel-body">
                    <form method="POST" action="/loot" accept-charset="UTF-8" class="form-horizontal" role="form" id="registerLoot">
                        {!! csrf_field() !!}
                        <div class="panel-heading" style="padding-top: 15px; padding-bottom: 5px">
                            @include('errors.list')
                            <div class="form-group">
                                <label for="adventure_id" class="control-label col-sm-2">Adventure:</label>
                                <div class="col-sm-4">
                                    <select name="adventure_id" id="adventure_id" class="form-control">
                                        @if(old('adventure_id') === null)
                                        <option value="" selected="selected">Please select an adventure</option>
                                        @endif
                                        @foreach($adventures->lists('name', 'id') as $id => $value)
                                            @if(old('adventure_id') == $id)
                                                <option value="{{ $id }}" selected>{{ $value }}</option>
                                            @else
                                                <option value="{{ $id }}">{{ $value }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body" style="margin-left: 20px">
                            <div class="row">
                                @for($id = 1; $id<= 20; $id++)
                                    @include('loot.partials.create-slot', array('partial_id' => $id))
                                @endfor
                            </div>
                            <div style="text-align: center; margin-top: 15px">
                                <input type="submit" value="Add" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#adventure_id').combobox();
        if ($('#adventure_id option:selected').val() != "")
            highlightNonSelectedFields();
    });

    $(window).unload(function() {
        //Just so we don't have an invalid state if people reloads the page.
        $('#adventure_id ').val("0");
    });

    $('#adventure_id').change(function () {
        var adventureid = $('#adventure_id option:selected').val()

        if (adventureid > 0) {
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/loot/getJSONLoot?adventure=') }}"+adventureid,
                cache: false
            }).done(function (msg) {
                lootdata = msg;
                updateLootData();
            });
        }
    });

    function updateLootData() {
        //Clear the drop downs
        $('[name*="slot"]').empty();
        $('[id*="container"]').hide();

        var lastSlot = 0;
        $(lootdata).each(function () {
            if (lastSlot != this.slot) {
                $('[name=slot' + this.slot +']').append('<option value="0">Please select loot.</option>');
                $('[name=slot' + this.slot +']').parent().addClass('has-warning');
                lastSlot = this.slot;
            }
            $('[name=slot' + this.slot +']').append('<option value="' + this.id + '">' + this.name + ' - ' + this.amount + '</option>');
        });

        for (var i = 1; i <= 20; i++) {
            if ($('[name=slot' + i +']').html() === "")
                $('#slot' + i + 'container').hide();
            else {
                $('#slot' + i + 'container').show();

                //If a dropdown has the option for Nothing, select it by default since it's the likely option to be selected.
                /* jshint ignore:start */
                $('[name=slot' + i + '] option').filter(function () {
                    if ($(this).text().indexOf('Nothing') >= 0)
                    {
                        $('[name=slot' + i + ']').parent().removeClass('has-warning');
                        return true;
                    }
                    return false;
                }).prop('selected', true);
                /* jshint ignore:end */

                //If a dropdown only has one option, select it.
                if ($('[name=slot' + i +'] option').length == 2) {
                    $('[name=slot' + i +']').val($('[name=slot' + i + '] option:last').val());
                    $('[name=slot' + i +']').parent().removeClass('has-warning');
                }
            }
        }
    }

    function highlightNonSelectedFields()
    {
        for (var i = 1; i <= 20; i++) {
            //Update highlighing
            if ($('[name=slot' + i + '] option:selected').text() == 'Please select loot.')
                $('[name=slot' + i + ']').parent().addClass('has-warning');
            else
                $('[name=slot' + i + ']').parent().removeClass('has-warning');
            //Hide any fields with no selection.
            if ($('[name=slot' + i + '] option').length == 0)
                $('#slot' + i + 'container').hide();
        }
    }
</script>
@stop