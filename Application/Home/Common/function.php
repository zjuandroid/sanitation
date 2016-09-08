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
    return json_encode($result);
}