
$(function() {

    /*
        Default popup
    */

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

    /*
        Success popup 
    */

    $('.success-popup').on('click', function(e) {
        //console.log('Словили');
        e.preventDefault();
        $('#success-popup').modal().open();
    });

    /*
        Google maps popup
    */

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

    /*
        Modal close button
    */

    $('#modal .modal-close').on('click', function(e){
        e.preventDefault();
        $.modal().close();
        $('#modal .modal-content-container').empty();
    });


    $('#success-popup .modal-close').on('click', function(e){
        e.preventDefault();
        $.modal().close();
        $('#success-popup .success-popup-content h1').empty();
        $('#success-popup .success-popup-content p').empty();
    });

    /*
        Dropdown menu
    */

    $('.dropdown-toggle').dropdown();

    /*
        Prettify selects
    */
	if ($.fn.chosen) {
		$('select.pretty').chosen({
			disable_search: true,
			inherit_select_classes: true
		}).on('change', function(){
			var box = $(this).data('chosen').container || $(this);
			box[$(this).val() ? 'addClass' : 'removeClass']('selected');
		});
	}

    showMessagesQueue();

});

function initMyCandidates() {
    function hideVisitDateInners() {
        $('.dates-col .dates-inner').hide();
    }

    function hideCandidates() {
        $('.candidates-col .candidates-inner').hide();
        $('.date-item').removeClass('active');
    }

    hideVisitDateInners();
    $('.property-item').click(function () {
        hideVisitDateInners();
        $('.property-item').removeClass('active');

        $('.dates-col .dates-inner[data-property-id="' + $(this).attr('data-property-id') + '"]').show();
        $(this).addClass('active');

        var count = $('.dates-col .dates-inner[data-property-id="' + $(this).attr('data-property-id') + '"]').find('.c_candidate_block.date-item').length - 2;

        if (count == 0) {
            $('span#visit-dates-title').hide();
        } else {
            $('span#visit-dates-title').show();
        }

        $('div#p'+$(this).attr('data-property-id')+'-wating-candidates-item').click();
    });

    hideCandidates();
    $('.date-item').click(function () {
        hideCandidates();
        $('.candidates-col .candidates-inner[data-candidates-for="' + $(this).attr('id') + '"]').show();

        if ($('.candidates-col .candidates-inner[data-candidates-for="' + $(this).attr('id') + '"]').find('div.attente-block').length == 0) {
            $('#no-candidates').show();
        } else {
            $('#no-candidates').hide();
        }
        if ($(this).hasClass('inactive')) return false;
        $(this).addClass('active');
    });

    $('.date-item').each(function() {
        if (typeof $(this).attr('data-visit-date-id') != 'undefined') {
            if ($('.candidates-col .candidates-inner[data-candidates-for="' + $(this).attr('id') + '"]').find('div.attente-block').length == 0) {
                $(this).addClass('inactive');
            }
        }
    });

    $('div.property-item.c_annonce_block.active').click();
}

var messagesQueue = new Array();

function showMessagesQueue() {
    if (messagesQueue.length == 0) return;
    for (var i=0; i<messagesQueue.length; i++) {
        if (messagesQueue[i].showed === false) {
            messagesQueue[i].showed = true;
            showSuccessMessage(messagesQueue[i].title, messagesQueue[i].body);
            return ;
        }
    }
}

function addToMessagesQueue(title, body) {
    messagesQueue[messagesQueue.length] = {'title': title, 'body': body, 'showed': false};
}

function showSuccessMessage(title, body) {
    var $contentContaigner = $('#success-popup .success-popup-content');
    $contentContaigner.find('h1').text(title);
    $contentContaigner.find('p').text(body);

    $('#success-popup').modal().open({
        onClose: function(el, options){
            showMessagesQueue();
        }
    });
}