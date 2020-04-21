<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 7/8/2018

 * Time: 9:21 AM

 */



if (!class_exists('TNCP_hc_khangphuc_product_home')){

class TNCP_hc_khangphuc_product_home extends TNCP_ToanNang{



protected $options = [

    'products-cat' => array(),

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

    <div class="hc_khangphuc_product_home">

        <?php

            $product_cat = $this->getOption('products-cat');

            $categorys = tn_cat_product_recursive($product_cat);

        ?>

        <?php if (!empty($categorys)):?>

        <div class="container">

            <div class="row">

                <div class="col-xs-12 col-md-3">

                    <div class="list-category">

                        <h3 class="title-list-cat"><?php _e('Danh mục sản phẩm','tn_component'); ?></h3>

                        <?php woocommerce_khangphuc_menu_cat($categorys); ?>

                    </div>

                </div>

                <div class="col-xs-12 col-md-9">

                    <div class="row">

                    <?php

                        $args = array(

                            'post_type'      => 'product',

                            'posts_per_page' => 6,

                        );



                        $loop = new WP_Query( $args );

                    ?>

                    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

                        <?php $product = wc_get_product( $post->ID ); ?>

                        <div class="col-xs-6 col-md-4">

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

                    <?php endwhile; ?>

                    <?php wp_reset_query(); ?>

                    </div>
                    <div class="redmore"><a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" ><?php _e('Xem thêm','tn_component'); ?> <i class="fas fa-chevron-circle-right"></i></a></div>
                </div>

            </div>

        </div>

        <?php endif; ?>

    </div>

<?php }

}

}

