<?php

/**
 * Created by PhpStorm componentTn.
 * User: Dang KHoa
 * Date: 10/8/2018
 * Time: 10:30 AM
 */


if (!class_exists('TNCP_rin_voshun_footer')) {



   class TNCP_rin_voshun_footer extends TNCP_ToanNang {

       protected $options = [
           
       ];

       function __construct() {
           parent::__construct(__FILE__);
           parent::setOptions($this->options);
       }

       /* Add html to Render */

       public function render() {
       		$section = $this->getOption('voshun_footer');
       		$info = $section["info"];
       		$stores = $section["branches"];
       		$fanpage = $section["fanpage"];
       		$social = $section["socials"];
       		$map = $section["gmap"];
//Phần Quang Trọng không được xóa, khi bỏ vào src công ty hả mở ra
            ?>




            <!--Viết HTML , chỉ nên viết phần này-->
            <?php
				$url = __FILE__;
				$urls = pathinfo($url);
				$linkDK = $this->getPath();
			?>
            <div class="rin_xuongmayOH_footer" style="background: url('<?=$linkDK?>/images/background.png')">
            	<div class="opacity">
	            	<div class="container">
	            		<div class="row">
	            			<div class="col-md-4 col-sm-12 col-xs-12 inf">
	            				<div class="item">
	            						
	            					<div class="title">thông tin công ty<!-- <span>xưởng may</span><img src="<?=$linkDK?>/images/ft_logo.png"> --></div>
	            					<div class="desc">
	            						<span><h6>Địa Chỉ : <?=$info["address"] ?></h6></span>
	            						<span><h6>Điện Thoại:<b><?=$info["phone"] ?> - <?=$info["phone2"] ?></b></h6></span>
	            						<span><h6>Email: <?=$info["email"] ?></h6></span>
	            						<span><h6>Website : <?=$info["website"] ?></h6></span>
	            					</div>
	            				</div>
								<div class="item">
	            					
	            					<div class="title">Đăng ký nhận tin<!-- <span>xưởng may</span><img src="<?=$linkDK?>/images/ft_logo.png"> --></div>
	            					<div class="desc">
	            						<span class="titleform"><h6>Hãy nhập email của bạn để nhận được thông tin mới nhất từ chúng tôi !</h6></span>
	            						<?= do_shortcode( '[gravityform id="3" title="false" description="false" ajax="true"]' ); ?>
	            					</div>
	            				</div>
	            			</div>
	            			<div class="col-md-4 col-sm-12 col-xs-12 inf">
	            				
	            				<div class="item col-xs-12 col-sm-12">
	            					<div class="title">hệ thống cửa hàng </div>
	            					<div class="desc">
	            						<ul class="menu-ft">
	            							<?php $st = $stores["store"]; ?>
	            							<?php foreach ($st as $key => $value) { ?>
	            								<li><?=$value["address"]?><img align="middle" src="<?=$linkDK?>/images/mapmarked.png"></li>
	            							<?php } ?>
	            							
	            							
	            						</ul>
	            					</div>
	            				</div>
	            				
	            			</div>
	            			<div class="col-md-4 col-sm-12 col-xs-12 inf">
	            				<div class="item col-sm-12 col-xs-12">
	            					<div class="title">Fanpage</div>
	            					<div class="desc">
	            						<div class="fb-page" data-href="<?=$fanpage?>" data-tabs="timeline" data-width="500" data-height="300" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="<?=$fanpage?>" class="fb-xfbml-parse-ignore"><a href="<?=$fanpage?>">Facebook</a></blockquote></div>
	            					</div>
	            				</div>
	            				<div class="item col-sm-12 col-xs-12">
	            					
	            					<div class="desc">
	            						<ul class="sociallink">
	            							<?php foreach ($social as $key => $value) { ?>
	            								<li><a href="<?=$value["link"]?>"><img src="<?=$value["hinh"]["url"]?>"></a></li>
	            							<?php } ?>
	            							
	            						</ul>
	            					</div>
	            				</div>
	            			</div>

					    </div>
				    </div>
				</div>
		    </div>
		    <?php if(is_page('lien-he')){ ?>
		    <?php }else{ ?>
			<div class="map">
				
	            <div id="bando1" class="acf-map col-md-12 active_bando">
					<div class="marker" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>">
					</div>
				</div>
				
			</div>
				<?php }?>
			<div class="frontline">
				<p>
					<a href="#">Copyright @ 2018 - All right Reserved Sieumypham.com .Designed by Toan Nang Co.,Ltd</a>
				</p>
			</div>
            <!--End HTMl-->


            
            
            
            
<!--phần không xóa-->
            <?php

       }

   }

}