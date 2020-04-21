<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/8/2018
 * Time: 9:21 AM
 */

if (!class_exists('TNCP_hc_khangphuc_news')){
class TNCP_hc_khangphuc_news extends TNCP_ToanNang{

protected $options = [
    'phuckhang-news' => array(),
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
    <div class="hc_khangphuc_news">
        <?php $news = $this->getOption('phuckhang-news'); ?>
        <?php if (!empty($news)):?>
        <div class="container">
            <div class="row">
                <div class="news-khangphuc-title">
                    <h3>
                        <?php _e('Tin tức','tn_component')?>
                    </h3>
                </div>
                <div class="list-news">
                    <?php $args = array(
                        'posts_per_page'   => 10,
                        'offset'           => 0,
                        'category'         => $news[0]['danh_muc'],
                        'orderby'          => 'date',
                        'order'            => 'DESC',
                        'post_type'        => 'post',
                        'post_status'      => 'publish',
                    );
                    $posts = get_posts( $args );
                    ?>
                    <?php if (count($posts)>1): $post = $posts[0]; ?>
                    <div class="col-md-8 col-sm-12">
                        <div class="news-item first-post">
                            <div class="news_img">
                                <a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?></a>
                            </div>
                            <div class="news_details">
                                <div class="news_name">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </div>
                                <div class="news_excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <div class="news_btn">
                                    <span class="date-post"><i class="fas fa-calendar-alt"></i> <?php the_date( 'd-m-Y' ); ?></span>
                                    <a class="read-more" href="<?php the_permalink(); ?>"><?=__('Xem thêm >>', 'tn_component')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="slick-news">
                        <?php $i =0; foreach ($posts as $post): $i++;?>
                        <?php if ($i>1): ?>
                            <div class="news-item">
                                <div class="news_img">
                                    <a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?></a>
                                </div>
                                <div class="news_details">
                                    <div class="news_name">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </div>
                                    <div class="news_excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <div class="news_btn">
                                        <span class="date-post"><i class="fas fa-calendar-alt"></i> <?php echo get_the_date( 'd-m-Y', $post ); ?></span>
                                        <a class="read-more" href="<?php the_permalink(); ?>"><?=__('Xem thêm >>', 'tn_component')?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php endforeach;?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
<?php }
}
}
