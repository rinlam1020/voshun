<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.2
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$attachment_ids = $product->get_gallery_image_ids();

if ( $attachment_ids && has_post_thumbnail() ) { ?>
    <div class="col-md-2 padding-6 col-md-pull-10">
        <div class="page_chitiet_slick slider">
        <?php foreach ( $attachment_ids as $attachment_id ) {
            //echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id  ), $attachment_id );
            $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
            $thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
            $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
            $full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
            $full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
            ?>


            <a href="<?php echo esc_url( $full_src[0] ) ?>"><img class="xzoom-gallery3" width="100%" src="<?php echo esc_url( $thumbnail_src[0] ) ?>"  xpreview="<?php echo esc_url( $full_src[0] ) ?>"></a>
       <?php }
        ?>
        </div>
    </div>


<?php }
