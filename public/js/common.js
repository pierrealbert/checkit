
$(function() {

    $('.open-in-modal').on('click', function(e) {
        e.preventDefault();

        var anchor = $(this).attr('href');
        var title = $(this).data('title');

        $('#modal .modal-title-container .modal-title').html(title);

        $('#modal').modal().open({
            onOpen: function(el, options){
                $.get(anchor, function(response){
                    $('#modal .modal-content-container').html(response);
                    
                    $('.modal-content-container .open-in-modal').on('click', function(e) {
                        e.preventDefault();
                        var anchor = $(this).attr('href');
                          
                        $.get(anchor, function(response){
                            $('#modal .modal-content-container').html(response);                    
                        });    
                    });                    
                });
            }            
        });
    });

    $('#modal .modal-close').on('click', function(e){
        e.preventDefault();
        $.modal().close();
        $('#modal .modal-content-container').empty();
    });

    $('.dropdown-toggle').dropdown();
});