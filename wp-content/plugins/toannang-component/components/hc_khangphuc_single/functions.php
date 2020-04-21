<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/19/2018
 * Time: 12:53 PM
 */

if (!function_exists('sidebar_hc_vape_register_widgets')):
    add_action( 'widgets_init', 'sidebar_hc_vape_register_widgets' );
    function sidebar_hc_vape_register_widgets() {
        register_sidebar( array(
            'name' => __( 'Sidebar', 'tn_component' ),
            'id' => 'sidebar-khangphuc-hc',
            'description' => __( 'Sidebar.', 'tn_component' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s sidebar">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ) );
    }
endif;