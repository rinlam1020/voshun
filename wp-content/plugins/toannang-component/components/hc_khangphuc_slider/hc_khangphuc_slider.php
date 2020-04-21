<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_hc_khangphuc_slider')){
class TNCP_hc_khangphuc_slider extends TNCP_ToanNang{

protected $options = [
    'slider' => '',
];
function __construct()
{
    parent::__construct(__FILE__);
    parent::setOptions($this->options);
}

/*Add html to Render*/
public function render(){ ?>
    <div class="cy_khangphuc_slider">
        <?php echo $this->getOption('slider'); ?>
    </div>
<?php }
}
}
