<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 8/8/2018
 * Time: 10:06 AM
 */


global $server, $database, $componentURI, $status, $metadata;
$server = '';
$componentURI = $server . '/components/';
$database = json_decode(file_get_contents($server . '/database.json'), true);
$metadata = json_decode(file_get_contents($server . '/metadata.json'), true);

if(!function_exists('tn_array_sort')){
    function tn_array_sort($array_sort, $key_sort){
        global $database;
        array_multisort( array_column($array_sort, $key_sort), SORT_DESC, $array_sort );
        $arr = [];
        foreach ($array_sort as $key => $data){
            $arr[$key] = array(
                'name' => $database[$key]['name'],
                'description' => $database[$key]['description'],
                'thumbnail' => '/components/'.$key.'/screenshot.jpg',
                'tags' =>  $database[$key]['tags'],
                'downloads' => $data['downloads'],
                'author' => $database[$key]['author'],
                'version' => $database[$key]['version'],
                'reviews' =>  $data['reviews'],
                'stars' =>  $data['stars'],
            );
        }
        return $arr;
    }
}
if(!function_exists('tn_topdownload')){
    function tn_topdownload($array_sort){
        return tn_array_sort($array_sort,'downloads');
    }
}
if(!function_exists('tn_topvote')){
    function tn_topvote($array_sort){


        array_multisort(array_column($array_sort, 'stars'),  SORT_DESC,
            array_column($array_sort, 'downloads'), SORT_DESC,
            $array_sort);
        global $database;
        $arr = [];
        foreach ($array_sort as $key => $data){
            $arr[$key] = array(
                'name' => $database[$key]['name'],
                'description' => $database[$key]['description'],
                'thumbnail' => '/components/'.$key.'/screenshot.jpg',
                'tags' =>  $database[$key]['tags'],
                'downloads' => $data['downloads'],
                'author' => $database[$key]['author'],
                'version' => $database[$key]['version'],
                'reviews' =>  $data['reviews'],
                'stars' =>  $data['stars'],
            );
        }
        return $arr;
    }
}



if (!function_exists('array_find')) {
    function array_find($needle, array $haystack)
    {
        $res = [];
        foreach ($haystack as $key => $value) {
            foreach ($value as $index => $val) {
                if (false !== stripos($val, $needle)) {
                    $res[$key] = $value;
                }

            }

        }
        return $res;
    }
}

if (!function_exists('is_components_exists')) {
    function is_components_exists($key)
    {
        $installeds = get_tncp_setting();
        foreach ($installeds as $index => $installed) {
            if ($index == $key) return true;
        }
        return false;
    }
}

if (!function_exists('downloadComponent')) {
    function downloadComponent($id, $token = "")
    {
        global $componentURI;
        $filename = $id . '.zip';
        if (!remoteFileExists($componentURI . $filename)) {
            $response['status'] = false;
            $response['msg'] = 'Bản component không tồn tại trên server';
            goto endFunc;
        }
        $f = file_put_contents(get_tncp_option('path') . "/components/" . $filename,
            fopen($componentURI . $filename, 'r'), LOCK_EX);
        if (false === $f)
            $response['status'] = false;
        $zip = new ZipArchive;
        $res = $zip->open(get_tncp_option('path') . "/components/" . $filename);
        if ($res === TRUE) {
            $zip->extractTo(get_tncp_option('path') . "/components/" . $id . "/");
            $zip->close();
            $response['status'] = true;
            $response['msg'] = 'Tải component thành công!';
            pushRequestCountDownload($id, $token);

        } else {
            $response['status'] = false;
            $response['msg'] = 'Tải thất bại, vui lòng thử lại sau.';
        }
        unlink(get_tncp_option('path') . "components/" . $filename);
        endFunc:
        return $response;

    }
}
if (!function_exists('deleteComponent')) {
    function deleteComponent($dirPath)
    {
        if (!is_dir($dirPath)) {
            return false;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteComponent($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
        return true;
    }
}
if (!function_exists('pushRequestCountDownload')) {
    function pushRequestCountDownload($id, $token = "")
    {
        global $server;

        $fields = array(
            'id_component' => $id,
            'token' => $token . 'toannang@',
        );
        $fields_string = "";
        //url-ify the data for the POST
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $server);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);

    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach ($database as $index => $value) {
        if (isset($_POST[$index . '_download'])) {

            $status = downloadComponent($index, $_POST['token']);
        }

    }
    if (!empty($_POST['id_inactive_component']) && isset($_POST['deletecomponent'])) {
        $ids = explode(',', $_POST['id_inactive_component']);
        if (!empty($ids)) {
            foreach ($ids as $id) {
                if (deleteComponent(get_tncp_option('path') . "/components/" . $id)) {
                    $status['status'] = true;
                    $status['msg'] = 'Đã dọn dẹp thành công';
                } else {
                    $status['status'] = false;
                    $status['msg'] = 'Có lỗi, vui lòng thử lại sau.';

                }
            }
        }
    }
}
