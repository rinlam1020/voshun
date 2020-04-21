<?php

/**
 * Author: BINHCAO
 * Date: 11-07-2018
 */

global $autoupdate_config;
$file_inc = file_get_contents(str_replace('\\', '/', __DIR__ . '/autoupdate.json'));
$autoupdate_config = json_decode($file_inc);

function is_dev()
{
    global $autoupdate_config;
    return $autoupdate_config->type == 'dev' ? true : false;
}

function remoteFileExists($url)
{
    $curl = curl_init($url);
    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);
    //do request
    $result = curl_exec($curl);
    $ret = false;
    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($statusCode == 200) {
            $ret = true;
        }
    }
    curl_close($curl);
    return $ret;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_REQUEST['act']) && $_REQUEST['act'] == 'update') {
        $response = array('filename' => $file_name);
        $current_version = get_tncp_option('version');
        $update_data = json_decode(file_get_contents($autoupdate_config->url));
        if ($update_data->version === $current_version && $autoupdate_config->type != 'dev') {
            $response['status'] = false;
            $response['msg'] = 'Bản cập nhật không khả dụng.';
            goto endFunc;
        }
        $file_name = 'update_' . md5("toannang-component-plugin-v" . $_REQUEST['version']) . ".zip";

        $file_host = "toannang-component-plugin-v" . $_REQUEST['version'] . ".zip";
        if ($autoupdate_config->type == 'dev') {
            $file_name = 'toannang-component-dev.zip';
            $file_host = 'toannang-component-dev.zip';
        }

        if (!remoteFileExists($autoupdate_config->url . "/path/" . $file_host)) {
            $response['status'] = false;
            $response['msg'] = 'Phiên bản cập nhật không tồn tại.';
            goto endFunc;
        }

        $f = file_put_contents(str_replace('\\', '/', __DIR__ . "/" . $file_name),
            fopen($autoupdate_config->url . "/path/" . $file_host, 'r'), LOCK_EX);
        if (false === $f)
            $response['status'] = false;
        $zip = new ZipArchive;
        $res = $zip->open(str_replace('\\', '/', __DIR__ . '/' . $file_name));
        if ($res === TRUE) {
            $zip->extractTo(str_replace('\\', '/', __DIR__));
            $zip->close();
            $response['status'] = true;
            $response['msg'] = 'Cập nhật thành công!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Cập nhật thất bại, vui lòng thử lại sau.';
        }
        unlink(str_replace('\\', '/', __DIR__ . '/' . $file_name));
        endFunc:
        echo json_encode($response);
        die;
    }
}