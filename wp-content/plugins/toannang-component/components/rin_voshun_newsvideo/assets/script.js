/*$(document).ready(function() {
    $(".rin_voshun_newsvideo .slick-video iframe").each(function (idx) {
      $(this).addClass("data-idx-" + idx).data("idx", idx);
    });

    function getPlayer (iframe, onPlayerReady, clonned) {
        var $iframe = $(iframe);
        if ($iframe.data((clonned ? "clonned-" : "") + "player")) {
          var isReady = $iframe.data((clonned ? "clonned-" : "") + "player-ready");
          if (isReady) {
            onPlayerReady && onPlayerReady($iframe.data((clonned ? "clonned-" : "") + "player"));
          }         
          return player;
        }
        else {
          var player = new YT.Player($iframe.get(0), {
            events: {
              'onReady': function () {
                $iframe.data((clonned ? "clonned-" : "") + "player-ready", true);
                onPlayerReady && onPlayerReady(player);
              }
            }
          });        
          $iframe.data((clonned ? "clonned-" : "") + "player", player);
          return player;
        }       
    }
    
    //on first load, play the video
    $(".rin_voshun_newsvideo .slick-video").on('init', function(event, slick, currentSlide) {
        var currentSlide, player, command;
        currentSlide = $(slick.$slider).find(".slick-current");        
        getPlayer(currentSlide.find("iframe"), function (player) {
          player.playVideo();
        });
    });

    //when new slide displays, play the video
    $(".rin_voshun_newsvideo .slick-video").on("afterChange", function(event, slick) {
        var currentSlide;
        currentSlide = $(slick.$slider).find(".slick-current");
        getPlayer(currentSlide.find("iframe"), function (player) {
          player.playVideo();
        });
    });
  
    function updateClonnedFrames () {
      frames = $(".rin_voshun_newsvideo .slick-video").find(".slick-slide").not(".slick-cloned").find("iframe");
      frames.each(function () {
        var frame = $(this);
        var idx = frame.data("idx");
        clonedFrames = $(".rin_voshun_newsvideo .slick-video").find(".slick-cloned .data-idx-" + idx);
        console.log("clonedFrames", frame, idx, clonedFrames);
        clonedFrames.each(function () {
          var clonnedFrame = this;
          getPlayer(frame[0], function (player) {
            getPlayer(clonnedFrame, function (clonedPlayer) {         
              console.log("clonnedPlayer", clonedPlayer);
              clonedPlayer.playVideo();  
              clonedPlayer.seekTo(player.getCurrentTime(), true);
              setTimeout(function () {
                clonedPlayer.pauseVideo();         
              }, 0);              
            }, true);
          });
        });        
      });           
    }
    
    //reset iframe of non current slide
    $(".rin_voshun_newsvideo .slick-video").on('beforeChange', function(event, slick, currentSlide) {
        var currentSlide, iframe, clonedFrame;
        currentSlide = $(slick.$slider).find(".slick-current");
        iframe = currentSlide.find("iframe");        
        getPlayer(iframe, function (player) {   
          player.pauseVideo();
          updateClonnedFrames();
        });          
    });

    //start the slider
    $('.rin_voshun_newsvideo .slick-video').slick({
      infinite: true,
      dots:true,
      autoplay: true,
      speed: 3000,
      slidesToShow: 3,
      slidesToScroll:1,
      centerMode: true,
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
});
*/







$(document).ready(function(){
    $(".my-nav a").click(function(){
        $(this).tab('show');
    });

     $('.rin_voshun_newsvideo .item-feed').slick({
  		infinite: true,
  		autoplay: true,
  		speed: 3000,
  		slidesToShow: 3,
  		slidesToScroll: 3,
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

var slider = $('.rin_voshun_newsvideo .slick-video').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    infinite:true,
    autoplay: true,
    speed: 1000,
    centerMode: true,
    responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        centerMode: true,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: false,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: false,
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
  });
  slider.on('afterChange', function(event, slick, currentSlide) {
    var vid = $(slick.$slides[currentSlide]).find('iframe');
    if (vid.length > 0) {
      slider.slick('slickPause');
      $(vid)[0].src += "&autoplay=1";
    }
  });

  $('iframe').on('ended', function() {
    console.log('Video Complete')
    slider.slick('slickPlay');
  });
  

});

