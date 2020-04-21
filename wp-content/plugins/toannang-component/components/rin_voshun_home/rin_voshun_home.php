<?php

/**
 * Created by PhpStorm componentTn.
 * User: Dang KHoa
 * Date: 10/8/2018
 * Time: 10:30 AM
 */


if (!class_exists('TNCP_rin_voshun_home')) {



   class TNCP_rin_voshun_home extends TNCP_ToanNang {

       protected $options = [
           
       ];

       function __construct() {
           parent::__construct(__FILE__);
           parent::setOptions($this->options);
       }

       /* Add html to Render */

       public function render() {

       		$section = $this->getOption('voshun_home');
       		$travel = $section["travel_service"];
       		$product_cate = $section["product_cat"];
       		$advertising = $section["advertising"];
//Phần Quang Trọng không được xóa, khi bỏ vào src công ty hả mở ra
            ?>




            <!--Viết HTML , chỉ nên viết phần này-->
            <?php
				$url = __FILE__;
				$urls = pathinfo($url);
				$linkDK = $this->getPath();
			?>
            <div class="rin_voshun_home" style="background: url('<?=$linkDK?>/images/background.png');">
            	<div class="sect">
            		
            		<div class="container">
			    		<div class="row">
			    			<div class="col-md-3 col-sm-6 col-xs-12">
				    				<div class="home-sidebar">
				    					<div class="title">Danh Mục Sản Phẩm</div>
				 
				    					<ul class="my-nav">
				    						<?php foreach ($product_cate as $key => $value) { ?>
				    							<?php $terms = get_term($value["category"],'product_cat'); ?>
				    							
				    							<?php if($key==0): ?> 

				    								<li><a data-toggle="tab" href="<?=get_term_link($terms->term_id)?>"><?=$terms->name?></a></li>
				    							<?php else: ?>
													<li><a data-toggle="tab" href="<?=get_term_link($terms->term_id)?>"><?=$terms->name?></a></li>
				    							<?php endif;?>

				    						<?php } ?>
				    						
				    					</ul>
				    				</div>
				    				<div id='cssmenu'>
				    					<div class="title">Danh Mục Sản Phẩm</div>

										<ul class="my-nav">
				    						<?php foreach ($product_cate as $key => $value) { ?>
				    							<?php $terms = get_term($value["category"],'product_cat'); ?>
				    							
				    							<?php if($key==0): ?> 

				    								<li><a data-toggle="tab" href="<?=get_term_link($terms->term_id)?>"><?=$terms->name?></a></li>
				    							<?php else: ?>
													<li><a data-toggle="tab" href="<?=get_term_link($terms->term_id)?>"><?=$terms->name?></a></li>
				    							<?php endif;?>

				    						<?php } ?>
				    						
				    					</ul>
									</div>
				    				<div class="advertisement">
				    					<a href="<?=$advertising["link"] ?>">
				    						<img src="<?=$advertising["hinh"]["url"] ?>">
				    					</a>
				    				</div>
			    			</div>
			    			<div class="col-md-9 col-sm-6 col-xs-12">
			    				<div class="row">
			    					<div class="slick-product fade in active" id="home">
							    					<div class="col-md-12">
							    						<div class="row">
							    						<div class="title">
							    							<h3>Sản phẩm nổi bật</h3>
							    						</div>
							    					</div>
							    					</div>
							    					
							    							<?php 
								    							$product = get_posts(array(
							    						
					                                                                'post_type' => 'product',
					                                                                'meta_query' => array(
					                                                                	array(
																				            'key' => 'featured',
																				            'compare' => '=',
																				            'value' => 1
																				            
																				        )
					                                                                )
					                                                                
							    								)); ?>
							    								<?php foreach ($product as $key => $value) { ?>
							    									<div class="col-md-3 col-sm-6 col-xs-6">
							    										<div class="row">
		    															<div class="item">
													    					<div class="shadow">
														    					<div class="padding">
														    						<div class="img">
														    							<a href="<?php the_permalink($value->ID) ?>">
													    								<?=get_the_post_thumbnail($value->ID,'shop_catalog')?>
													    							</a>
														    						</div>
														    						<div class="desc">
														    							<h6><a href="<?php the_permalink($value->ID) ?>"><?php echo $value->post_title; ?></a></h6>
														    							<div class="price">
														    								<?php $pro = wc_get_product($value->ID ) ?>
														    								<span class="sale"><?=number_format($pro->get_sale_price(),'0',',','.');?>₫</span>
														    								<span class="regular"><?=number_format($pro->get_regular_price(),'0',',','.');?>₫</span>
														    							</div>
														    						</div>
														    					</div>
														    				</div>
													    				</div>
													    				</div>
																	</div>
		    													<?php } ?>
									    				
														
													
													<div class="col-md-12">
														<a href="<?=get_term_link(108)?>" class="seemore">Xem thêm</a>
													</div>
							    				</div>
			    					
			    				
			    				
			    			</div>
			    			</div>
			    			
			    		</div>
			    	</div>
			    	<div class="container service">
			    		<div class="row">
			    			<div class="col-md-12">
			    				<div class="title">Dịch Vụ</div>
			    				<img class="below" src="<?=$linkDK?>/images/title-below.png">
			    			</div>
			    			<div class="col-md-12">
			    				<div class="items">
			    					<?php $service = get_posts(array(
		    						
                                                                'post_type' => 'post',

                                                                'posts_per_page' => 10,
                                                                'orderby' => 'date',

                                                                'order' => 'DESC',


                                                                'tax_query' =>  array(



                                                                    array('taxonomy' => 'category',

                                                                        'field'    => 'id',
                                                                        'terms'    => 62

                                                                    )



                                                                )
		    					)); ?>
		    					<?php foreach ($service as $key => $value) { ?>
		    						<div class="col-md-3 col-sm-6 col-xs-12">
			    						<div class="item">
			    							<div class="img">
			    								<a href="<?php the_permalink($value->ID) ?>">
			    								<?=get_the_post_thumbnail($value->ID,'medium')?>
			    							</a>
			    							</div>
			    							<div class="desc">
			    								<a href="<?php the_permalink($value->ID) ?>"><?php echo $value->post_title; ?></a>
			    							</div>
			    						</div>
			    					</div>
		    					<?php } ?>
			    					

			    				</div>
			    			</div>
			    		</div>
			    	</div>
			    	
            	</div>
		    	<div class="service2">
		    		<div class="container">
			    		<div class="row">
			    			<div class="col-md-12">
			    				<div class="title">Dịch Vụ lữ hành</div>
			    				<img class="below" src="<?=$linkDK?>/images/below2.png">
			    			</div>
			    			<div class="col-md-12">
			    				<div class="items">
									<?php foreach ($travel as $key => $value) { ?>
					
										<?php $ser = $value["post"] ?>
										<div class="col-md-6 col-sm-6 col-xs-12">
				    						<div class="item">
				    							<a href="<?php the_permalink($ser->ID); ?>">
				    								<?=get_the_post_thumbnail($ser->ID,'large')?>
				    							</a>
				    						</div>
										</div>
									<?php } ?>
			    					
									
			    				</div>
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