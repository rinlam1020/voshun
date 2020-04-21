$(document).ready(function() {
    $('#searchkey').change(function (e) {
        loadProductAddCart(e);
    });
    $('.search-input svg').click(function (e) {
        loadProductAddCart(e);
    });
    $( "#searchkey" ).keypress(function(event){
        var keycode = event.key;
        /* if (keycode ===  ' ') */
        loadProductAddCart(event,1); 
    });
});

function loadProductAddCart(e,key = 0) {
    var value =  $('#searchkey').val();
    if (key == 1) { value =  $('#searchkey').val()+ e.key; }
    if( value.length > -1 ){
        $('.hc-khangphuc-addcart-page .asl_loader').css('display', 'block');
        var domain = $('#domaintn').val();
        var datas = { 'stn' : value};
        window.stop();
        $.ajax({
            url: domain,
            type: "post",
            data: datas,
            success: function(d) {
                $('#result-search').html(d);
                $('.hc-khangphuc-addcart-page .asl_loader').css('display', 'none');
            }
        });
        return false;
    }
}

/* global wc_add_to_cart_params */

jQuery( function( $ ) {
    if ( typeof wc_add_to_cart_params === 'undefined' ) {

        return false;

    }
    /**
     * AddToCartHandler class.
     */

    var AddToCartHandler = function() {

        $( document.body )

            .on( 'click', '.hc-khangphuc-addcart-page .add_to_cart_button_hc', this.onAddToCart );

    };
    /**
     * Handle the add to cart event.
     */
    AddToCartHandler.prototype.onAddToCart = function( e ) {

        var $thisbutton = $( this );

        if ( $thisbutton.is( '.ajax_add_to_cart' ) ) {

            if ( ! $thisbutton.attr( 'data-product_id' ) ) {

                return true;

            }



            e.preventDefault();



            $thisbutton.removeClass( 'added' );

            $thisbutton.addClass( 'loading' );



            var data = {};



            $.each( $thisbutton.data(), function( key, value ) {

                data[ key ] = value;

            });



            // Trigger event.

            //$( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );



            // Ajax action.

            $.post( wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' ), data, function( response ) {

                if ( ! response ) {

                    return;

                }



                if ( response.error && response.product_url ) {

                    window.location = response.product_url;

                    return;

                }



                // Redirect to cart option

                if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {

                    window.location = wc_add_to_cart_params.cart_url;

                    return;

                }



                // Trigger event so themes can refresh other areas.

                //$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );

                $thisbutton.removeClass( 'loading' );

                var total = $('.toannang-cart-number-btn').data('product_total');

                console.log(total);

                var count = parseInt(total) + 1;

                $('.toannang-cart-number-btn').data('product_total', count);

                $('.toannang-cart-number').html(count);

                return false;

            });

        }

    };



    /**

     * Update fragments after remove from cart event in mini-cart.

     */

    AddToCartHandler.prototype.onRemoveFromCart = function( e ) {

        var $thisbutton = $( this ),

            $row        = $thisbutton.closest( '.woocommerce-mini-cart-item' );



        e.preventDefault();



        $row.block({

            message: null,

            overlayCSS: {

                opacity: 0.6

            }

        });



        $.post( wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'remove_from_cart' ), { cart_item_key : $thisbutton.data( 'cart_item_key' ) }, function( response ) {

            if ( ! response || ! response.fragments ) {

                window.location = $thisbutton.attr( 'href' );

                return;

            }

            $( document.body ).trigger( 'removed_from_cart', [ response.fragments, response.cart_hash ] );

        }).fail( function() {

            window.location = $thisbutton.attr( 'href' );

            return;

        });

    };



    /**

     * Update cart page elements after add to cart events.

     */

    AddToCartHandler.prototype.updateButton = function( e, fragments, cart_hash, $button ) {

        $button = typeof $button === 'undefined' ? false : $button;



        if ( $button ) {

            $button.removeClass( 'loading' );

            $button.addClass( 'added' );



            // View cart text.

            if ( ! wc_add_to_cart_params.is_cart && $button.parent().find( '.added_to_cart' ).length === 0 ) {

                $button.after( ' <a href="' + wc_add_to_cart_params.cart_url + '" class="added_to_cart wc-forward" title="' +

                    wc_add_to_cart_params.i18n_view_cart + '">' + wc_add_to_cart_params.i18n_view_cart + '</a>' );

            }



            $( document.body ).trigger( 'wc_cart_button_updated', [ $button ] );

        }

    };



    /**

     * Update cart page elements after add to cart events.

     */

    AddToCartHandler.prototype.updateCartPage = function() {

        var page = window.location.toString().replace( 'add-to-cart', 'added-to-cart' );



        $( '.shop_table.cart' ).load( page + ' .shop_table.cart:eq(0) > *', function() {

            $( '.shop_table.cart' ).stop( true ).css( 'opacity', '1' ).unblock();

            $( document.body ).trigger( 'cart_page_refreshed' );

        });



        $( '.cart_totals' ).load( page + ' .cart_totals:eq(0) > *', function() {

            $( '.cart_totals' ).stop( true ).css( 'opacity', '1' ).unblock();

            $( document.body ).trigger( 'cart_totals_refreshed' );

        });

    };



    /**

     * Update fragments after add to cart events.

     */

    AddToCartHandler.prototype.updateFragments = function( e, fragments ) {

        if ( fragments ) {

            $.each( fragments, function( key ) {

                $( key )

                    .addClass( 'updating' )

                    .fadeTo( '400', '0.6' )

                    .block({

                        message: null,

                        overlayCSS: {

                            opacity: 0.6

                        }

                    });

            });



            $.each( fragments, function( key, value ) {

                $( key ).replaceWith( value );

                $( key ).stop( true ).css( 'opacity', '1' ).unblock();

            });



            $( document.body ).trigger( 'wc_fragments_loaded' );

        }

    };



    /**

     * Init AddToCartHandler.

     */

    new AddToCartHandler();

});