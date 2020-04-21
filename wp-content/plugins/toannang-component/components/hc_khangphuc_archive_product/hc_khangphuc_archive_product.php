<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 7/8/2018

 * Time: 9:21 AM

 */



if (!class_exists('TNCP_hc_khangphuc_archive_product')){

    class TNCP_hc_khangphuc_archive_product extends TNCP_ToanNang{



        protected $options = [

            'products-cat' => array(),
            'products-brand' => array(),

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

            <div class="hc_khangphuc_archive_product">

                <div class="title-archive">

                    <div class="container">

                        <div class="row">

                            <h1 class="title-product-cat">

                                <?php the_archive_title()?>

                            </h1>

                        </div>

                    </div>

                </div>

                <?php

                $product_cat = $this->getOption('products-cat');

                $categorys = tn_cat_product_recursive($product_cat);

                $product_brand = $this->getOption('products-brand');

                $category_brand = tn_cat_product_recursive($product_brand);

                ?>
                    <div class="container">

                        <div class="row">

                            <div class="col-xs-12 col-md-3">
                                <?php if (!empty($categorys)):?>
                                <div class="list-category">

                                    <h3 class="title-list-cat"><?php _e('Danh mục','tn_component'); ?></h3>

                                    <?php woocommerce_khangphuc_archive($categorys); ?>

                                </div>
                                <?php endif; ?>
                                <?php if (!empty($category_brand)):?>
                                <div class="list-category">

                                    <h3 class="title-list-cat"><?php _e('Thương hiệu','tn_component'); ?></h3>

                                    <?php woocommerce_khangphuc_archive($category_brand); ?>

                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-xs-12 col-md-9">

                                <div class="row">

                                    <?php while ( have_posts() ) : the_post(); ?>

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

                                                           data-quantity="1" class="add-to-cart product_type_simple add_to_cart_button_hc ajax_add_to_cart"

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

                            </div>

                        </div>

                    </div>
            </div>

        <?php }

    }

}

