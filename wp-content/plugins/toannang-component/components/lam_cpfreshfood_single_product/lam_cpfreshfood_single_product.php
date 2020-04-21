<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_lam_cpfreshfood_single_product')){
    class TNCP_lam_cpfreshfood_single_product extends TNCP_ToanNang{

        protected $options = [
            'single-about' => ''
        ];
        function __construct()
        {
            parent::__construct(__FILE__);
            parent::setOptions($this->options);
        }


        /*Add html to Render*/
        public function render(){ ?>
            <div id="lam_cpfreshfood_single_product">
                <div class="container">

                    <?php //wc_get_template_part( 'content', 'single-product' ); ?>
                    <?php require(__DIR__.'/woocommerce/content-single-product.php');?>
                </div>

            </div>

        <?php }
}
}



