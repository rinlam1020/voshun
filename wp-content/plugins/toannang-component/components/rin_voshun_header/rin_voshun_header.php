<?php

/**
 * Created by PhpStorm componentTn.
 * User: Dang KHoa
 * Date: 10/8/2018
 * Time: 10:30 AM
 */


if (!class_exists('TNCP_rin_voshun_header')) {



   class TNCP_rin_voshun_header extends TNCP_ToanNang {

       protected $options = [
           
       ];

       function __construct() {
           parent::__construct(__FILE__);
           parent::setOptions($this->options);
       }

       /* Add html to Render */

       public function render() {

          $section = $this->getOption('voshun_header');

//Phần Quang Trọng không được xóa, khi bỏ vào src công ty hả mở ra
            ?>




            <!--Viết HTML , chỉ nên viết phần này-->
            <?php
				$url = __FILE__;
				$urls = pathinfo($url);
				$linkDK = $this->getPath();
			?>
            
            <header class="rin_voshun_header sticky-header" >
                  <div class="topline">
                        <div class="container">
                              <div class="row row-flex">
                                    <div class="col-md-6 col-sm-6 hidden-xs info">
                                          <p>Địa Chỉ: <?= $section["address"] ?> </p>
                                    </div>
                                    <div class="col-md-3 hidden-sm hidden-xs info">
                                          <p>Email: <?= $section["email"] ?></p>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12 info">
                                          <p><i class="fa fa-phone-volume faa-ring animated"></i><span><a href="tel:<?= $section["phone"] ?>"><?= $section["phone"] ?></a></span><span><a href="tel:<?= $section["phone2"] ?>"><?= $section["phone2"] ?></a></span></p>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="menu">
                  	<div class="container">
                  		<div class="row row-flex">
                  			<div class="col-md-2 col-sm-3 col-xs-3 logo">
                           <?php  if(strlen(az_box_logo_primary())>0){



                            echo  az_box_logo_primary();



                        }else{  ?>



                            <a href="#">



                                <img class="logo" src="<?=$linkDK?>/images/logo.png">



                            </a>



                        <?php  } ?>
      	            			
                  			</div>
                  			<div class="col-md-10 col-sm-9 col-xs-9">
                                          <div class="menu-toggle hidden-md hidden-lg">
                                                <a class="menu_toggle" href="#main-menu"><i class="fa fa-bars"></i></a>
                                          </div>
                                          <?php 
                                            wp_nav_menu(
                                              array('menu' => 'main-menu','container' => 'nav','container_id'=>'main-menu' )
                                            )
                                          ?>
                                          <?php 
                                            wp_nav_menu(
                                              array('menu' => 'main-menu','container' => 'nav','container_id'=>'main-menu2' )
                                            )
                                          ?>
                                          <div id="search" class="search-field">
                                          <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
  <label class="screen-reader-text" for="s"><?php _e( 'Search for:', 'woocommerce' ); ?></label>
  <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Tìm kiếm...&hellip;', 'placeholder', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'woocommerce' ); ?>" />
  <input type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>" />
  <input type="hidden" name="post_type" value="product" />
</form></div>
                  				
                  			</div>
                  		</div>
                  	</div>
                  </div>
		    </header>
			
            <!--End HTMl-->


            
            
            
            
<!--phần không xóa-->
            <?php

       }

   }

} 