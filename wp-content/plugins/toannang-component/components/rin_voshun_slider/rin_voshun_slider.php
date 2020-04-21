<?php

/**
 * Created by PhpStorm componentTn.
 * User: Dang KHoa
 * Date: 10/8/2018
 * Time: 10:30 AM
 */


if (!class_exists('TNCP_rin_voshun_slider')) {



   class TNCP_rin_voshun_slider extends TNCP_ToanNang {

       protected $options = [
           
       ];

       function __construct() {
           parent::__construct(__FILE__);
           parent::setOptions($this->options);
       }

       /* Add html to Render */

       public function render() {

       		$section = $this->getOption('voshun_home');
       		$slider = $section['slider'];
//Phần Quang Trọng không được xóa, khi bỏ vào src công ty hả mở ra
            ?>




            <!--Viết HTML , chỉ nên viết phần này-->
            <?php
				$url = __FILE__;
				$urls = pathinfo($url);
				$linkDK = $this->getPath();
			?>
			<link rel="stylesheet" href="<?=$linkDK?>/assets/inc/fontawesome-animation.css">
            <div class="rin_xuongmayOH_slider" >
		    	<div class="slider_main">
		    		<?php foreach ($slider as $key => $value) { ?>
		    			<div>
		    				<a href="<?=$value["link"]?>">
				    			<img src="<?=$value["hinh"]["url"]?>" alt="<?=$value["title"]?>" title="<?=$value["title"]?>">
				    		</a>
			    		</div>
		    		<?php } ?>
		    		
		    	</div>
		    </div>
			
            <!--End HTMl-->


            
            
            
            
<!--phần không xóa-->
            <?php

       }

   }

}