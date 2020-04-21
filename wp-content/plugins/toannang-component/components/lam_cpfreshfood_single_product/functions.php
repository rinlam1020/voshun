<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/16/2018
 * Time: 9:57 AM
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

add_filter( 'woocommerce_checkout_fields' , 'custom_wc_checkout_fields' );

function custom_wc_checkout_fields( $fields ) {
$fields['billing']['billing_email']['placeholder'] = 'Email';
return $fields;
}


function add_slider_gallery() { ?>
    <?php
    global $product;
    global $post;
    $attachment_ids = $product->get_gallery_image_ids();
    ?>
    <div class="qv_alt_details__group col-lg-4 col-md-6 col-sm-12">
        <div class="qv_alt_details__left">
            <div class="qv_alt_details__img">
                <div class="qv_alt_details__img--items">
                    <a href="<?= get_the_post_thumbnail_url( get_the_id(), 'shop_single' );?>" data-fancybox="gallerya"><img src="<?= get_the_post_thumbnail_url( get_the_id(), 'shop_single' );?>"></a>
                </div>
                <?php foreach( $attachment_ids as $attachment_id ) { ?>
                <div class="qv_alt_details__img--items">
                    <a href="<?php echo $thumbnail_url = wp_get_attachment_image_src( $attachment_id, 'shop_single' )[0]; ?>" data-fancybox="gallery"><img src="<?php echo $thumbnail_url = wp_get_attachment_image_src( $attachment_id, 'shop_single' )[0]; ?>"></a>
                </div>
              <?php } ?>

            </div>
            <div class="row margin-5">
                <div class="qv_alt_details__slicks">
                    <div class="col-xs-3 padding-5">
                        <div class="qv_alt_details__items">
                            <a href="javascript:;"><img src="<?= get_the_post_thumbnail_url( get_the_id(), 'shop_single' ); ?>"></a>
                        </div>
                    </div>
                     <?php foreach( $attachment_ids as $attachment_id ) { ?>

                    <div class="col-xs-3 padding-5">
                        <div class="qv_alt_details__items">
                            <a href="javascript:;"><img src="<?php echo $thumbnail_url = wp_get_attachment_image_src( $attachment_id, 'shop_single' )[0]; ?>"></a>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php }
add_action('woocommerce_before_single_product_summary', 'add_slider_gallery',27 );


function add_single_about() { ?>
    <?php
    global $product;
  
    $abouts = get_field('home_abouts','option');
    ?>
    <div class="col-lg-3 col-md-3 col-sm-12 single-about visible-lg">
        <div class="about-item">
            <div class="title">
                <?php _e('-- CHẤT LƯỢNG CHO TẤT CẢ --   ','tn_component'); ?>
            </div>
        <?php
        if($abouts) {
            foreach($abouts as $row){
                ?>
                <div class="item">
                    <a href="<?php echo $row['link']; ?>">
                        <div class="item-img">
                            <img src="<?php echo $row['img']['url']; ?>" alt=" <?php echo $row['title']; ?>" title=" <?php echo $row['title']; ?>">
                        </div>
                        <div class="item-content">
                            <p class="titleq"><?php echo $row['title']; ?></p>
                            <p class="desc"><?php echo $row['desc']; ?></p>
                        </div>
                    </a>
                </div>
            <?php } } ?>
        </div>

    </div>

<?php }
add_action('woocommerce_after_single_product_summary', 'add_single_about',8 );

function add_cout_view() { ?>
    <?php
    global $product;

    ?>
    <div class="count-view">
        <i class="fa fa-phone"></i>Liên hệ
    </div>

<?php }
add_action('woocommerce_single_product_summary', 'add_cout_view',15 );




function add_contact_link(){ ?>
     <!-- <div class="product_sw_price">
        <?php 
            $price = get_post_meta(  get_the_ID(), '_regular_price', true);
            $sale = get_post_meta(  get_the_ID(), '_sale_price', true);
            $curency = get_woocommerce_currency_symbol();
        ?>
        <?php if($sale) { ?>
             <span class="price-thr"><?php echo number_format($price); ?> <sup><?php echo $curency; ?></sup></span>
              - <span class="price-pri"><?php echo number_format($sale); ?> <sup><?php echo $curency; ?></sup></span>
            <?php } elseif($price) { ?>
            <span class="price-pri"><?php echo number_format($price); ?> <sup><?php echo $curency; ?></sup></span>
            <?php }else {?>Giá: <span class="lienhe">Liên hệ</span> <?php }?>
    </div>
    <div class="to-contact-page">
    </div> -->
<?php }
add_action('woocommerce_single_product_summary', 'add_contact_link',25 );

// thêm nút share, yêu cầu cài Plugin AddtoAny Share Button
add_action('woocommerce_single_product_summary','add_add_to_any_share_button',45);
function add_add_to_any_share_button(){
    echo do_shortcode('[addtoany]');
}

// ĐẶT LẠI NÚT ADD CART
add_filter( 'woocommerce_product_single_add_to_cart_text', 'az_custom_cart_button_text' );
add_filter( 'woocommerce_product_add_to_cart_text', 'az_custom_cart_button_text' );
function az_custom_cart_button_text() {
    return __( 'Thêm vào giỏ', 'woocommerce' );
}
// đặt lại chi tiết sản phẩm
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action('woocommerce_after_single_product_summary', 'az_output_product_data_tabs');
function az_output_product_data_tabs(){
    global $product;
    $section = get_field('voshun_home','option');
            $product_cate = $section["product_cat"];
    ?>
    <div class="col-xs-12">
        <div class="row margin-6">

            <div class="col-md-9 padding-6">

                <div class="page_chitiet_tab">
                    <ul class="nav nav-pills">
                        <li class="active"><a data-toggle="tab" href="#tab_details_1">Thông tin sản phẩm</a></li>
                        <li><a data-toggle="tab" href="#tab_details_2">Công dụng</a></li>
                        <li><a data-toggle="tab" href="#tab_details_3">Hướng dẩn của sản phẩm</a></li>
                        <li><a data-toggle="tab" href="#tab_details_4">Lý do mua hàng tại voshun</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab_details_1" class="tab-pane fade in active">
                            <?php echo nl2br(get_the_content($product->get_id()))?>
                        </div>

                        <div id="tab_details_2" class="tab-pane fade">
                            <?php $usage = get_field('usage',$product->get_id());echo $usage; ?>
                        </div>

                        <div id="tab_details_3" class="tab-pane fade">
                            <?php $instruction = get_field('instruction',$product->get_id());echo $instruction; ?>
                        </div>

                        <div id="tab_details_4" class="tab-pane fade">
                            <?php $whyshopatus = get_field('whyshopatus',$product->get_id());echo $whyshopatus; ?>
                        </div>
                    </div>
                </div>

                <div class="fb-comments" data-href="<?php get_permalink($product->get_id())?>" data-width="100%"></div>
            </div>
                <div class="col-md-3">
                    <div class="sidebarsss">

                        <div class="ttle">Danh mục Sản phẩm</div>
                        <ul class="my-nav">
                                            <?php foreach ($product_cate as $key => $value) { ?>
                                                <?php $terms = get_term($value["category"],'product_cat'); ?>
                                                
                                                <?php if($key==0): ?> 

                                                    <li><a data-toggle="tab" href="<?=get_term_link($terms->term_id)?>"><?=$terms->name?></a></li>
                                                <?php else: ?>
                                                    <li><a data-toggle="tab" href="<?=get_term_link($terms->term_id)?>"><?=$terms->name?></a></li>
                                                <?php endif;?>

                                            <?php } ?>
                                            
                                        </ul>
                    </div>
                </div>
        </div>
    </div>
<?php }


// tạo lại sản phẩm liên quan, ưu tiên vị trí thứ 10
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 10 );
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_action('woocommerce_after_single_product_summary', 'woocommerce_remove_related_products',10);
function woocommerce_remove_related_products()
{

    $cats = wp_get_post_terms(get_the_ID(),'product_cat');
    $catarr = [];
    if(!empty($cats)){
        foreach ($cats as $cat){
            $catarr[] = $cat->term_id;
        }
    }

    if($cats){
        $related_posts = get_posts(array(
            'posts_per_page' => 12,
            'post_type' => 'product',
            'exclude' => get_the_ID(),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $catarr
                )
            ),
            'orderby'   => 'rand'
        ));
    }
    ?>
    <?php if(!empty($related_posts)) :?>
    <div class="col-xs-12 related-product cy_denchieusang_home_product">
        <div class="page_chitiet_tittle">
            <span><?php _e('SẢN PHẨM CÙNG LOẠI', 'tn_component');?></span>
        </div>
        <div class="product_sw_group wow fadeInUp" data-wow-duration="1s">
            <div class="flex-sp slider-related">
                <?php foreach ($related_posts as $pro) : ?>

                    <?php $product = wc_get_product($pro);
                    $price = get_post_meta( $pro->ID , '_regular_price', true);
                    $sale = get_post_meta( $pro->ID, '_sale_price', true);
                    $curency = get_woocommerce_currency_symbol();
                    ?>
                    <div class="col-item ">
                        <div class="item">
                            <div class="item-img">
                                <a href="<?php echo get_permalink( $pro ); ?>" >
                                    <img src="<?= get_the_post_thumbnail_url( $pro->ID, 'shop_catalog' );?>" alt="<?php echo get_the_title($pro->ID); ?>" >
                                </a>
                                <div class="giohang">
                                    <a href="/?add-to-cart=<?=$pro->ID?>" data-product_id="<?=$pro->ID?>"
                                       data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart"
                                       aria-label=" “<?php echo get_the_title($pro->ID)?>” <?php _e('vào giỏ hàng','tn_component')?>">
                                        <i class="fas fa-cart-plus"></i>
                                        <span class="gh">
                                            <?php _e('Thêm vào giỏ','tn_component'); ?>
                                          </span>
                                    </a>
                                </div>
                            </div>
                            <div class="item-content">
                                <h5>
                                    <a href="<?php echo get_permalink( $pro ); ?>"><?php echo get_the_title($pro->ID); ?></a>
                                </h5>
                                <div class="gia">
                                    <?php if($sale) { ?>
                                        <div class="gia1">
                                            <?php echo number_format($sale); ?><?php echo $curency; ?>
                                        </div>
                                        <div class="gia2">
                                            <?php echo number_format($price); ?><?php echo $curency; ?>
                                        </div>
                                    <?php } elseif($price) { ?>
                                        <div class="gia1">
                                            <?php echo number_format($price); ?><?php echo $curency; ?>
                                        </div>
                                    <?php }else {?>
                                        <div class="gia1">
                                            <?php _e('Liên hệ', 'tn_component');?>
                                        </div>
                                    <?php }?>

                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach;?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php }

//thêm chính sách bán hàng sau nút MUA NGAY


// bật tính năng theme woocommerce
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );
function mytheme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// chèn label 'số lượng' trước thẻ input số lượng
add_action('woocommerce_before_add_to_cart_quantity','add_label_before_quantity_input');
function add_label_before_quantity_input(){
    echo "<div class='dt3d_amount'><label>Số lượng: </label>";
}
// chèn thẻ đóng 'div'
add_action('woocommerce_after_add_to_cart_quantity','add_label_after_quantity_input');
function add_label_after_quantity_input(){
    echo "</div>";

    ?>

<?php }

// thêm nút share, yêu cầu cài Plugin AddtoAny Share Button


function pressfore_comment_time_output($date, $d, $comment){
    return sprintf( _x( '%s trước', '%s = human-readable time difference', 'tn_component' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) );
}
add_filter('get_comment_date', 'pressfore_comment_time_output', 10, 3);




// xóa field thanh toán
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
    
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_postcode']);
    unset($fields['shipping']['shipping_country']);

    return $fields;
}

// xóa instock
add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );


if (!function_exists('renderinput')) :
    function renderinput($field_id){
        global $product;
        switch ($field_id){
            case 12:
                $input = '<input type="hidden" name="input_'.$field_id.'" class="form-control item-quantity" readonly="readonly" value="'.get_the_title($product->ID).'" />';
                break;
            case 13:
                $input = '<input type="hidden" name="input_'.$field_id.'" class="form-control item-quantity" readonly="readonly" value="'.get_the_ID().'" />';
                break;

        }
        return $input;
    }
endif;

// Remove the additional information tab
function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] );
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );


add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

    $tabs['description']['title'] = __( 'Thông tin chi tiết' );		// Rename the description tab
    $tabs['apply']['title'] = __( 'Ứng dụng' );
    $tabs['reviews']['title'] = __( 'Bình luận' );				// Rename the reviews tab

    return $tabs;

}
add_filter( 'woocommerce_locate_template', 'denchieusang_woocommerce_locate_template', 11, 5 );
function denchieusang_woocommerce_locate_template( $template, $template_name, $template_path ) {
    global $woocommerce;
    $_template = $template;

    if ( ! $template_path ) $template_path = $woocommerce->template_url;

    $plugin_path  = __DIR__ . '/woocommerce/';

    // Look within passed path within the theme - this is priority
    $template = locate_template(

        array(
            $template_path . $template_name,
            $template_name
        )
    );

    // Modification: Get the template from this plugin, if it exists
    if ( ! $template && file_exists( $plugin_path . $template_name ) )
        $template = $plugin_path . $template_name;

    // Use default template
    if ( ! $template )
        $template = $_template;

    // Return what we found
    return $template;
}

function getPostViews($postID, $is_single = true){
    global $post;
    if(!$postID) $postID = $post->ID;
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if(!$is_single){
        return '<span class="svl_show_count_only">'.$count.'</span>';
    }
    $nonce = wp_create_nonce('devvn_count_post');
    if($count == "0"){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return '<span class="svl_post_view_count" data-id="'.$postID.'" data-nonce="'.$nonce.'">0 </span>';
    }
    return '<span class="svl_post_view_count" data-id="'.$postID.'" data-nonce="'.$nonce.'">'.$count.' </span>';
}

function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count == "0" || empty($count) || !isset($count)){
        add_post_meta($postID, $count_key, 1);
        update_post_meta($postID, $count_key, 1);
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

add_action( 'wp_ajax_svl-ajax-counter', 'svl_ajax_callback' );
add_action( 'wp_ajax_nopriv_svl-ajax-counter', 'svl_ajax_callback' );
function svl_ajax_callback() {
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "devvn_count_post")) {
        exit();
    }
    $count = 0;
    if ( isset( $_GET['p'] ) ) {
        global $post;
        $postID = intval($_GET['p']);
        $post = get_post( $postID );
        if($post && !empty($post) && !is_wp_error($post)){
            setPostViews($post->ID);
            $count_key = 'post_views_count';
            $count = get_post_meta($postID, $count_key, true);
        }
    }
    die($count.' Views');
}

add_action( 'wp_footer', 'svl_ajax_script', PHP_INT_MAX );
function svl_ajax_script() {
    if(!is_single()) return;
    ?>
    <script>
        (function($){
            $(document).ready( function() {
                $('.svl_post_view_count').each( function( i ) {
                    var $id = $(this).data('id');
                    var $nonce = $(this).data('nonce');
                    var t = this;
                    $.get('<?php echo admin_url( 'admin-ajax.php' ); ?>?action=svl-ajax-counter&nonce='+$nonce+'&p='+$id, function( html ) {
                        $(t).html( html );
                    });
                });
            });
        })(jQuery);
    </script>
    <?php
}