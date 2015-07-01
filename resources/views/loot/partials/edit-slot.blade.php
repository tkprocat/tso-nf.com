<div class="col-lg-3 col-md-4 col-sm-6 form-group" style="padding-top: 10px" id="slot{{ $partial_id }}container">
    <label for="slot{{ $partial_id }}" class="control-label">Slot {{ $partial_id }}:</label>
    @if (isset($loot) && isset($loot[$partial_id]))
    <select name="slot{{ $partial_id }}" id="slot{{ $partial_id }}" class="form-control" style="width: 200px">
        @foreach($loot[$partial_id] as $id => $type)
            @if($useradventure->loot()->slot($partial_id)->first()->adventure_loot_id == $id)
            <option selected="selected" value="{{ $id }}">{{ $type }}</option>
            @else
            <option value="{{ $id }}">{{ $type }}</option>
            @endif
        @endforeach
    </select>
    @else
    <select name="slot{{ $partial_id }}" id="slot{{ $partial_id }}" class="form-control" style="width: 200px"></select>
    @endif
</div>