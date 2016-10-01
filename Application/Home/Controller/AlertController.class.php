<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/20
 * Time: 21:58
 */
namespace Home\Controller;

use Think\Log;



class AlertController extends BaseController
{
    var $alertDeviceType = array(
        array(
            'id' => 101,
            'name' => '环卫车报警'
        ),
        array(
            'id' => 102,
            'name' => '垃圾车报警'
        ),
        array(
            'id' => 201,
            'name' => '环卫工人报警'
        ),
        array(
            'id' => 301,
            'name' => '中转站报警'
        ),
        array(
            'id' => 401,
            'name' => '垃圾箱报警'
        )
    );

    var $alertType = array(
        array(
            'id' => 101,
            'name' => '油量骤降'
        ),
        array(
            'id' => 201,
            'name' => '环卫工人跌倒'
        ),
        array(
            'id' => 301,
            'name' => '中转站已满'
        ),
        array(
            'id' => 401,
            'name' => '垃圾箱已满'
        )
    );

    public function getNewAlertNum() {
        $dao = M('alert');

        $condition['status'] = 0;
        $num = $dao->where($condition)->count();

        $ret['alert_num'] = $num;

        echo (wrapResult('CM0000', $ret));
    }

    public function getAlerts1() {
        $alertStatus = I('post.alertStatus');
        $alertType = I('post.alertType');
//        if(empty($alertStatus)) {
//            exit(wrapResult('CM0003'));
//        }

        if($alertStatus == 'new') {
            $condition['status'] = 0;
        }
        else if ($alertStatus == 'old') {
            $condition['status'] = array('neq', 0);
        }
        else if($alertStatus == 'all' || empty($alertStatus)) {
            $condition = null;
        }
        else {
            exit (wrapResult('CM0004'));
        }

        if($alertType == 'car') {
            $condition['type'] = 1;
        }
        else if($alertType == 'person') {
            $condition['type'] = 2;
        }
        else if(empty($alertType)) {

        }
        else {
            exit (wrapResult('CM0004'));
        }

        $dao = M('alert');
        $data = $dao->where($condition)->select();

        if($alertStatus == 'new' || $alertStatus == 'all') {
            $update['status'] = 1;
            $condition['status'] = 0;
            $flag = $dao->where($condition)->save($update);
            if($flag === false) {
                exit (wrapResult('CM0005'));
            }
        }

        $totalNum = count($data);

        $i = 0;
        foreach($data as $alert) {
            $data[$i]['type_description'] = $this->getTypeDescription($alert['type']);
            $data[$i]['status_description'] = $this->getStatusDescription($alert['status']);
            $i++;
        }

        $ret['alert_num'] = $totalNum;
        $ret['alert_list'] = $data;

        echo (wrapResult('CM0000', $ret));
    }

    private function getTypeDescription($type) {
        $desc = '';
        switch($type) {
            case 1:
                $desc = '车辆告警';
                break;
            case 2:
                $desc = '环卫工人告警';
                break;
            default:
                break;
        }

        return $desc;
    }

    private function getStatusDescription($status) {
        $desc = '';
        switch($status) {
            case 0:
                $desc = '未读';
                break;
            case 1:
                $desc = '已读';
                break;
            default:
                break;
        }

        return $desc;
    }

    public function getAlertDeviceType() {
        $ret['alertDeviceTypes'] = $this->alertDeviceType;

        echo (wrapResult('CM0000', $ret));
    }

    public function getAlertType() {
        $ret['alertTypes'] = $this->alertType;

        echo (wrapResult('CM0000', $ret));
    }

    public function getAlerts2() {
        $alertDeviceType = I('post.alertDeviceType');
        $alertType = I('post.alertType');
        $companyId = I('post.companyId');
        $districtId = I('post.districtId');
        $name = I('post.name');

        $startTime = I('post.startTime');
        $endTime = I('post.endTime');
        $alertStatus = I('post.alertStatus');

        if($startTime) {
            $condition['report_time'] = array('egt', $startTime);
        }
        if($endTime) {
            $condition['report_time'] = array('elt', $endTime);
        }
        if($alertStatus == 'new') {
            $condition['status'] = 0;
        }
        else if ($alertStatus == 'old') {
            $condition['status'] = array('neq', 0);
        }

        if($alertType || $alertDeviceType) {
            $kind1 = -1;
            if($alertType) {
                $condition['content_type'] = $alertType;
                $kind1 = floor($alertType/100);
            }

            $kind2 = -1;
            if($alertDeviceType) {
                $condition['device_type'] = $alertDeviceType;
                $kind2 = floor($alertDeviceType/100);
            }

            if($kind1 != $kind2) {
                exit (wrapResult('CM0000'));
            }

            switch ($kind1) {
                case 1:
                    //车辆
                    if($companyId) {
                        $condition['company_id'] = $companyId;
                    }
                    if($name) {
                        $condition['plate'] = array('like', '%'.$name.'%');
                    }
                    $data = M('alert')->where($condition)->alias('t1')->join('left join san_car t2 ON t1.source_id=t2.id')->field('t1.id, t1.device_type, t1.source_id, t1.content_type, t1.content_desc, t1.status, t1.report_time, t2.plate as name, t2.company_id')->select();
                    break;
                case 2:
                    //环卫工人
                    if($companyId) {
                        $condition['company_id'] = $companyId;
                    }
                    if($name) {
                        $condition['name'] = array('like', '%'.$name.'%');
                    }
                    $data = M('alert')->where($condition)->alias('t1')->join('left join san_employee t2 ON t1.source_id=t2.id')->field('t1.id, t1.device_type, t1.source_id, t1.content_type, t1.content_desc, t1.status, t1.report_time, t2.name, t2.company_id')->select();
                    break;
                case 3:
                    //垃圾中转站
                    if($companyId) {
                        $condition['company_id'] = $companyId;
                    }
                    if($name) {
                        $condition['name'] = array('like', '%'.$name.'%');
                    }
                    $data = M('alert')->where($condition)->alias('t1')->join('left join san_waste_station t2 ON t1.source_id=t2.id')->field('t1.id, t1.device_type, t1.source_id, t1.content_type, t1.content_desc, t1.status, t1.report_time, t2.name, t2.company_id')->select();
                    break;
                case 4:
                    //垃圾回收点
                    if($companyId) {
                        $condition['company_id'] = $companyId;
                    }
                    if($name) {
                        $condition['name'] = array('like', '%'.$name.'%');
                    }
                    $data = M('alert')->where($condition)->alias('t1')->join('left join san_collect_point t2 ON t1.source_id=t2.id')->field('t1.id, t1.device_type, t1.source_id, t1.content_type, t1.content_desc, t1.status, t1.report_time, t2.name, t2.company_id')->select();
                    break;
            }
            $i = 0;
            foreach($data as $item) {
                $data[$i]['device_type'] = $this->getDeviceType($item['device_type']);
                $data[$i]['content_type'] = $this->getContentType($item['content_type']);
                $i++;
            }
        }
        else if($companyId || $districtId || $name) {
            if($districtId) {
                //垃圾回收点或者中转站
//                $condition['']
            }
            else {
                //车辆，人员，垃圾回收点或者中转站

            }
        }
        else {

        }

    }

    public function getAlerts() {
        $alertDeviceType = I('post.alertDeviceType');
        $alertType = I('post.alertType');
        $companyId = I('post.companyId');
        $districtId = I('post.districtId');
        $name = I('post.name');

        $startTime = I('post.startTime');
        $endTime = I('post.endTime');
        $alertStatus = I('post.alertStatus');

        if($startTime && $endTime) {
            $condition['report_time'] = array(array('egt', $startTime), array('elt', $endTime));
        }
        else if($startTime) {
            $condition['report_time'] = array('egt', $startTime);
        }
        else if($endTime) {
            $condition['report_time'] = array('elt', $endTime);
        }

        if($alertStatus == 'new') {
            $condition['status'] = 0;
        }
        else if ($alertStatus == 'old') {
            $condition['status'] = array('neq', 0);
        }
        if($alertDeviceType) {
            $condition['device_type'] = intval($alertDeviceType);
        }
        if($alertType) {
            $condition['content_type'] = intval($alertType);
        }
        if($companyId) {
//            $condition['source_company_id'] = intval($companyId);
            $condition['source_company_id'] = $companyId;
        }
        if($districtId) {
            $condition['source_district_id'] = intval($districtId);
        }
        if($name) {
            $condition['source_name'] = array('like', '%'.$name.'%');
        }

//        dump($condition);

        $dao = M('alert');
        $data = $dao->where($condition)->select();
//        $data = $dao->where('source_company_id=1')->select();
        for($i = 0; $i < count($data); $i++) {
            $data[$i]['device_type_desc'] = $this->getDeviceType($data[$i]['device_type']);
            $data[$i]['content_type_desc'] = $this->getContentType($data[$i]['content_type']);
        }

        $ret['alerts'] = $data;

        if($alertStatus == 'new' || $alertStatus == 'all' || empty($alertStatus)) {
            $update['status'] = 1;
            $condition['status'] = 0;
            $flag = $dao->where($condition)->save($update);
            if($flag === false) {
                exit (wrapResult('CM0005'));
            }
        }

        echo (wrapResult('CM0000', $ret));
    }

    private function getDeviceType($id) {
        foreach($this->alertDeviceType as $item) {
            if($item['id'] == $id) {
                return $item['name'];
            }
        }

        return null;
    }

    private function getContentType($id) {
        foreach($this->alertType as $item) {
            if($item['id'] == $id) {
                return $item['name'];
            }
        }

        return null;
    }
}