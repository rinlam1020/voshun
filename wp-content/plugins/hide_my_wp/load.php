<?php
register_activation_hook(__FILE__, array(&$this, 'on_activate_callback'));
register_deactivation_hook(__FILE__, array(&$this, 'on_deactivate_callback'));

$can_deactive = false;
if (isset($_COOKIE['hmwp_can_deactivate']) && preg_replace("/[^a-zA-Z]/", "", substr(NONCE_SALT, 0, 8)) == preg_replace("/[^a-zA-Z]/", "", $_COOKIE['hmwp_can_deactivate']))
    $can_deactive = true;

//may also need to change mute-sceamer
$this->short_prefix = preg_replace("/[^a-zA-Z]/", "", substr(NONCE_SALT, 0, 6)) . '_';

//Fix a WP problem caused by filters order for deactivation
if (isset($_GET['action']) && $_GET['action'] == 'deactivate' && isset($_GET['plugin']) && $_GET['plugin'] == self::main_file && is_admin() && $can_deactive) {
    update_option(self::slug . '_undo', get_option(self::slug));
    delete_option(self::slug);
}

if ((isset($_POST['action']) && $_POST['action'] == 'deactivate-selected') || (isset($_POST['action2']) && $_POST['action2'] == 'deactivate-selected') && is_admin() && $can_deactive) {
    $plugins = isset($_POST['checked']) ? (array)$_POST['checked'] : array();
    foreach ($plugins as $plugin)
        if ($plugin == self::main_file)
            delete_option(self::slug);
}

include_once('lib/class.helper.php');
$this->h = new PP_Helper(self::slug, self::ver);
$this->h->check_versions('5.0', '3.4');
if (is_admin() || $can_deactive)
    $this->h->register_messages();




///if (!$recent_message_last || current_time('timestamp', 1) > strtotime( '+70 hours', strtotime($recent_message_last))) {

   /* if (is_multisite())
        $opt = get_blog_option(SITE_ID_CURRENT_SITE, 'hide_my_wp');
    else
        $opt = get_option('hide_my_wp');

global $wpdb;
    if ($opt['enable_ids'] && $opt['help_trust_network']) {
        //distinct on field1 *
        echo 'ppppppp0';
        $sql = 'SELECT name,value,ip,impact, created FROM ' . $wpdb->prefix . 'hmwp_ms_intrusions WHERE created > date_sub(now(), interval 3 day) GROUP BY ip,value ';
        $intrusions = json_encode($wpdb->get_results($sql));
        $intrusions = urlencode($intrusions);
        echo 'ppppppp';
    } else {
        $intrusions = '';
    }

    $url = 'ht' . 'tp:/' . '/api.wpwave.com/important_message.php';
    $args = array('site' => urlencode(str_replace('ht' . 'tp:/' . '/', '', home_url())),
        'wp_ver' => '54', 'theme' => urlencode($theme), 'plugin' => 'sss', 'ver' => '2', 'li' => '12', 'intrusions' => $intrusions, 'last'=>strtotime(get_option('pp_important_messages_last')));
//intrusions
    $url=add_query_arg($args,$url);
    echo 'sss'.$url;

    $data = @wp_remote_post($url);
    print_r($data);*/
//}



//$this->opt('db_ver');

$sub_installation = trim(str_replace(home_url(), '', site_url()), ' /');

if ($sub_installation && substr($sub_installation, 0, 4) != 'http')
    $this->sub_folder = $sub_installation . '/';

$this->is_subdir_mu = false;
if (is_multisite())
    $this->is_subdir_mu = true;
if ((defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL) || (defined('VHOST') && VHOST == 'yes'))
    $this->is_subdir_mu = false;

if (is_multisite() && !$this->sub_folder && $this->is_subdir_mu)
    $this->sub_folder = ltrim(parse_url(trim(get_blog_option(BLOG_ID_CURRENT_SITE, 'home'), '/') . '/', PHP_URL_PATH), '/');


if (is_multisite() && !$this->blog_path && $this->is_subdir_mu) {
    global $current_blog;
    $this->blog_path = str_replace($this->sub_folder, '', $current_blog->path); //has /
}

if (is_admin()) {
    include_once('lib/class.settings-api.php');
    add_action('init', array(&$this, 'register_settings'), 5);
}


if (is_multisite())
    $this->options = get_blog_option(BLOG_ID_CURRENT_SITE, self::slug);
else
    $this->options = get_option(self::slug);

if (is_admin() && $can_deactive)
    $this->load_this_plugin_first();

if (!isset($_GET['style_wrapper']) && !isset($_GET['parent_wrapper']) && !isset($_GET['get_wrapper']) && !isset($_GET['style_internal_wrapper']) && !isset($_GET['script_internal_wrapper']) && isset($this->options['full_hide']) && $this->options['full_hide'])
    setcookie($this->access_cookie(), 1, time() + 60 * 60 * 3);//3 hour

$block_ip = false;
if ($this->opt('blocked_ips') || ($this->opt('trust_network'))) {
    $rules = get_option('trust_network_rules');
    $banned_ips = array_merge(explode(',', $this->opt('blocked_ips')), explode(',', $rules['ip']));
    if ($banned_ips) {
        foreach ($banned_ips as $ip) {
            if ($this->netMatch($ip, $_SERVER['REMOTE_ADDR']))
                $block_ip = true;
        }
    }

    $banned_params = explode(',', $rules['param']);

    if ($banned_params) {
        foreach ($banned_params as $param) {
            $q = explode('=',  trim($param,' '));
            if (isset($q[1])){
                $q[0] = trim($q[0],' ');
                $q[1] = trim($q[1],' ');

                if (isset($_REQUEST[$q[0]]) && !is_array($_REQUEST[$q[0]]))
                    if (strtolower(trim($_REQUEST[$q[0]],' '))==strtolower($q[1]))
                        $block_ip = true;
            }
        }
    }

    if ($block_ip){
        if (!isset($rules['counter']))
            $rules['counter'] = 0;
        $rules['counter']++;
        update_option('trust_network_rules', $rules);
    }

}

if (defined('W3TC') && trim($this->opt('new_content_path'),' /') && trim($this->opt('new_content_path'), '/ ')!='wp-content')
    if ($this->h->str_contains($_SERVER['REQUEST_URI'], trim($this->opt('new_content_path'),' /'). '/cache/minify/'))
        $_SERVER['REQUEST_URI']= str_replace('inc', 'wp-content', $_SERVER['REQUEST_URI']);


if ($this->opt('blocked_countries')) {
    $ccode = $this->h->countryCode();
    foreach (explode(',', $this->opt('blocked_countries')) as $country) {
        if ($ccode == strtoupper(trim($country, ' ')))
            $block_ip = true;
    }
}

if (!$can_deactive && $block_ip) {
    status_header( 404 );
    nocache_headers();
    echo $this->opt('blocked_ip_message');
    die;
}

if ($this->opt('enable_ids')) {
    include_once('lib/mute-screamer/mute-screamer.php');

    if (!$this->h->str_contains($this->opt('exception_fields'), 'REQUEST.remember_%')) {
        $opts = get_option(self::slug);
        $opts['exception_fields'] = $opts['exception_fields'] . "\n" . "REQUEST.remember_%";
        update_option(self::slug, $opts);
    }
}

add_filter('pp_settings_api_filter', array(&$this, 'pp_settings_api_filter'), 100, 1);
add_action('pp_settings_api_reset', array(&$this, 'pp_settings_api_reset'), 100, 1);
add_action('init', array(&$this, 'init'), 1);
add_action('wp', array(&$this, 'wp'));
add_action('generate_rewrite_rules', array(&$this, 'add_rewrite_rules'));
add_filter('404_template', array(&$this, 'custom_404_page'), 10, 1);
add_filter('the_content', array(&$this, 'post_filter'));
add_action('admin_notices', array(&$this, 'admin_notices'));

add_filter('posts_request', array(&$this, 'disable_main_wp_query'), 110, 2 );
add_action('wp', array(&$this, 'global_assets_filter'));

if (isset($_GET['die_message']) && is_admin())
    add_action('admin_init', array(&$this, 'die_message'), 1000);

if ((is_admin() || $can_deactive)) {
    require 'lib/plugin-update/plugin-update-checker.php';
    $HMWP_UpdateChecker = PucFactory::buildUpdateChecker(
        'http://api.wpwave.com/hide_my_wp.json',//li will be added automatically
        __DIR__.'/hide-my-wp.php',
        'hide-my-wp',
        100 //4days + manual and auto checks in several places (7 days when there's an update)!
    );
    $HMWP_UpdateChecker->throttleRedundantChecks = true;
    $HMWP_UpdateChecker->addQueryArgFilter(array(&$this, 'update_attr'));
}

//compatibility with social login
if ($this->opt('disable_directory_listing')) {
    defined('WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL')
    || define('WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL', plugins_url() . '/wordpress-social-login');
    defined('WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL')
    || define('WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL', WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/hybridauth/index.php');
}

if (is_multisite())
    add_action('network_admin_notices', array(&$this, 'admin_notices'));

if ($this->opt('antispam')) {
    add_action('init', array(&$this, 'spam_blocker'), 1);
    add_action('comment_form_default_fields', array(&$this, 'spam_blocker_fake_field'), 1000);
}

if ($this->opt('login_query'))
    $login_query = $this->opt('login_query');
else
    $login_query = 'hide_my_wp';


if (!$can_deactive && $this->opt('hide_wp_admin') && $this->opt('hide_other_wp_files') && $this->h->ends_with($_SERVER['PHP_SELF'], 'customize.php') && (!isset($_GET[$login_query]) || $_GET[$login_query] != $this->opt('admin_key')))
    $this->block_access();

if ($this->opt('replace_mode') == 'quick' && !is_admin() && !isset($_GET['die_message'])) {
//root
    add_filter('plugins_url', array(&$this, 'partial_filter'), 1000, 1);
    add_filter('bloginfo', array(&$this, 'partial_filter'), 1000, 1);
    add_filter('stylesheet_directory_uri', array(&$this, 'partial_filter'), 1000, 1);
    add_filter('template_directory_uri', array(&$this, 'partial_filter'), 1000, 1);
    add_filter('script_loader_src', array(&$this, 'partial_filter'), 1000, 1);
    add_filter('style_loader_src', array(&$this, 'partial_filter'), 1000, 1);

    add_filter('stylesheet_uri', array(&$this, 'partial_filter'), 1000, 1);
    add_filter('includes_url', array(&$this, 'partial_filter'), 1000, 1);
    add_filter('bloginfo_url', array(&$this, 'partial_filter'), 1000, 1);

    if (!$this->is_permalink()) {
        add_filter('author_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('post_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('page_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('attachment_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('post_type_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('get_pagenum_link', array(&$this, 'partial_filter'), 1000, 1);

        add_filter('category_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('tag_link', array(&$this, 'partial_filter'), 1000, 1);

        add_filter('feed_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('category_feed_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('tag_feed_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('taxonomy_feed_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('author_feed_link', array(&$this, 'partial_filter'), 1000, 1);
        add_filter('the_feed_link', array(&$this, 'partial_filter'), 1000, 1);

    }
}

if ($this->opt('email_from_name'))
    add_filter('wp_mail_from_name', array(&$this, 'email_from_name'));


if ($this->opt('email_from_address'))
    add_filter('wp_mail_from', array(&$this, 'email_from_address'));


if ($this->opt('hide_wp_login')) {
    add_action('site_url', array(&$this, 'add_login_key_to_action_from'), 101, 4);
    remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);
    add_filter('login_url', array(&$this, 'add_key_login_to_url'), 101, 2);
    add_filter('logout_url', array(&$this, 'add_key_login_to_url'), 101, 2);
    add_filter('lostpassword_url', array(&$this, 'add_key_login_to_url'), 101, 2);
    add_filter('register', array(&$this, 'add_key_login_to_url'), 101, 2);

//since 4.5
    add_filter('comment_moderation_text', array(&$this, 'add_key_login_to_messages'), 101, 2);
    add_filter('comment_notification_text', array(&$this, 'add_key_login_to_messages'), 101, 2);

    add_filter('wp_logout', array(&$this, 'correct_logout_redirect'), 101);

    add_filter('wp_redirect', array(&$this, 'add_key_login_to_url'), 101, 2);
}

add_action('after_setup_theme', array(&$this, 'ob_starter'), -100001);
//add_action('shutdown',  array(&$this, 'do_shutdown'), 110);

//Fix WP Fastest cache
// if  (defined('WPFC_WP_PLUGIN_DIR') && !is_admin())
//    add_action('after_setup_theme',array(&$this, 'ob_starter') , 100001);

// Fix wp-rocket_cache problem!
//if (WP_CACHE && defined('WP_ROCKET_VERSION'))
//  add_filter('rocket_buffer',  array(&$this, 'global_html_filter'), -10000);

// Fix hyper_cache problem!
if (WP_CACHE && function_exists('hyper_cache_sanitize_uri'))
    add_filter('cache_buffer', array(&$this, 'global_html_filter'), -100);



add_action('admin_enqueue_scripts', array($this, 'admin_css_js'));
// add_action( 'wp_enqueue_scripts', array( $this, 'css_js' ) );

if (function_exists('bp_is_current_component'))
    add_action('bp_uri', array($this, 'bp_uri'));

if ($this->opt('replace_wpnonce')) {
    if (isset($_GET['_nonce']))
        $_GET['_wpnonce'] = $_GET['_nonce'];

    if (isset($_POST['_nonce']))
        $_POST['_wpnonce'] = $_POST['_nonce'];

    $this->preg_replace_old[] = "/('|\")_wpnonce('|\")/";
    $this->preg_replace_new[] = "'_nonce'";
}





?>