<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_hc_khangphuc_page')){
    class TNCP_hc_khangphuc_page extends TNCP_ToanNang{

        protected $options = [
        ];

        function __construct()
        {
            parent::__construct(__FILE__);
            parent::setOptions($this->options);
        }

        /*Add html to Render*/
        public function render(){
            global $post;
            ?>
            <div class="hc-khangphuc-page">
                <div class="page_title">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <h1 class="title-page"><?php the_title()?></h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row margin-6">
                        <div class="col-md-12 padding-6 w100">
                            <div class="page_content">
                                <?php the_content();?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php }
    }
}
