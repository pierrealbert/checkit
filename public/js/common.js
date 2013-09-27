
$(function() {

    $('#modal-dialog').dialog({
        autoOpen: false,    
        modal: true,
        buttons: {
        },
        close: function() {
        }
    });

    $('.open-in-modal').click(function(event) {        
        var anchor = $(event.target);
        event.preventDefault();
        
        $.get(anchor.attr('href'), function(data) {
            $('#modal-dialog').html(data);                 
            $('#modal-dialog').dialog('open');
        });
    });
    
    $('#login-button').click(function(event) { 
        
        alert('ddd');
         event.preventDefault();
         $.post('/login', function(data) {
             $('#modal-dialog').html(data);
             $('#modal-dialog').dialog('open');
         });        
     });    
});
