$(document).ready(function() {


    $(document).on("click", ".confirm", function(e) {

        e.preventDefault();

        var href = $(this).attr("href");

        alertify.confirm("Press OK to proceed.", function(confirmed) {
            if (confirmed) {
                window.location = href;
            }
        });

    });

    $(document).on("change", ".resultSelect", function() {

        var resultSelected = Boolean($(this).val())
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

    $("input[type=number]").numeric();

});
