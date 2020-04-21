<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 7/8/2018

 * Time: 9:21 AM

 */



if (!class_exists('TNCP_hc_khangphuc_header')){

class TNCP_hc_khangphuc_header extends TNCP_ToanNang{



protected $options = [

    'categories' => array(),

    'hotline_ban_hang' => '',

    'menu' => '',

];

function __construct()

{

    parent::__construct(__FILE__);

    parent::setOptions($this->options);

}



/*Add html to Render*/

public function render(){ ?>



    <header class="cy_khangphuc_header">

        <div class="bg-repeat-2">

            <div class="container">

            <div class="row">

                <div class="col-xs-12 col-md-3">

                    <div class="logo-container">

                        <?php  if(strlen(az_box_logo_primary())>0){



                            echo  az_box_logo_primary();



                        }else{  ?>



                            <a href="#">



                                <img src="<?php echo $this->getPath()?>images/logo.png">



                            </a>



                        <?php  } ?>

                    </div>

                </div>

                <div class="col-xs-12 col-md-7 col-sm-8">

                    <div class="search-container">

                        <?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>

                        <a href="<?php echo wc_get_cart_url()?>" data-product_total="<?php echo  WC()->cart->get_cart_contents_count(); ?>" title="<?php _e('Giỏ hàng', 'tn_component')?>" class="btn-cart toannang-cart-number-btn">

                            <i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="toannang-cart-number"><?php echo  WC()->cart->get_cart_contents_count(); ?></span>

                        </a>



                    </div>

                </div>

                <div class="col-xs-12 col-md-2 col-sm-4">

                    <a href="#nav-main" title="Menu"class="navbar-toggle collapsed btn-nav-mob visible-xs pull-left" >

                        <span class="sr-only">Toggle navigation</span>

                        <span class="icon-bar"></span>

                        <span class="icon-bar"></span>

                        <span class="icon-bar"></span>

                    </a>

                    <div class="hotline">

                        <a href="tel: 0906 760 089" class="call-now pull-right" title="Hotline">

                            <img src="<?php echo $this->getPath();?>/images/icon.png" class="img-responsive visible-lg pull-left" alt="Icon">

                            <p class="info pull-left">

                                <span><?php _e('Hotline', 'tn_component')?></span>

                                <span><?php echo  $this->getOption('hotline_ban_hang'); ?></span>

                            </p>

                            <div class="clearfix"></div>

                        </a>

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>



            <div class="menu-container hidden-xs">

                <div class="col-xs-12 col-md-9 pull-right">

                    <nav id="nav-main">

                        <?php if(!empty($this->getOption('menu'))){

                            $menus = $this->getOption('menu');

                            hc_get_menu($menus);

                        } ?>

                    </nav>

                </div>

                <div class="clearfix"></div>

            </div>

        </div>

        </div>

    </header>

<?php }

}

}

