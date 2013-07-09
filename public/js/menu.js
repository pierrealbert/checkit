
$(function(){
    $("ul.sf-menu").supersubs({
        minWidth:    12,
        maxWidth:    15,
        extraWidth:  1
    }).superfish({
        delay: 100,
        autoArrows: false,
        speed: 'fast'
    });
});