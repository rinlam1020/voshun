<?php
/**
 * This is custom function
 */


if(!function_exists('az_wp_enqueue_script')):
    function az_wp_enqueue_script() {
        wp_deregister_script('jquery');
        wp_register_script('jquery', get_bloginfo('wpurl')."/assets/js/jquery-3.2.1.min.js", false, null);
        wp_enqueue_script('jquery');
        //add style
        wp_enqueue_style( 'fontawesome', get_bloginfo('wpurl').'/assets/include/fontawesome-free-5.0.6/web-fonts-with-css/css/fontawesome-all.css' );
        wp_enqueue_style( 'bootstrap-theme', 'https://fonts.googleapis.com/css?family=Open+Sans');
        wp_enqueue_style( 'bootstrap', get_bloginfo('wpurl'). '/assets/css/bootstrap.css');
        wp_enqueue_style( 'mmenu', get_bloginfo('wpurl'). '/assets/include/mmenu/jquery.mmenu.all.css');
        wp_enqueue_style( 'animate', get_bloginfo('wpurl'). '/assets/include/wow/animate.css');
        wp_enqueue_style( 'slick-css', get_bloginfo('wpurl'). '/assets/include/slick/slick.css');
        wp_enqueue_style( 'slick-theme', get_bloginfo('wpurl'). '/assets/include/slick/slick-theme.css');
        wp_enqueue_style( 'hover', get_bloginfo('wpurl'). '/assets/css/hover.css');
        wp_enqueue_style( 'reset', get_bloginfo('wpurl'). '/assets/css/reset.css' );

        //add script footer
        wp_enqueue_script( 'mmenu',get_bloginfo('wpurl').'/assets/include/mmenu/jquery.mmenu.all.min.js', array(), '1.0.0', true );
        wp_enqueue_script( 'slick',get_bloginfo('wpurl').'/assets/include/slick/slick.js', array(), '1.0.0', false );
        wp_enqueue_script( 'wow',get_bloginfo('wpurl').'/assets/include/wow/wow.js', array(), '1.0.0', false );
        wp_enqueue_script( 'bootstrap-js',get_bloginfo('wpurl').'/assets/js/bootstrap.js', array(), '1.0.0', false );
        wp_enqueue_script( 'smoothscroll-js',get_bloginfo('wpurl').'/assets/js/smoothscroll.min.js', array(), '1.0.0', true );
        //add script
        wp_enqueue_script( 'maps-api-js','https://maps.googleapis.com/maps/api/js?key=AIzaSyCrqCCZ0e7lqFRD1OvRgLmOEZabZXSv8ME', array(), '1.0.0', true );
        wp_enqueue_script( 'maps-site-js',get_bloginfo('wpurl').'/assets/js/maps.js', array(), '1.0.0', true );
    }
    add_action( 'wp_enqueue_scripts', 'az_wp_enqueue_script',1 );
endif;

if(!function_exists('az_wp_enqueue_script_end')):
    function az_wp_enqueue_script_end(){
        wp_enqueue_style( 'style', get_bloginfo('wpurl'). '/assets/css/style.css' );
        wp_enqueue_script( 'header_3d',get_bloginfo('wpurl').'/assets/js/index.js', array(), '1.0.0', true );
    }
    add_action( 'wp_enqueue_scripts', 'az_wp_enqueue_script_end',99 );
endif;

/*
 * Edit field check out woocommerce
 * */
if(!function_exists('sv_require_wc_custom_field')):
    function sv_require_wc_custom_field( $fields ) {
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);

        $fields['billing']['billing_email']['required'] = false;
        $fields['billing']['billing_address_1']['required'] = false;
        return $fields;
    }
    add_filter( 'woocommerce_checkout_fields', 'sv_require_wc_custom_field' );
endif;



