$(document).ready(function() {

	$(window).scroll(function(){ 
		var top = $(document).scrollTop(); 
		if (top > 0) {
			$('#logo').removeClass('home-logo').addClass('logo'); 
		}
		else {
			$('#logo').removeClass('logo').addClass('home-logo'); 
		}
    });

});