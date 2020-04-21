<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_cy_baothy_archive_dichvu')){
    class TNCP_cy_baothy_archive_dichvu extends TNCP_ToanNang{

        protected $options = [

        ];
        function __construct()
        {
            parent::__construct(__FILE__);
            parent::setOptions($this->options);
        }

        /*Add html to Render*/
        public function render(){ ?>

            <?php global $post; ?>
            <div id="cy_baothy_archive_dichvu" class="cy_baothy_archive_dichvu">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center header-title-dv">
                            <h1><?php the_archive_title()?></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="archive-content-wrapper grid">
                                <?php while(have_posts()): the_post();?>
                                  <div class="grid-item col-lg-3 col-md-6 col-xs-12 wow fadeInRight">
                                      <div class="item">
                                        <div class="post-thumbnail">
                                          <a href="<?php echo get_permalink()?>" title="<?php echo get_the_title() ?>">
                                              <img src="
                                                <?php if ( has_post_thumbnail() ) { ?>
                                              <?php echo get_the_post_thumbnail_url(get_the_ID(),'size300x200')?>"/>
                                              <?php }else { ?>
                                                <img src="<?php echo $this->getPath(); ?>/images/default.jpg">
                                             <?php } ?>
                                          </a>
                                        </div>
                                           <h2 class="h2"><a href="<?php echo get_permalink()?>"><?php the_title()?></a></h2>
                                        <p class="info-text fo-avo">
                                            <span><i class="fas fa-calendar-alt"></i> <?php echo get_the_date('d/m/Y',get_the_ID())?>  </span>

                                        </p>
                                        <div class="post_excerpt">
                                            <?php
                                            echo wp_trim_words( get_the_content(), 20, '...' );
                                            ?>
                                        </div>
                                          <div class="view-detail">
                                              <a href="<?php echo get_permalink()?>">Xem thêm <i class="fas fa-chevron-right"></i> </a>
                                          </div>
                                      </div>
                                  </div>
                                <?php endwhile; ?>
                            </div>
                            <div class="pagination">
                                <?php wp_pagenavi();?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php }
    }
}



