<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_hc_khangphuc_archive')){
    class TNCP_hc_khangphuc_archive extends TNCP_ToanNang{

        protected $options = [
        ];

        function __construct()
        {
            parent::__construct(__FILE__);
            parent::setOptions($this->options);
        }

        /*Add html to Render*/
        public function render(){
            global $post;
            ?>
            <div class="hc-khangphuc-archive">
                <div class="page_title">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <h1 class="title-archive"><?php the_archive_title(); ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row section-featured">
                        <?php while(have_posts()): the_post();?>
                                <div class="col-sm-6">
                                    <div class="archive_item">
                                        <div class="archive_item_img">
                                            <a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?></a>
                                        </div>
                                        <div class="archive_item_details">
                                            <div class="archive_item_name">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </div>
                                            <div class="archive_item_excerpt">
                                                <?php the_excerpt(); ?>
                                            </div>
                                            <div class="archive_item_btn">
                                                <a href="<?php the_permalink(); ?>"><?=__('Xem thêm', 'tn_component')?></a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            <?php endwhile;?>

                        <!-- end col-sm-6 -->
                    </div>

                    <div class="text-center pagination-wrapper">
                        <?php wp_pagenavi(); ?>
                    </div>
                </div>
            </div>
        <?php }
    }
}
