<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/10/16
 * Time: 15:09
 */

namespace Home\Controller;


class EnvironmentController
{
    public function getPmDots() {
        $startTime = strtotime('2016-10-10');
        $endTime = strtotime('2016-10-11');
        $condition['report_time'] = array(array('egt', $startTime), array('elt', $endTime));

        $carNum = 5;
        $dao = M('car_his');
        $data = array();
        for($i = 1; $i < $carNum; $i++) {
            $condition['car_id'] = $i;
            $res = $dao->where($condition)->field('his_long, his_lat')->select();
            foreach($res as $item) {
                $item['weight'] = rand(10, 100);
                $data[] = $item;
             }
        }

        $ret['dots'] = $data;

        echo wrapResult('CM0000', $ret);
    }
}