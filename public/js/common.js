
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

    $('.open-in-modal.gmap').on('click', function(e) {
        e.preventDefault();

        var anchor = $(this).attr('href');
        var title = $(this).data('title');

        $('#modal .modal-content-container').empty();

        $('#modal .modal-title-container .modal-title').html(title);
        $('#modal').css('width', '766px');

        $('#modal').modal().open({
            onOpen: function(el, options){
                $.get(anchor, function(response){
                    $('#modal .modal-content-container').html(response);

                    var script = document.createElement("script");
                    script.type = "text/javascript";
                    script.src = "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=showMapsStep1";
                    document.body.appendChild(script);

                    $('#modal .modal-close').click(function(e) {
                        e.preventDefault();
                        $.modal().close();
                        $('#modal .modal-content-container').empty();
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

	// prettify selects
	/*$('select.pretty').chosen({
		disable_search: true,
		inherit_select_classes: true
	}).on('change', function(){
		var box = $(this).data('chosen').container || $(this);
		box[$(this).val() ? 'addClass' : 'removeClass']('selected');
	});
	});*/
});

function initMyCandidates() {
    function hideVisitDateInners() {
        $('.dates-col .dates-inner').hide();
        $('.property-item').removeClass('current');
    }

    function hideCandidates() {
        $('.candidates-col .candidates-inner').hide();
        $('.date-item').removeClass('current');
    }

    hideVisitDateInners();
    $('.property-item').click(function () {
        hideVisitDateInners();
        $('.dates-col .dates-inner[data-property-id="' + $(this).attr('data-property-id') + '"]').show();
        $(this).addClass('current');
    });

    hideCandidates();
    $('.date-item').click(function () {
        hideCandidates();
        $('.candidates-col .candidates-inner[data-candidates-for="' + $(this).attr('id') + '"]').show();
        $(this).addClass('current');
    });
}
