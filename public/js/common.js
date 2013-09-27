
$(function() {

    $('.open-in-modal').click(function(e) {
        e.preventDefault();

        var anchor = $(this).attr('href');

        $('#modal').modal().open({
            onOpen: function(el, options){
                $.get(anchor, function(response){
                    $('#modal .modal-content-container').html(response);
                });
            }
        });

    });

    $('#modal .modal-close').on('click', function(e){
        e.preventDefault();
        $.modal().close();
        $('#modal .modal-content-container').empty();
    });
});