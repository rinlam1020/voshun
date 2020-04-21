<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_th_anpha_single_video')){
    class TNCP_th_anpha_single_video extends TNCP_ToanNang{

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
    <div class="wrapper th_anpha_single_video style1" id="single-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 widget-sidebars" id="single-sidebar">
                    <aside id="text-2" class="widget widget_text wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
                        <h3 class="widget-title text-center">Danh mục</h3>
                        <div class="box-content">
							<ul class="wigdet-menu">
                                <?php

                                $parent_cat_arg = array('hide_empty' => false, 'parent' => 0 );
                                $parent_cat = get_terms('category',$parent_cat_arg);//category name

                                foreach ($parent_cat as $catVal) { ?>
                                    <li class="lv1"> <a href="<?php echo  get_term_link($catVal); ?>"><?php echo $catVal->name; ?></a> </li>


                                    <?php $random_query = new WP_Query(array(
                                            'posts_per_page' => 3,
                                            'orderby' => 'date',
                                            'category__in' => $catVal->term_id,
                                    ));
                                     if ( $random_query->have_posts() ) :
                                         echo '<ul class="submenu">';
                                    while ( $random_query->have_posts() ) :
                                            $random_query->the_post();?>
                                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </li>
                                     <?php endwhile;
                                      echo '</ul>';
                                     endif;
                                    }
                                    ?>
                            </ul>
                        </div>
                    </aside>
                </div>
                <div class="col-lg-9" >
                        <div class="content-single">
                             <div class="entry-content">
                                 <?php if(have_posts()) : while(have_posts()): the_post();?>
                                    <div class="entry-header">
                                        <h1 class="entry-title"><?php the_title(); ?></h1>
                                     </div>
									 <div class="content-youtube text-center">
                                        <?php $anpha_iframe_youtbe = get_field('single_link_youtube');
                                        if( $anpha_iframe_youtbe ) {
                                            $string_s = str_replace('watch?v=', 'embed/', $anpha_iframe_youtbe);
                                          
                                            ?>
                                            <iframe width="560" height="315" src="<?php echo $string_s; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                        <?php } ?>
                                    </div>
                                    <div class="entry-contents">
                                        <?php the_content(); ?>
                                        <p class="tags"><?php the_tags(); ?></p>
                                    </div>
                                 <?php endwhile; endif;?>
                             </div>
                            <div class="entry-footer">
                                <div class="related-post">
                                    <?php
                                        $categories = get_the_category(get_the_ID());
                                        if ($categories){
                                            $category_ids = array();
                                            foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
                                            $args=array(
                                                'category__in' => $category_ids,
                                                'post__not_in' => array(get_the_ID()),
                                                'posts_per_page' => 5,
                                            );
                                            $my_query = new wp_query($args);
                                            if( $my_query->have_posts() ): ?>
                                                <h3 class="title-related"><?php _e('Có thể bạn quan tâm','tn_component')?></h3>
                                                <div class="list-related-post regular2 slider slick-slider">
                                                    <?php while ($my_query->have_posts()):$my_query->the_post();
                                                        ?>
                                                        <div class="related-item">
                                                            <?php the_post_thumbnail('size278x170')?>
                                                            <div class="desc">
                                                                <h5><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h5>
                                                            </div>
                                                        </div>

                                                    <?php
                                                    endwhile;  ?>
                                                </div>
                                            <?php endif; wp_reset_query();} ?>
                                    </div>
                                </div>

                            </div>
                    </div>
            </div>
        </div>
    </div>

        <?php }
    }
}