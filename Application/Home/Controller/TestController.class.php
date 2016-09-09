<?php

namespace Home\Controller;

use Think\Controller;
use Org\Util\Date;

class TestController extends Controller{

    /**
     *  作用：产生随机字符串，不长于32位
     */
    function createNoncestr( $length = 32 )
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        echo $str;
    }

    public function getcompanies(){
        $url = 'http://localhost/sanitation/index.php/home/company/getcompanies';
//        $post_data['appid']       = '10';
//        $post_data['appkey']      = 'cmbohpffXVR03nIpkkQXaAA1Vf5nO4nQ';
//        $post_data['username'] = 'chwang';
//        $post_data['password']    = '123456';

        $post_data['managerId'] = '1';
//        $post_data['password']    = '111111';
//        $post_data['email']    = 'zsjs123@126.com';


        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getCars() {
//        $url = 'http://localhost/sanitation/index.php/home/company/getCars';
        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getcars';

        $post_data['companyId'] = '1';
        $post_data['plate'] = '8';

        $post_data['appid'] = '3';
        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getCarInfo() {
//        $url = 'http://localhost/sanitation/index.php/home/company/getcarinfo';
        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getcarinfo';

//        $post_data['companyId'] = '3';
//        $post_data['plate'] = '72';

        $post_data['carList'] = '';
        $res = request_post($url, $post_data);
        print_r($res);
    }

    function test() {
//        $a = '[1,2,4]';
//        $obj = json_decode($a);
//        dump($obj);
        echo date("Y-m-d H:i:s",1466354044);     # 格式化时间戳
    }



}