<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 7/8/2018

 * Time: 9:21 AM

 */



if (!class_exists('TNCP_hc_khangphuc_breadcrumb')){

class TNCP_hc_khangphuc_breadcrumb extends TNCP_ToanNang{



protected $options = [

    'khangphuc-breadcrumb-banner' => array(),

];

function __construct()

{

    parent::__construct(__FILE__);

    parent::setOptions($this->options);

}



/*Add html to Render*/

public function render(){ ?>

    <div class="hc_khangphuc_breadcrumb">

        <?php $banner = $this->getOption('khangphuc-breadcrumb-banner'); ?>

        <?php if (!empty($banner)):?>

            <?php $src_baner = wp_get_attachment_image_src($banner,'full'); ?>

            <div class="banner-sub-site">

                <div class="bread-crumbs">

                    <div class="container">

                        <?php if(function_exists('az_box_breadCrumbs')){az_box_breadCrumbs();}?>

                    </div>

                </div>

            </div>

        <?php else: ?>

        <div class="container">

            <div class="row">

                <?php if(function_exists('az_box_breadCrumbs')){az_box_breadCrumbs();}?>

            </div>

        </div>

        <?php endif; ?>

    </div>

<?php }

}

}

