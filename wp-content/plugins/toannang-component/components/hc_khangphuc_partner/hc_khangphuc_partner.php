<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_hc_khangphuc_partner')){
class TNCP_hc_khangphuc_partner extends TNCP_ToanNang{

protected $options = [
    'list-images' => array(),
];
function __construct()
{
    parent::__construct(__FILE__);
    parent::setOptions($this->options);
}

/*Add html to Render*/
public function render(){ ?>
    <div class="hc_khangphuc_partner">
        <?php $partner = $this->getOption('list-images'); ?>
        <?php if (!empty($partner)):?>
        <div class="container">
            <div class="row">
                <div id="hc-khangphuc-partner">
                <?php foreach ($partner as $image): ?>
                    <div class="banner_item">
                        <a href="<?=$image['lien_ket']?>"><img src="<?=$image['hinh_anh']['sizes']['medium']?>"></a>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
<?php }
}
}
