<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/5
 * Time: 22:03
 */
namespace Home\Controller;

use Think\Log;


class CarController extends BaseController
{
    public function getCompanies() {
        $managerId =  I('post.userId');

        $dao = M('company');
        $data = $dao->field('ID, COMPANY_NAME')->select();

        $ret['company_list'] = $data;

        echo (wrapResult('CM0000', $ret));
    }

    public function getCars() {
        $companyId = I('post.companyId');
        $plate = I('post.plate');

//        echo('companyId');
//        dump($companyId);
//        echo('plate');
//        dump($plate);
        Log::record(date("Y-m-d H:i:s").' companyid---> '.$companyId);
        Log::record(date("Y-m-d H:i:s").' plate---> '.$plate);

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
        if($plate) {
            $condition['plate'] = array('like', '%'.$plate.'%');
        }

        $dao = M('car');
//        $data = $dao->join('san_company ON car.company_id = company.id')->field('car.id, car.plate, car.car_type, car.car_online, car.car_state, car.company_id, company.company_name')->order('car.company_id')->select();
//        $data = $dao->alias('t1')->join('company t2', 't1.company_id=t2.id')->field('t1.id, t1.plate, t1.car_type, t1.car_online, t1.car_state, t1.company_id, t2.company_name')->select();
        $data = $dao->where($condition)->alias('t1')->join('left join san_company t2 ON t1.company_id=t2.id')->field('t1.id, t1.plate, t1.car_type, t1.car_online, t1.car_state, t1.company_id, t2.company_name')->order('t1.company_id')->select();

        $lastCompanyId = -1;
        $company_list = array();

        foreach($data as $car) {
            if($car['company_id'] != $lastCompanyId) {
                $company['id'] = $car['company_id'];
                $company['name'] = $car['company_name'];
//                $company['property'] = 'company';
                $company['children'] = array();

//                $child['id'] = $car['id'];
//                $child['plate'] = $car['plate'];
//                $child['type'] = $car['car_type'];
//                $child['online'] = $car['car_online'];
//                $child['state'] = $car['car_state'];
//                $company['children'][] = $child;
//                array_push($company['children'], $child);
                addCarChild($company, $car);

                $company_list[] = $company;

                $lastCompanyId = $car['company_id'];
            }
            else {
//                $company = end($company_list);

//                $child['id'] = $car['id'];
//                $child['plate'] = $car['plate'];
//                $child['type'] = $car['car_type'];
//                $child['online'] = $car['car_online'];
//                $child['state'] = $car['car_state'];
//                array_push($company_list[count($company_list)-1]['children'], $child);
                addCarChild($company_list[count($company_list)-1], $car);
            }
        }

        $ret['company_list'] = $company_list;
        echo (wrapResult('CM0000', $ret));
    }

    public function getCarInfo() {
        $carList = I('post.carList');

        if(empty($carList)) {
            exit (wrapResult('CM0000'));
        }

        $dao = M('car');
        $data = $dao->where('t1.id in ('.$carList.')')->alias('t1')->join('left join san_company t2 ON t1.company_id=t2.id')->field('t1.id, t1.plate, t1.car_type, t1.car_online, t1.car_state, t1.cur_long, t1.cur_lat, t1.cur_velocity, t1.cur_oil_amount, t1.update_time, t1.company_id,  t1.video_url, t1.fan_speed, t1.tank_allowance, t1.injector_state, t1.sweep_state, t1.fuel_quantity, t1.need_maintain, t2.company_name')->select();
        $hisDao = M('car_his');
        $i = 0;
        //add for demo
        $startTime = strtotime('2016-10-10');
        $endTime = strtotime('2016-10-11');
        $condition['report_time'] = array(array('egt', $startTime), array('elt', $endTime));
        foreach($data as $car) {
            $car['location'] = getAddress($car['cur_long'], $car['cur_lat']);
//            $car['video_url'] = 'http://115.159.66.204/uploads/video/carMonitor.flv';
            $car['video_url'] = C('VIDEO_ROOT').$car['video_url'];
            $car['car_state'] = $this->getCarStateDes(intval($car['car_state']));
            //add for demo
            $condition['car_id'] = $car['id'];
            $car['preset_trail'] = $hisDao->where($condition)->field('his_long, his_lat')->select();

            $data[$i++] = $car;

        }

//        p($this->getAddress(121.506126,31.245475));
        $ret['car_list'] = $data;
        echo (wrapResult('CM0000', $ret));
    }

    private function getCarStateDes($state) {
        switch ($state) {
            case 0:
                $des = '良好';
                break;
            default:
                //不用未知
//                $des = '未知';
                $des = '良好';
                break;
        }

        return $des;
    }

    public function getCarTrackSegments() {
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');
        $companyId = I('post.companyId');
        $plate = I('post.plate');


        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_TIME_SPAN')) {
            exit(wrapResult('CM0002'));
        }
        else {
//            $condition['report_time'] = array('egt', $startTime);
//            $condition['report_time'] = array('elt', $endTime);
            $condition['report_time'] = array(array('egt', $startTime), array('elt', $endTime));
        }

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
        if($plate) {
            $condition['plate'] = array('like', '%'.$plate.'%');
        }

        $dao = M('car_his');
        $data = $dao->where($condition)->alias('t1')->join('left join san_car t2 ON t1.car_id=t2.id')->join('left join san_company t3 ON t2.company_id=t3.id')->field('t1.id, t1.car_id, t1.report_time, t1.his_long, t1.his_lat, t2.plate, t2.company_id, t3.company_name')->order('t2.company_id, t1.car_id, t1.report_time')->select();

//        dump($data);
        $lastCompanyId = -100;
        $lastCarId = -100;
        $reportTime = -100;
        $company_list = array();

        foreach($data as $carPoint) {
            $company = null;
            $car = null;
            $seg = null;
//            dump($carPoint['company_id']);
//            dump($lastCompanyId);
            if($carPoint['company_id'] != $lastCompanyId) {
//                dump('1----');
                $seg['startTime'] = $carPoint['report_time'];
                $seg['endTime'] = $carPoint['report_time'];

                $car['id'] = $carPoint['car_id'];
                $car['plate'] = $carPoint['plate'];
                $car['children'][] = $seg;

                $company['id'] = $carPoint['company_id'];
                $company['name'] = $carPoint['company_name'];
                $company['children'][] = $car;

                $company_list[] = $company;

                $lastCompanyId = $carPoint['company_id'];
                $lastCarId = $carPoint['car_id'];
                $reportTime = $carPoint['report_time'];
            }
            else if($carPoint['car_id'] != $lastCarId){
//                dump('2------');
                $seg['startTime'] = $carPoint['report_time'];
                $seg['endTime'] = $carPoint['report_time'];

                $car['id'] = $carPoint['car_id'];
                $car['plate'] = $carPoint['plate'];
                $car['children'][] = $seg;

//                p($company_list);
                $company_list[count($company_list)-1]['children'][] =  $car;
//                p($company_list);

                $lastCarId = $carPoint['car_id'];
                $reportTime = $carPoint['report_time'];
            }
            else {
                if($carPoint['report_time'] - $reportTime >= C('MAX_SEG_SPAN')) {
//                    dump('3---------');
                    $seg['startTime'] = $carPoint['report_time'];
                    $seg['endTime'] = $carPoint['report_time'];

                    $lastCarIndex = count( $company_list[count($company_list)-1]['children'])-1;
                    $company_list[count($company_list)-1]['children'][$lastCarIndex]['children'][] = $seg;
                }
                else {
//                    dump('4---------');
                    $lastCompanyIndex = count($company_list)-1;
                    $lastCarIndex = count( $company_list[$lastCompanyIndex]['children'])-1;
                    $lastSegIndex = count($company_list[$lastCompanyIndex]['children'][$lastCarIndex]['children'])-1;
                    $company_list[$lastCompanyIndex]['children'][$lastCarIndex]['children'][$lastSegIndex]['endTime'] = $carPoint['report_time'];
                }

                $reportTime = $carPoint['report_time'];
            }

//            p($company_list);
        }

        $ret['company_list'] = $company_list;
        echo (wrapResult('CM0000', $ret));
    }

    public function getCarTrackPoints() {
        $carIds = I('post.carIds');
        $startTimes = I('post.startTimes');
        $endTimes = I('post.endTimes');

        if(empty($carIds) || empty($startTimes) || empty($endTimes)) {
            exit(wrapResult('CM0003'));
        }

        $idList = explode(',', $carIds);
        $startTimeList = explode(',', $startTimes);
        $endTimeList = explode(',', $endTimes);

//        dump($idList);

        $dao = M('car_his');
        $carList = null;
        $carInfo = M('car')->field('id, plate')->where('id in ('.$carIds.')')->select();

        for($i = 0; $i < count($idList); $i++) {
            $seg = null;
            $condition['car_id'] = $idList[$i];
//            $condition['report_time'] = array('egt', $startTimeList[$i]);
//            $condition['report_time'] = array('elt', $endTimeList[$i]);
            $condition['report_time'] = array(array('egt', $startTimeList[$i]), array('elt', $endTimeList[$i]));

            $seg['car_id'] = $idList[$i];
            $seg['plate'] = $this->getNameById($carInfo, $idList[$i]);
            $seg['start_time'] = $startTimeList[$i];
            $seg['end_time'] = $endTimeList[$i];
            $seg['points'] = $dao->where($condition)->field('report_time, his_long, his_lat')->order('report_time')->select();

            $carList[] = $seg;
//            p($carList);
        }

//        $condition['car_id'] = $carId;
//        $condition['report_time'] = array('egt', $startTime);
//        $condition['report_time'] = array('elt', $endTime);
//
//        $dao = M('car_his_pos');

        $ret['car_list'] = $carList;

        echo (wrapResult('CM0000', $ret));
    }

    private function getNameById($list, $id) {
        foreach($list as $item) {
            if($item['id'] == $id) {
                return $item['plate'];
            }
        }

        return null;
    }

}