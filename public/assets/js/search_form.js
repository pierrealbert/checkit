var initialRadioValues = new Array();
$(document).ready(function(){
    var arrIndx = 0;
    $('form[name="searchForm"]').find('input[type="radio"]:checked').each(function() {
        initialRadioValues[arrIndx++] = $(this).attr('id');
    });
    $('form[name="searchForm"]').find('input[type="checkbox"]:checked').each(function() {
        initialRadioValues[arrIndx++] = $(this).attr('id');
    });

    $('input.btn-tous').click(function() {
        $(this).parent().find('input[type="radio"]:checked').prop('checked', false);
        $(this).parent().find('input[type="checkbox"]:checked').prop('checked', false);
        for (var i=0; i<initialRadioValues.length; i++) {
            if ($(this).parent().find('input#'+initialRadioValues[i]).length) {
                //$('input#'+initialRadioValues[i]).prop('checked', true);
            }
        }
    });
});