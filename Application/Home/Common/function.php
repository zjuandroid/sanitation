<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/5
 * Time: 23:26
 */
function wrapResult($code, $data = null) {
    $result['code'] = $code;
    $result['message'] = M('errcode')->where($result)->getField('msg');
    $result['data'] = $data;
//	dump($result);
    return json_encode($result, JSON_UNESCAPED_UNICODE);
}

/**
 * 模拟post进行url请求
 * @param string $url
 * @param array $post_data
 */
function request_post($url = '', $post_data = '') {
    if (empty($url) || empty($post_data)) {
        return false;
    }

    $o = "";
    foreach ( $post_data as $k => $v )
    {
        $o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);

    $postUrl = $url;
    $curlPost = $post_data;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);

    return $data;
}

function addChild(&$company, $car) {
    $child['id'] = $car['id'];
    $child['plate'] = $car['plate'];
    $child['type'] = $car['car_type'];
    $child['online'] = $car['car_online'];
    $child['state'] = $car['car_state'];
    $company['children'][] = $child;
}

function p ($array) {
    dump($array, 1, '<pre>', 0);
}