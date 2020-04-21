<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 7/8/2018

 * Time: 9:21 AM

 */



if (!class_exists('TNCP_hc_khangphuc_product_featured')){

class TNCP_hc_khangphuc_product_featured extends TNCP_ToanNang{



protected $options = [

    'products-featured' => array(),

];

function __construct()

{

    parent::__construct(__FILE__);

    parent::setOptions($this->options);

}



/*Add html to Render*/

public function render(){

    global $product;

    global $post;

    ?>

    <div class="hc_khangphuc_product_featured">

        <?php $products = $this->getOption('products-featured'); ?>

        <?php if (!empty($products)):?>

        <div class="container">

            <div class="row">

                <div class="featured-product-title">

                    <h3>

                        <?php _e('Sản phẩm nổi bật','tn_component')?>

                    </h3>

                </div>

                <div class="product-featured">

                <?php foreach ($products as $post): ?>

                <?php $product = wc_get_product( $post->ID ); ?>

                    <div class="col-xs-6 col-md-3">

                        <div class="product_item">

                            <div class="img-thumbnail">

                                <a href="<?php echo get_permalink($post->ID)?>" title="<?php echo get_the_title($post->ID)?>">

                                    <img src="<?php echo get_the_post_thumbnail_url($post->ID,'medium')?>" class="img-responsive" alt="<?php echo get_the_title($post->ID)?>">

                                </a>

                            </div>

                            <div class="detail">

                                <h4 class="product-name">

                                    <?php echo get_the_title($post->ID)?>

                                </h4>

                                <p class="product-code">

                                    <?php _e('MSP: ', 'tn_component'); ?><?php echo $product->get_sku(); ?>

                                </p>

                                <p class="product-button">

                                    <a href="<?php echo get_permalink($post->ID)?>" title="<?php _e('Chi tiết', 'tn_component')?>" class="link-detail">

                                        <?php _e('Chi tiết', 'tn_component')?>

                                    </a>

                                    <a href="?add-to-cart=<?=$post->ID?>" data-product_id="<?=$post->ID?>"

                                       data-quantity="1" class="add-to-cart button product_type_simple add_to_cart_button_hc ajax_add_to_cart"

                                       aria-label=" “<?php echo get_the_title($post->ID)?>” <?php _e('vào giỏ hàng','tn_component')?>" rel="nofollow">

                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>

                                    </a>

                                </p>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

                    <div class="clearfix"></div>
                    <div class="redmore"><a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" ><?php _e('Xem thêm','tn_component'); ?> <i class="fas fa-chevron-circle-right"></i></a></div>
                </div>

            </div>

        </div>

        <?php endif; ?>

    </div>

<?php }

}

}

