$(document).ready(function() {

    if(jQuery(window).width()>480)

    	$('.cy_khangphuc_header .search-container .button-box select').select2();

});

$(document).ready(function(){

	$(window).bind("load resize", function(){

		if(jQuery(window).width()<768){

			$('body').css('background-color', '#fff');

			jQuery('.cy_khangphuc_header nav#nav-main').mmenu({

			});

		}

	});

	$(window).scroll(function() {    
	    var scroll = $(window).scrollTop();    
	    if (scroll <= 200) {
	        $(".cy_khangphuc_header").removeClass("darkHeader");
	        $("#container-site").removeClass("darkSite");
	    }else{
	    	$(".cy_khangphuc_header").addClass("darkHeader");
	    	$("#container-site").addClass("darkSite");
	    }
	});

});