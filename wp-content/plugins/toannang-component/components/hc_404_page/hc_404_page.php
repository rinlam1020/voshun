<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_hc_404_page')){
    class TNCP_hc_404_page extends TNCP_ToanNang{

        protected $options = [

        ];
        function __construct()
        {
            parent::__construct(__FILE__);
            parent::setOptions($this->options);
        }

        /*Add html to Render*/
        public function render(){ ?>
            <section class="hc-404-page">
                <div class="container">
                    <div class="row content-404">
                        <div class="info col-xs-12 col-sm-7 col-sm-push-5">
                            <h1 class="title"><?= __('Không tìm thấy trang','component_tn')?></h1>
                            <p class="desc"><?= __('Có phải bạn nhầm lẫn? Vui lòng kiểm tra lại đường dẫn hoặc', 'component_tn') ?></p>
                            <a href="<?php bloginfo('url'); ?>" title="<?= __('Trở lại trang chủ', 'component_tn'); ?>"><?= __('Trở lại trang chủ', 'component_tn'); ?> <i class="fas fa-undo-alt"></i></a>
                        </div>
                        <div class="img-box col-xs-12 col-sm-5 col-sm-pull-7">
                            <img src="<?php echo $this->getPath()?>images/error-404.jpg" alt="404">
                        </div>
                    </div>
                </div>
            </section>
        <?php }
    }
}



