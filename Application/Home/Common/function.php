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
//	dump(json_encode($result));
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

function addCarChild(&$company, $car) {
    $child['id'] = $car['id'];
    $child['plate'] = $car['plate'];
    $child['type'] = $car['car_type'];
    $child['online'] = $car['car_online'];
    $child['state'] = $car['car_state'];
//    $child['property'] = 'car';
    $company['children'][] = $child;
}

function addPersonChild(&$company, $person) {
    $child['id'] = $person['id'];
    $child['name'] = $person['name'];
    $child['employee_no'] = $person['employee_no'];
    $child['online'] = $person['online'];
    $child['state'] = $person['state'];
//    $child['property'] = 'car';
    $company['children'][] = $child;
}

function addStationChild(&$district, $station) {
    $child['id'] = $station['id'];
    $child['name'] = $station['name'];
    $child['station_no'] = $station['station_no'];
    $child['online'] = $station['online'];
    $child['state'] = $station['state'];
//    $child['property'] = 'car';
    $district['children'][] = $child;
}

function p ($array) {
    dump($array, 1, '<pre>', 0);
}

function getAddress($long, $lat) {
    $postData['output'] = 'json';
    $postData['ak'] = C('BAIDU_MAP_KEY');
    $postData['location'] = $lat.','.$long;

    $res = request_post(C('ADDR_REQ_URL'), $postData);

    $obj = json_decode($res);
    if($obj->status != 0) {
        return '无法获取准确地址';
    }

    return $obj->result->formatted_address;
}
