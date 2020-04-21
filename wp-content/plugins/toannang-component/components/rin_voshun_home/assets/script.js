( function( $ ) {
$( document ).ready(function() {

  
  $('.rin_voshun_home .sect .service .items').slick({
      infinite: true,
      autoplay: true,
      speed: 3000,
      slidesToShow: 4,
      slidesToScroll: 4,
      responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
        
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
  });

$(".my-nav a").click(function(){

        

        $(this).tab('show');

        

    });

  
$('#cssmenu li.has-sub>a').on('click', function(){
    $(this).removeAttr('href');
    var element = $(this).parent('li');
    if (element.hasClass('open')) {
      element.removeClass('open');
      element.find('li').removeClass('open');
      element.find('ul').slideUp();
    }
    else {
      element.addClass('open');
      element.children('ul').slideDown();
      element.siblings('li').children('ul').slideUp();
      element.siblings('li').removeClass('open');
      element.siblings('li').find('li').removeClass('open');
      element.siblings('li').find('ul').slideUp();
    }
  });

  $('#cssmenu>ul>li.has-sub>a').append('<span class="holder"></span>');

  (function getColor() {
    var r, g, b;
    var textColor = $('#cssmenu').css('color');
    textColor = textColor.slice(4);
    r = textColor.slice(0, textColor.indexOf(','));
    textColor = textColor.slice(textColor.indexOf(' ') + 1);
    g = textColor.slice(0, textColor.indexOf(','));
    textColor = textColor.slice(textColor.indexOf(' ') + 1);
    b = textColor.slice(0, textColor.indexOf(')'));
    var l = rgbToHsl(r, g, b);
   
  })();

  function rgbToHsl(r, g, b) {
      r /= 255, g /= 255, b /= 255;
      var max = Math.max(r, g, b), min = Math.min(r, g, b);
      var h, s, l = (max + min) / 2;

      if(max == min){
          h = s = 0;
      }
      else {
          var d = max - min;
          s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
          switch(max){
              case r: h = (g - b) / d + (g < b ? 6 : 0); break;
              case g: h = (b - r) / d + 2; break;
              case b: h = (r - g) / d + 4; break;
          }
          h /= 6;
      }
      return l;
  }
});
} )( jQuery );
