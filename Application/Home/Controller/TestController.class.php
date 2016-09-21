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
        $url = 'http://localhost/sanitation/index.php/home/company/getCars';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getcars';

        $post_data['companyId'] = '1';
        $post_data['plate'] = '8';

        $post_data['appid'] = '3';
        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getCarInfo() {
        $url = 'http://localhost/sanitation/index.php/home/company/getcarinfo';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getcarinfo';

        $post_data['companyId'] = '1';
//        $post_data['plate'] = '72';

        $post_data['carList'] = '1';
        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getPersons() {
        $url = 'http://localhost/sanitation/index.php/home/person/getPersons';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getcars';

//        $post_data['companyId'] = '1';
//        $post_data['plate'] = '8';

        $post_data['appid'] = '3';
        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getPersonInfo() {
        $url = 'http://localhost/sanitation/index.php/home/person/getPersonInfo';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getPersonInfo';

        $post_data['personList'] = '1,2';
//        $post_data['plate'] = '72';

        $post_data['carList'] = '1';
        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getDistricts() {
        $url = 'http://localhost/sanitation/index.php/home/station/getDistricts';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getPersonInfo';

        $post_data['personList'] = '1,2';
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getStations() {
        $url = 'http://localhost/sanitation/index.php/home/station/getStations';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getPersonInfo';

        $post_data['districtId555'] = 1;
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getStationInfo() {
        $url = 'http://localhost/sanitation/index.php/home/station/getStationInfo';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getPersonInfo';

        $post_data['stationList'] = '1,2';
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getStreets() {
        $url = 'http://localhost/sanitation/index.php/home/Dustbin/getStreets';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getPersonInfo';

        $post_data['stationList'] = '1,2';
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getCollectPoints() {
//        $url = 'http://localhost/sanitation/index.php/home/Dustbin/getCollectPoints';
        $url = 'http://115.159.66.204/sanitation/index.php/home/Dustbin/getCollectPoints';

        $post_data['districtId555'] = '10';
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getDustbinInfo() {
        $url = 'http://localhost/sanitation/index.php/home/Dustbin/getDustbinInfo';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getDustbinInfo';

        $post_data['collectPointList'] = '1,2';
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getCarTrackSegments() {
        $url = 'http://localhost/sanitation/index.php/home/Car/getCarTrackSegments';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getDustbinInfo';

        $post_data['startTime'] = 1473082656;
        $post_data['endTime'] = 1473092731;

//        $post_data['endTime'] = 1473092706;
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getCarTrackPoints() {
        $url = 'http://localhost/sanitation/index.php/home/Car/getCarTrackPoints';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/Car/getCarTrackPoints';

        $post_data['carIds'] = '1,2';
        $post_data['startTimes'] = '1473082656,1473092706';
        $post_data['endTimes'] = '1473082686,1473092731';

//        $post_data['endTime'] = 1473092706;
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getPersonTrackSegments() {
        $url = 'http://localhost/sanitation/index.php/home/person/getPersonTrackSegments';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getDustbinInfo';

        $post_data['startTime'] = 1473082656;
        $post_data['endTime'] = 1473092731;

//        $post_data['endTime'] = 1473092706;
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getPersonTrackPoints() {
        $url = 'http://localhost/sanitation/index.php/home/Person/getPersonTrackPoints';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/Car/getCarTrackPoints';

        $post_data['personIds'] = '1,2,3';
        $post_data['startTimes'] = '1473082656,1473092706,1473082656';
        $post_data['endTimes'] = '1473082686,1473092731,1473092731';

//        $post_data['endTime'] = 1473092706;
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }


    public function getStationTrackSegments() {
        $url = 'http://localhost/sanitation/index.php/home/station/getStationTrackSegments';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getDustbinInfo';

        $post_data['startTime'] = 1473082656;
        $post_data['endTime'] = 1473107656;

//        $post_data['endTime'] = 1473092706;
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getStationTrackPoints() {
        $url = 'http://localhost/sanitation/index.php/home/station/getStationTrackPoints';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getDustbinInfo';

        $post_data['startTime'] = 1473082656;
        $post_data['endTime'] = 1473107656;

        $post_data['stationList'] = '1,2';
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }



    public function getCollectTrackSegments() {
        $url = 'http://localhost/sanitation/index.php/home/Dustbin/getCollectTrackSegments';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getDustbinInfo';

        $post_data['startTime'] = 1466418002;
        $post_data['endTime'] = 1466434002;

//        $post_data['endTime'] = 1473092706;
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getCollectTrackPoints() {
        $url = 'http://localhost/sanitation/index.php/home/Dustbin/getCollectTrackPoints';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getDustbinInfo';

        $post_data['startTime'] = 1466418002;
        $post_data['endTime'] = 1466434002;

        $post_data['collectList'] = '1,2';
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getNewAlertNum() {
        $url = 'http://localhost/sanitation/index.php/home/Alert/getNewAlertNum';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getDustbinInfo';

        $post_data['startTime'] = 1466418002;
        $post_data['endTime'] = 1466434002;

        $post_data['collectList'] = '1,2';
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function getAlerts() {
        $url = 'http://localhost/sanitation/index.php/home/Alert/getAlerts';
//        $url = 'http://115.159.66.204/sanitation/index.php/home/company/getDustbinInfo';


        $post_data['alertStatus'] = 'all';
//        $post_data['plate'] = '72';

        $res = request_post($url, $post_data);
        print_r($res);
    }

    public function addNewAlert() {

        $alert['type'] = 1;
        $alert['content'] = '沪A13872发生故障';
        $alert['report_time'] = time();

        M('alert')->add($alert);
    }

    function test() {
//        $a = '[1,2,4]';
//        $obj = json_decode($a);
//        dump($obj);
        echo date("Y-m-d H:i:s",1466354044);     # 格式化时间戳
    }



}
