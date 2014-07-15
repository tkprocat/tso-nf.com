var loot1selected = "";
var loot2selected = "";
var loot3selected = "";
var loot4selected = "";
var loot5selected = "";
var loot6selected = "";
var loot7selected = "";
var loot8selected = "";
var lootamount1selected = "";
var lootamount2selected = "";
var lootamount3selected = "";
var lootamount4selected = "";
var lootamount5selected = "";
var lootamount6selected = "";
var lootamount7selected = "";
var lootamount8selected = "";
var lootdata = null;

$(document).ready(function () {
    //Validate form submission.
    if ($('[name="adventure"] option:selected').text() === '') {
        $('#slot1container').hide();
        $('#slot2container').hide();
        $('#slot3container').hide();
        $('#slot4container').hide();
        $('#slot5container').hide();
        $('#slot6container').hide();
        $('#slot7container').hide();
        $('#slot8container').hide();
    } else {
        adventureid = $('[name="adventure"]').val();
        $.ajax({
            type: "POST",
            cache: false,
            url: "{{ URL::to('/getJSONLootLab') }}",
            data: {
                adventure: $('[name="adventure"] option:selected').val()
            }
        }).done(function (msg) {
            lootdata = msg;
            updateLootData();
        });
    }
});

$('[name="adventure"]').change(function (element) {
    adventureid = $('[name="adventure"]').val();
    $.ajax({
        type: "POST",
        url: "{{ URL::to('/getJSONLootLab') }}",
        cache: false,
        data: {
            adventure: $('[name="adventure"] option:selected').val()
        }
    }).done(function (msg) {
        lootdata = msg;
        updateLootData();
    });
});

function updateLootData() {
    //Clear the drop downs
    $('#slot1options').empty();
    $('#slot2options').empty();
    $('#slot3options').empty();
    $('#slot4options').empty();
    $('#slot5options').empty();
    $('#slot6options').empty();
    $('#slot7options').empty();
    $('#slot8options').empty();

    var lastSlot = 0;
    $(lootdata).each(function () {
        if (lastSlot != this.Slot) {
            $('#slot' + this.Slot + 'options').append('<option value="0">Please select loot.</option>');
            lastSlot = this.Slot;
        }
        $('#slot' + this.Slot + 'options').append('<option value="'+this.ID+'">'+this.Type+' - '+this.Amount+'</option>');
    });

    for (var i = 1; i < 9; i++) {
        if ($('#slot' + i + 'options').html() === "")
            $('#slot' + i + 'container').hide();
        else {
            $('#slot' + i + 'container').show();

            //If a dropdown has the option for Nothing, select it by default since it's the likely option to be selected.
            /* jshint ignore:start */
            $('#slot'+i+'options option').filter(function() {
                return ($(this).text().indexOf('Nothing') >= 0);
            }).prop('selected', true);
            /* jshint ignore:end */

            //If a dropdown only has one option, select it.
            if ($('#slot'+i+'options option').length == 2)
                $('#slot'+i+'options').val($('#slot'+i+'options option:last').val());
        }
    }
}

function updateValidationRules() {
    //Clear any old rules.
    $('#registerLoot').removeData('validator');
    $("#registerLoot").validate();

    for (var i = 0; i <= 8; i++) {
        if ($('#slot' + i + 'container').is(":visible")) {
            $('[name="slot' + i + 'options"]').rules("add", { slotRequired: true });
        }
    }
}