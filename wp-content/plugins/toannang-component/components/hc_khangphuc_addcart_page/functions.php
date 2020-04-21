<?php

if (!function_exists('addToCartInPageCart')){
    function addToCartInPageCart(){
        echo '<div class="hc-khangphuc-addcart-page">';
        echo '<p class="title-search"><span>'.__('Thêm sản phẩm vào giỏ hàng.','tn_component').'</span></p>';
        echo '<div class="search-input"><input class="search-key" placeholder="'.__('Tìm kiếm...', 'tn_component').'" name="searchkey" id="searchkey" />
                <div class="asl_loader"><div class="asl_loader-inner asl_simple-circle"></div></div>
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                <path id="magnifier-2-icon" d="M460.355,421.59L353.844,315.078c20.041-27.553,31.885-61.437,31.885-98.037
                    C385.729,124.934,310.793,50,218.686,50C126.58,50,51.645,124.934,51.645,217.041c0,92.106,74.936,167.041,167.041,167.041
                    c34.912,0,67.352-10.773,94.184-29.158L419.945,462L460.355,421.59z M100.631,217.041c0-65.096,52.959-118.056,118.055-118.056
                    c65.098,0,118.057,52.959,118.057,118.056c0,65.096-52.959,118.056-118.057,118.056C153.59,335.097,100.631,282.137,100.631,217.041z"></path>

            </svg>
            <input type="hidden" id="domaintn" value="'.get_home_url().'" /></div>';
        echo '<div id="result-search"></div>';
        echo '</div>';
    }
    add_action('woocommerce_cart_collaterals','addToCartInPageCart');
    add_action('woocommerce_cart_is_empty','addToCartInPageCart');
}

if (!function_exists('getProductByNameSearch')){
    function getProductByNameSearch(){
        if (isset($_POST['stn']))
        {
            global $wpdb;
            global $post;
            $name = $_POST['stn'];
            if (!empty($_POST['stn']))
                $myrows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE `post_type` = 'product' AND `post_status` = 'publish' AND `post_title` LIKE '%{$name}%' LIMIT 18;" );
            else
                $myrows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE `post_type` = 'product' AND `post_status` = 'publish' LIMIT 18;" );
            $str = '';
            if (!empty($myrows))
            {
                foreach ($myrows as $post)
                {
                    $_product = wc_get_product( $post->ID );
                    $price = $_product->get_price();
                    if (!is_numeric($price)) $price = 0;
                    $str .= '<div class="item">'.get_the_post_thumbnail( $post->ID, 'thumbnail' );
                    $str .='<h3> <a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>';
                    $str .= '<p class="price-wc">'.number_format($price, 0,",",".").' VND</p>';
                    $str .= '<p class="price-wc"><a href="?add-to-cart='.$post->ID.'" data-product_id="'.$post->ID.'"';
                    $str .= 'data-quantity="1" class="add-to-cart button product_type_simple add_to_cart_button_hc ajax_add_to_cart" aria-label=" “'.get_the_title($post->ID).'” ';
                    $str .=  __('vào giỏ hàng','tn_component').'" rel="nofollow"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a></p>';
                    $str .='<div class="clearfix"></div>';
                    $str .='</div>';
                }
                $str .= '<a class="load-page" href="'.wc_get_cart_url().'">'.__('Cập nhật lại giỏ hàng','tn_component').'</a>';
            }else{
                $str .= '<div class="item">';
                $str .= '<h2 class="not-found">'.__('Không tìm thấy sản phẩm', 'tn_component').'</h2>';
                $str .='</div>';
            }
            echo $str;
            die();
        }
    }
    if (!is_admin()) add_action('init','getProductByNameSearch');
}