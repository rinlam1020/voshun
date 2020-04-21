<?php

/**
 * Created by PhpStorm componentTn.
 * User: Dang KHoa
 * Date: 10/8/2018
 * Time: 10:30 AM
 */


if (!class_exists('TNCP_rin_voshun_newsvideo')) {



   class TNCP_rin_voshun_newsvideo extends TNCP_ToanNang {

       protected $options = [
           
       ];

       function __construct() {
           parent::__construct(__FILE__);
           parent::setOptions($this->options);
       }

       /* Add html to Render */

       public function render() {
       		$section = $this->getOption('voshun_home');
       		$video = $section["video"];
//Phần Quang Trọng không được xóa, khi bỏ vào src công ty hả mở ra
            ?>




            <!--Viết HTML , chỉ nên viết phần này-->
            <?php
				$url = __FILE__;
				$urls = pathinfo($url);
				$linkDK = $this->getPath();
			?>
            <div class="rin_voshun_newsvideo" style="background: url('<?=$linkDK?>/images/background.png');">
		    	<div class="container">
		    		<div class="row">
		    			<div class="col-md-12 top">
			    				<div class="title">Tin tức</div>
			    				<img class="below" src="<?=$linkDK?>/images/title-below.png">
			    			</div>
		    			<div class="col-md-12 padding50">
		    				<div class="item-feed">
		    					<?php $news = get_posts(array(
		    						
                                                                'post_type' => 'post',

                                                                'posts_per_page' => 6,
                                                                'orderby' => 'date',

                                                                'order' => 'DESC',


                                                                'tax_query' =>  array(



                                                                    array('taxonomy' => 'category',

                                                                        'field'    => 'id',
                                                                        'terms'    => 1

                                                                    )



                                                                )
		    					)); ?>
		    					<?php foreach ($news as $key => $value) { ?>
		    						<div class="col-md-4 ">
				    					<div class="item">
				    						<div class="img">
				    							<a href="<?php the_permalink($value->ID) ?>"><?=get_the_post_thumbnail($value->ID,'medium')?></a>
				    						</div>
				    						<div class="desc">
				    							<h6><a href="<?php the_permalink($value->ID) ?>"><?php echo $value->post_title; ?></a></h6>
				    							<span class="date"><?php the_time('G:i'); ?> | <?=get_the_date('Y-m-d',$value->ID)?></span>
				    							<p><?php echo $value->post_content; ?></p>
				    						</div>
				    						
				    					</div>
				    				</div>
		    					<?php } ?>
		    					
		    				</div>
		    			</div>
		    		</div>
		    	</div>
		    	<div class="container">
		    		<div class="row">
		    			<div class="col-md-12 top">
			    				<div class="title">Video</div>
			    				<img class="below" src="<?=$linkDK?>/images/title-below.png">
			    			</div>
		    			<div class="col-md-12 padding50">
		    				<div class="slick-video">
		    					<?php foreach ($video as $key => $value) { ?>
		    						<div>
		    							<?php 
		    								$ytlink = $value["youtube"];
                                                        preg_match('/embed(.*?)?feature/', $ytlink, $matches_id );
                                                                        $id = $matches_id[1];
                                                                        $idvd = str_replace( str_split( '?/' ), '', $id );
		    							?>
			    						
			    						<iframe id="frm" class="vid_frame" src="http://www.youtube.com/embed/<?=$idvd?>?rel=0" frameborder="0"></iframe>
			    					</div>
		    					<?php } ?>
		    					
		    				</div>
		    			</div>
		    		</div>
		    	</div>
		    </div>
            <!--End HTMl-->


            
            
            
            
<!--phần không xóa-->
            <?php

       }

   }

}