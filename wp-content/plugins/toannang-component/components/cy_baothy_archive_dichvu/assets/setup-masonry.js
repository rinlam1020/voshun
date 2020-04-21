$(document).ready(function () {
    if($('.archive-content-wrapper.grid').length > 0){
        $('.archive-content-wrapper.grid').masonry({
            // options
            itemSelector: '.grid-item',

        });
    }

})