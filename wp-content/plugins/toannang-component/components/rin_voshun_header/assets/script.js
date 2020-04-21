
$(window).bind("load resize", function(){

    if(jQuery(window).width()<992){

      jQuery('nav#main-menu').mmenu({



      });

     

    }

  });

(function($){
  "use strict";
  $(document).ready(function(){
    
      $(window).scroll(function(){
        if($(window).scrollTop()>136) {
          $('.sticky-header').addClass('show');
        }else {
          $('.sticky-header').removeClass('show');
        }
      });
      $('.rin_voshun_header  #main-menu2 ul li:last-child').attr("data-toggle", "dropdown");
     $('#search').removeClass("mm-panel");
      $('#search').removeClass("mm-hidden");
      $('#main-menu .searchfield').hide();
      $('#main-menu ul li:last-child').click(function(){
        if($('.searchfield').hasClass('displayblock')){
          $('.searchfield').removeClass("displayblock");
            $('.searchfield').addClass("displaynone");
          }
          else{
            $('.searchfield').addClass("displayblock");
            $('.searchfield').removeClass("displaynone");
          }
        $("#main-menu .searchfield").animate({
            height: "toggle",
            opacity: "toggle"
        }, "fast");
      });
     /* $('#main-menu').click(function(){
        if($('#main-menu .searchfield').hasClass('displayblock')){
          $('#main-menu .searchfield').addClass("displaynone");
            $('#main-menu .searchfield').removeClass("displayblock");
          }
        
      });*/
      $('.rin_voshun_header #search').hide();
      $('.rin_voshun_header  #main-menu2 ul li:last-child').click(function(){
        
        $(".rin_voshun_header #search").animate({
            height: "toggle",
            opacity: "toggle"
        }, "fast");
      });
  });
  $(document).ready(function(){
    
      $(window).scroll(function(){
        if($(window).scrollTop()>136) {
          $('.sticky-header2').addClass('show');
          $('.rin_voshun_header .menu .logo img').addClass('response-img');
        }else {
          $('.sticky-header2').removeClass('show');
          $('.rin_voshun_header .menu .logo img').removeClass('response-img');
        }
      });
      $(".rin_voshun_header  #main-menu ul li:first-child").hover( function() {
   $(".rin_voshun_header #main-menu:before").css({"background-color":"rgb(224, 177, 71)"});
});
  });
})(jQuery);