/*
 used by match edit screen to disable player scorecard fields as required
 */
function toggleDisabled(rowId, disable, className)
{
    //console.log(rowId);
    var row = $('#playerRow' + rowId);
    return row.find(className).prop('disabled', disable);
}

function showErrors(errors)
{
    var errorsFlash = $("#scorecardErrors");

    var ul = errorsFlash.find("ul");

    var html = "";
    for (i=0; i < errors.length; i++) {
        html += "<li>" + errors[i] + "</li>";
    }

    ul.html(html);

    errorsFlash.show();
}

function clearErrorMessages()
{
    var errorsFlash = $("#scorecardErrors");
    errorsFlash.find("ul").html("");
    errorsFlash.hide();
}

function clearErrors(selector)
{
    $(selector).removeClass("form-error");
}

function hasDuplicatePlayers()
{
    var playerIds = [],
        playerSelects = $(".playerSelect"),
        hasDuplicates = false;

    for (i=0; i < playerSelects.length; i++) {

        var select = $(playerSelects[i]);
        var playerId = select.val();

        if (!playerId) {
            continue;
        }

        if (playerIds.indexOf(playerId) > -1) {
            select.addClass('form-error');
            hasDuplicates = true;
        } else {
            playerIds.push(playerId);
            select.removeClass('form-error');
        }
    }

    return hasDuplicates;

}


$(document).ready(function() {

    //attach datetimepicker plugin
    $('.datetimepicker').datetimepicker({
        format: 'Y-m-d H:i'
    });

    //disable fields as required on load of match edit screen
    $('.resultSelect').trigger('change');
    $('.playerSelect').trigger('change');


    //generic alertify confirm prompt
    $(document).on("click", ".confirm", function(e) {

        e.preventDefault();

        var href = $(this).attr("href");

        alertify.confirm("Press OK to proceed.", function(confirmed) {
            if (confirmed) {
                window.location = href;
            }
        });

    });

    //change handlers for fields on result edit page to disable fields as required
    $(document).on("change", ".resultSelect", function() {

        var resultSelected = Boolean($(this).val());
        return $(".disableWithoutResult").prop("disabled", !resultSelected);

    });
    $(document).on("change", ".playerSelect", function() {

        var playerSelected = Boolean($(this).val());
        var rowId = $(this).data("rowid");
        toggleDisabled(rowId, !playerSelected, '.disableWithoutPlayer');

        $(".dnb").trigger("change");

    });
    $(document).on("change", ".dnb", function() {

        if ($(this).prop('disabled')) {
            return;
        }

        var checked = $(this).is(":checked");
        var rowId = $(this).data("rowid");
        toggleDisabled(rowId, checked, '.disableIfDnb');

    });

    //attach numeric plugin to number fields to prevent entry of non-numeric characters
    $("input[type=number]").numeric();

    //scorecard validation
    $(document).on("submit", "form.edit-match", function(e) {

        e.preventDefault();
        var form = $(this);

        errors = [];

        if (hasDuplicatePlayers()) {
            errors.push("You can't add a player to the scorecard more than once");
            return alertify.error("You can't add a player to the scorecard more than once");
        }

        /*if (errors.length > 0) {
            showErrors(errors);
            return alertify.error("Please address the errors");
        }

        clearErrorMessages();*/

        form[0].submit();

    });

});
