
$(function() {

    $('#modal-dialog').dialog({
        autoOpen: false,
        modal: true,
        width: 630
    });

    $('.open-in-modal').click(function(event) {
        var anchor = $(event.target);
        event.preventDefault();

        $.get(anchor.attr('href'), function(data) {
            $('#modal-dialog').html(data);
            $('#modal-dialog').dialog('open');
        });
    });
});
