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
    public function getNewAlertNum() {
        $dao = M('alert');

        $condition['status'] = 0;
        $num = $dao->where($condition)->count();

        $ret['alert_num'] = $num;

        echo (wrapResult('CM0000', $ret));
    }

    public function getAlerts() {
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

}