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
            $condition['plate'] = $plate;
        }

        //根据公司和车牌获取车的范围
        $dao = M('car');
        $data = $dao->where($condition)->group('car_id')->select();
        $companyTable = M('company')->field('id, company_name')->select();
        $carTable = M('car')->field('id, plate, company_id')->select();
        $idList = null;
        foreach($data as $item) {
            $idList[] = $item['car_id'];
        }

        if($idList)
        {
            $str = implode(',', $idList);
            $condition['car_id'] = array('in', $str);
        }
        else {
            exit(wrapResult('CM0000'));
        }

        $condition['report_time'] = array('egt', $startTime);
        $condition['report_time'] = array('elt', $endTime);
        $hisDao = M('car_his');
        $data = null;
        $data = $hisDao->where($condition)->order('car_id, report_time, his_long, his_lat, his_velocity')->select();

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
            }
            $i++;
        }


        $hisDao = M('car_his');
        $where['report_time'] = array('egt', $startTime);
        $where['report_time'] = array('elt', $endTime);
        $data = $hisDao->where($where)->field('car_id')->group('car_id')->select();
        $idList = null;
        foreach($data as $item) {
            $idList[] = $item['car_id'];
        }

        if($idList)
        {
            $str = implode(',', $idList);
            $condition['t1.id'] = array('in', $str);
        }
        else {
            exit(wrapResult('CM0000'));
        }
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
}