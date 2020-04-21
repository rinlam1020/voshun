<?php

global $post, $page_info, $page_location;

$page_info = get_field('page_info', 'option');
$page_location = get_field('main_location', 'option');

/**
 * HOOK: HEADER
 */
add_action('tnc_theme_header', function () use ($page_info) {
    $terms = $args = $menu_items = array();
    $menu_items = tn_get_menu('main-menu');
    // get Header Lept Vape
    $terms = get_categories( array(
        'taxonomy' => 'product_cat',
        'orderby' => 'name',
        'order'   => 'ASC'
    ) );
    $args = array(
        'categories' => $terms,
        'menu' => $menu_items,
        'hotline_ban_hang' => get_field('hotline','option'),
        'voshun_header' => get_field('voshun_header','option')
    );
    TNCP_ToanNang::renderComponent('TNCP_rin_voshun_header',$args);

});


/**
 * HOOK: HOME PAGE -----------------------------------------------------------------------------------------------------
 */

add_action('tnc_theme_home', function () {
    $slider = do_shortcode('[metaslider id="94"]');
    $featured = get_posts( array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'posts_per_page'      => 4,
        'tax_query'           => array(
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        )
    ) );
    wp_reset_postdata();

    $danhmuc = get_terms( 'product_cat', array() );
    $args = array(
        'slider'              => $slider,
        'list-images' => get_field('banners','option'),
        'products-featured' => $featured,
        'products-cat' => $danhmuc,
        'phuckhang-news'  => get_field('danh_sach_tin','option'),
        'voshun_home' => get_field('voshun_home','option')
    );
    TNCP_ToanNang::renderComponent('TNCP_rin_voshun_slider',$args);
    TNCP_ToanNang::renderComponent('TNCP_rin_voshun_home',$args);
    TNCP_ToanNang::renderComponent('TNCP_rin_voshun_newsvideo',$args);
});

/**
 * HOOK: PAGE ----------------------------------------------------------------------------------------------------------
 */

add_action('tnc_theme_page', function () use ($post, $page_location) {
    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_breadcrumb', array('khangphuc-breadcrumb-banner'=> get_field('banner_breadcrumbs','option')));
    $args = [];
    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_page',$args);
});

/**
 * HOOK: ARCHIVE -------------------------------------------------------------------------------------------------------
 */

add_action('tnc_theme_archive', function () {
    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_breadcrumb', array('khangphuc-breadcrumb-banner'=> get_field('banner_breadcrumbs','option')));
    $args = [];
    TNCP_ToanNang::renderComponent('tncp_cy_baothy_archive_dichvu',$args);
});

add_action('tnc_theme_archive_product', function () {
    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_breadcrumb', array('khangphuc-breadcrumb-banner'=> get_field('banner_breadcrumbs','option')));
    $danhmuc = get_terms( 'product_cat', array() );
    $args = ['products-cat' => $danhmuc];
    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_archive_product',$args);
});

/**
 * HOOK: SINGLE --------------------------------------------------------------------------------------------------------
 */

add_action('tnc_theme_single', function () use ($post, $page_info) {

    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_breadcrumb', array('khangphuc-breadcrumb-banner'=> get_field('banner_breadcrumbs','option')));
    $args = [];
    TNCP_ToanNang::renderComponent('tncp_th_anpha_single_video',$args);

});

add_action('tnc_theme_single_product', function(){

    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_breadcrumb', array('khangphuc-breadcrumb-banner'=> get_field('banner_breadcrumbs','option')));
    $args = [];
    TNCP_ToanNang::renderComponent('tncp_lam_cpfreshfood_single_product',$args);

});

/**
 * HOOK: SEARCH --------------------------------------------------------------------------------------------------------
 */
add_action('tnc_theme_search', function(){

    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_breadcrumb', array('khangphuc-breadcrumb-banner'=> get_field('banner_breadcrumbs','option')));
    $args = [];
    TNCP_ToanNang::renderComponent('TNCP_hc_vape_archive_1',$args);

});

/**
 * HOOK: FULL PAGE -----------------------------------------------------------------------------------------------------
 */

add_action('tnc_theme_page_full', function () use ($post, $page_location) {
    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_breadcrumb', array('khangphuc-breadcrumb-banner'=> get_field('banner_breadcrumbs','option')));
    $args = [];
    TNCP_ToanNang::renderComponent('TNCP_hc_khangphuc_page',$args);
});

/**
 * HOOK: FOOTER
 */
add_action('tnc_theme_footer', function () {

    $args = array(
        'khangphuc-logo-footer' => get_field('ft_logo','option'),
        'khangphuc-name-footer' => get_field('ft_name','option'),
        'khangphuc-hotline' => get_field('ft_hotline','option'),
        'khangphuc-address' => get_field('ft_dia_chi','option'),
        'khangphuc-email' => get_field('ft_email','option'),
        'khangphuc-website' => get_field('ft_website','option'),
        'khangphuc-facebook' => get_field('ft_fanpage','option'),
        'khangphuc-map' => get_field('ft_map','option'),
        'khangphuc-youtube' => get_field('ft_youtube','option'),
        'khangphuc-google' => get_field('ft_google','option'),
        'voshun_footer' => get_field('voshun_footer','option')
    );
    TNCP_ToanNang::renderComponent('TNCP_rin_voshun_footer',$args);

});

/**
 * HOOK: PAGE 404
 */
add_action('container_page_404', function () {

    $args = array();
    TNCP_ToanNang::renderComponent('tncp_hc_404_page',$args);

});