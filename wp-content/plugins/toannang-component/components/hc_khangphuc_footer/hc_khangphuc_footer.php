<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 7/8/2018

 * Time: 9:21 AM

 */



if (!class_exists('TNCP_hc_khangphuc_footer')){

    class TNCP_hc_khangphuc_footer extends TNCP_ToanNang{



        protected $options = [

            'khangphuc-hotline' => '',

            'khangphuc-logo-footer' => '',

            'khangphuc-name-footer' => '',

            'khangphuc-address' => '',

            'khangphuc-email' => '',

            'khangphuc-website' => '',

            'khangphuc-facebook' => '',

            'khangphuc-map' => '',

            'khangphuc-youtube' => '#',

            'khangphuc-google' => '#',
            'khangphuc-nhantin' => '',

        ];



        function __construct()

        {

            parent::__construct(__FILE__);

            parent::setOptions($this->options);

        }



        /*Add html to Render*/

        public function render(){ ?>

            <div class="hc_khangphuc_footer">

                <footer id="v-footer" class="wow fadeInUp" data-wow-duration="1s">

                    <div class="v-footer-group">

                        <div class="container">

                            <div class="row flex-sp">

                                <div class="col-md-5">

                                    <div class="v-f1">

                                        <div class="logo-footer">

                                            <?php $logo = $this->getOption('khangphuc-logo-footer');?>

                                            <img src="<?php $src_logo = wp_get_attachment_image_src($logo['ID'],'large'); echo $src_logo[0]; ?>">

                                        </div>

                                        <div class="name-footer-site"><?=$this->getOption('khangphuc-name-footer')?></div>

                                        <div class="f-text"><strong><?=__('Địa chỉ :', 'tn_component')?> </strong><?=$this->getOption('khangphuc-address')?></div>

                                        <div class="f-text"><strong><?php _e('Số điện thoại','tn_component')?> : </strong><span><?=$this->getOption('khangphuc-hotline')?></span></div>

                                        <div class="f-text"><strong>Email : </strong><?=$this->getOption('khangphuc-email')?></div>

                                        <div class="f-text"><strong>Website : </strong><?=$this->getOption('khangphuc-website')?></div>

                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <div class="v-f2">

                                        <h4>Đăng ký nhận tin</h4>

                                        <div class="v-f2-text"><?=__('Hãy nhập email của bạn để nhận được thông tin mới nhất từ chúng tôi !', 'tn_component')?></div>

                                        <div class="v-f2-dk">
                                            <?php echo do_shortcode($this->getOption('khangphuc-nhantin'))?>
                                        </div>

                                        <div class="v-f2-mxh">

                                            <a href="<?=$this->getOption('khangphuc-facebook')?>"><img src="<?php echo $this->getPath()?>images/mxh_03.png"></a>

                                            <a href="<?=$this->getOption('khangphuc-youtube')?>"><img src="<?php echo $this->getPath()?>images/mxh_07.png"></a>

                                            <a href="<?=$this->getOption('khangphuc-google')?>"><img src="<?php echo $this->getPath()?>images/mxh_11.png"></a>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="v-f2">

                                        <h4>fanpage</h4>

                                        <div class="v-fanpage">

                                            <div class="fb-page" data-href="<?=$this->getOption('khangphuc-facebook')?>" data-tabs="" data-width="380" data-height="220" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="<?=$this->getOption('khangphuc-facebook')?>" class="fb-xfbml-parse-ignore"><a href="<?=$this->getOption('khangphuc-facebook')?>">Beni.fit</a></blockquote></div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="v-copyright">

                        <?php _e('Copyright @ 2018 - All right Reserved Vaperevolution. Designed by <strong>Toan Nang </strong>', 'tn_component')?>

                    </div>

                </footer>

            </div>

        <?php }

    }

}