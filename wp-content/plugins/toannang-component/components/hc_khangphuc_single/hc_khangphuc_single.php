<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_hc_khangphuc_single')){
    class TNCP_hc_khangphuc_single extends TNCP_ToanNang{

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
            <div class="hc-khangphuc-single">
                <div class="container">
                    <div class="row section-featured">
                        <div class="col-sm-9">
                            <h1 class="title-single"><?php the_title(); ?></h1>
                            <div class="content-single">
                               <?php the_content(); ?>
                            </div>
                            <div class="fonlica_page_tag">
                                <?php $args = array(
                                    'number' => 10,
                                );?>
                                <?php $tags_array = get_tags( $args );?>
                                <?php if($tags_array):?>
                                    <strong><i class="fas fa-tags"></i> </strong>
                                    <?php foreach ($tags_array as $tag) :?>
                                        <a href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo $tag->name; ?></a>
                                    <?php endforeach; ?>
                                <?php endif?>
                            </div>
                            <div class="fbcomment" >
                                <div class="fb-comments" data-width="100%" data-href="<?php echo get_permalink($post->ID)?>" data-numposts="5"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <?php  dynamic_sidebar('sidebar-khangphuc-hc'); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    }
}
