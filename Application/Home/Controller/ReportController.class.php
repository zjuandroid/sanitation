<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/27
 * Time: 21:07
 */
namespace Home\Controller;
use Think\Log;

class ReportController extends BaseController
{
    public function getCarReport()
    {
        $companyId = I('post.companyId');
        $plate = I('post.plate');
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_REPORT_TIME_SPAN')) {
            exit(wrapResult('CM0002'));
        }

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
        if($plate) {
            $condition['plate'] = array('like', '%'.$plate.'%');
        }

        //根据公司和车牌获取车的范围
        $dao = M('car');
        $data = $dao->where($condition)->group('id')->select();
        $companyTable = M('company')->field('id, company_name')->select();
        $carTable = M('car')->field('id, plate, company_id')->select();
        $idList = null;
        foreach($data as $item) {
            $idList[] = $item['id'];
        }

        if($idList)
        {
            $str = implode(',', $idList);
            $condition['car_id'] = array('in', $str);
        }
        else {
            exit(wrapResult('CM0000'));
        }

//        $condition['report_time'] = array('egt', $startTime);
//        $condition['report_time'] = array('elt', $endTime);
        $condition['report_time'] = array(array('egt', $startTime), array('elt', $endTime));
        $hisDao = M('car_his');
        $data = null;
        $data = $hisDao->where($condition)->order('car_id, report_time')->select();

        $num = 0;
        $time = 0;
        $distance = 0;
        $s = 0;
        $e = 0;
        $lastId = -1;
        $i = 0;
        foreach($data as $point) {
            if($point['car_id'] != $lastId) {
                $s = $i;
                $result[$num]['car_plate'] = $this->getCarPlate($carTable, $point['car_id']);
                $result[$num]['company_name'] = $this->getCompanyName($companyTable, $carTable, $point['car_id']);
                $result[$num]['time'] = 0;
                $result[$num]['distance'] = 0;
                $result[$num]['oil_consumption'] = 0;

                $lastId = $point['car_id'];
                $num++;
            }
            else {
                $result[$num-1]['time'] = $point['report_time'] - $data[$s]['report_time'];
                $result[$num-1]['distance'] += 5;
                $result[$num-1]['oil_consumption'] += 5*0.08;
            }
            $i++;
        }

        $ret['car_reports'] = $result;
        echo (wrapResult('CM0000', $ret));
    }

    private function getCarPlate($table, $id) {
        foreach($table as $item) {
            if($item['id'] == $id) {
                return $item['plate'];
            }
        }

        return null;
    }

    private function getCompanyName($companyTable, $carTable, $carId) {
        $companyId = -1;
        foreach($carTable as $item) {
            if($item['id'] == $carId) {
                $companyId = $item['company_id'];
                break;
            }
        }
        if($companyId == -1) {
            return null;
        }

        foreach ($companyTable as $item) {
            if($item['id'] == $companyId) {
                return $item['company_name'];
            }
        }
    }

    public function getPersonReport()
    {
        $companyId = I('post.companyId');
        $name = I('post.name');
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_REPORT_TIME_SPAN')) {
            exit(wrapResult('CM0002'));
        }

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
        if($name) {
            $condition['name'] = array('like', '%'.$name.'%');
        }

        //根据公司和姓名获取人员的范围
        $dao = M('employee');
        $data = $dao->where($condition)->group('id')->select();
        $companyTable = M('company')->field('id, company_name')->select();
        $personTable = $dao->field('id, name, company_id')->select();
        $idList = null;
        foreach($data as $item) {
            $idList[] = $item['id'];
        }

        if($idList)
        {
            $str = implode(',', $idList);
            $condition['person_id'] = array('in', $str);
        }
        else {
            exit(wrapResult('CM0000'));
        }

//        $condition['report_time'] = array('egt', $startTime);
//        $condition['report_time'] = array('elt', $endTime);
        $condition['report_time'] = array(array('egt', $startTime), array('elt', $endTime));
        $hisDao = M('person_his');
        $data = null;
        $data = $hisDao->where($condition)->order('person_id, report_time')->select();

        dump($data);

        $num = 0;
        $time = 0;
        $distance = 0;
        $s = 0;
        $e = 0;
        $lastId = -1;
        $i = 0;
        foreach($data as $point) {
            if($point['person_id'] != $lastId) {
                $s = $i;
                $result[$num]['person_name'] = $this->getPersonName($personTable, $point['person_id']);
                $result[$num]['company_name'] = $this->getCompanyName($companyTable, $personTable, $point['person_id']);
                $result[$num]['time'] = 0;
                $result[$num]['distance'] = 0;

                $lastId = $point['person_id'];
                $num++;
            }
            else {
                $result[$num-1]['time'] = $point['report_time'] - $data[$s]['report_time'];
                $result[$num-1]['distance'] += 0.05;
            }
            $i++;
        }

        $ret['person_reports'] = $result;
        echo (wrapResult('CM0000', $ret));
    }

    private function getPersonName($table, $id) {
        foreach($table as $item) {
            if($item['id'] == $id) {
                return $item['name'];
            }
        }

        return null;
    }

    public function getWasteStationReport() {
        $companyId = I('post.companyId');
        $name = I('post.name');
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_REPORT_TIME_SPAN')) {
            exit(wrapResult('CM0002'));
        }

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
        if($name) {
            $condition['name'] = array('like', '%'.$name.'%');
        }

        //根据公司和姓名获取人员的范围
        $dao = M('waste_station');
        $data = $dao->where($condition)->group('id')->select();
        $companyTable = M('company')->field('id, company_name')->select();
        $wasteStationTable = $dao->field('id, name, company_id, address')->select();
        $idList = null;
        foreach($data as $item) {
            $idList[] = $item['id'];
        }

        if($idList)
        {
            $str = implode(',', $idList);
            $condition['waste_station_id'] = array('in', $str);
        }
        else {
            exit(wrapResult('CM0000'));
        }

//        $condition['report_time'] = array('egt', $startTime);
//        $condition['report_time'] = array('elt', $endTime);
        $condition['report_time'] = array(array('egt', $startTime), array('elt', $endTime));
        $hisDao = M('waste_station_his');
        $data = null;
//        dump($condition);
        $data = $hisDao->where($condition)->field('waste_station_id, sum(delta_weight) as sum_weight')->group('waste_station_id')->select();

        $i = 0;
        foreach($data as $item) {
            $data[$i]['waste_station_name'] = $this->getWasteStationName($wasteStationTable, $item['waste_station_id']);
            $data[$i]['waste_station_address'] = $this->getWasteStationAddress($wasteStationTable, $item['waste_station_id']);
            $data[$i]['company_name'] = $this->getCompanyName($companyTable, $wasteStationTable, $item['waste_station_id']);
            $i++;
        }

        $ret['waste_station_reports'] = $data;
        echo (wrapResult('CM0000', $ret));
    }

    private function getWasteStationName($table, $id) {
        foreach($table as $item) {
            if($item['id'] == $id) {
                return $item['name'];
            }
        }

        return null;
    }

    private function getWasteStationAddress($table, $id) {
        foreach($table as $item) {
            if($item['id'] == $id) {
                return $item['address'];
            }
        }

        return null;
    }
}