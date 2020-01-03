<?php
/**
 * curl方法
 * @param $url  //http地址
 * @param string $post //post数据
 * @param array $header //请求头
 * @param int $timeout //超时时间
 * @return bool|mixed
 */

function http_request($url, $post = '',$header=array(), $timeout = 30)
{
    if (empty($url)) {
        return false;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    if($header==true){
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    }
    if ($post != '' && !empty($post)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}