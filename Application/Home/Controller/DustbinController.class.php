<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/5
 * Time: 22:03
 */

namespace Home\Controller;


class DustbinController extends BaseController
{
    public function getStreets() {
        $managerId =  I('post.userId');

        $dao = M('district');
        $data = $dao->field('id, name, pid')->order('pid')->select();

        $districtArr = array();
        foreach($data as $item) {
            if($item['pid'] != 0) {
                break;
            }
            $district['id'] = $item['id'];
            $district['name'] = $item['name'];
//            $district['children'] = array();
            $district['children'] = null;
            $districtArr[] = $district;
        }

        foreach($data as $item) {
            if($item['pid'] == 0) {
                continue;
            }
//            foreach($districtArr as $s) {
//                if($s['id'] == $item['pid']) {
//                    $street['id'] = $s['id'];
//                    $street['name'] = $s['name'];
//                    $s['children'][] = $street;
//                    break;
//                }
//            }

            for($i = 0; $i < count($districtArr); $i++) {
                if($districtArr[$i]['id'] == $item['pid']) {
                    $street['id'] = $item['id'];
                    $street['name'] = $item['name'];
                    $districtArr[$i]['children'][] = $street;
                    break;
                }
            }
        }

//        p($districtArr);

        $ret['district_list'] = $districtArr;

        echo (wrapResult('CM0000', $ret));
    }

    public function getCollectPoints() {
        $districtId = I('post.districtId');
        $streetId = I('post.streetId');
        $name = I('post.name');
        $pointNo = I('post.pointNo');

        if($streetId) {
            $condition['district_id'] = $districtId;
        }
        else if($districtId) {
            $streetList = M('district')->where('pid = '.$districtId)->field('id')->select();

//            if(!empty($streetList)) {
//                $arr = array();
//                foreach($streetList as $street) {
//                    $arr[] = $street['id'];
//                }
//                $str = implode(',', $arr);
//                rtrim($str, ',');
//                if(!empty($str)) {
//                    $condition['district_id'] = array('in', $str);
//                }
//            }


            $arr = array();
            foreach($streetList as $street) {
                $arr[] = $street['id'];
            }
            $str = implode(',', $arr);
            rtrim($str, ',');

            $condition['district_id'] = array('in', $str);
        }
        if($name) {
            $condition['name'] = array('like', '%'.$name.'%');
        }
        if($pointNo) {
            $condition['point_no'] = array('like', '%'.$pointNo.'%');
        }

        $dao = M('collect_point');
        $data = $dao->where($condition)->alias('t1')->join('left join san_district t2 ON t1.district_id=t2.id')->field('t1.id, t1.name, t1.point_no, t1.online, t1.state, t1.district_id, t2.name as district_name, t2.pid')->order('t1.district_id')->select();
        $districtNameTable = M('district')->field('id, name')->select();

        $lastDistrictId = -100;
        $district_list = null;

//        dump($data);

        foreach($data as $point) {
            $street = null;
            $district = null;
            $collectPoint = null;
            $districtIndex = $this->getDistrictIndex($district_list, $point['pid']);
            $streetIndex = $this->getStreetIndex($district_list, $point['pid'], $point['district_id']);

//            dump($point['pid']);
//            dump($point['id']);
//            dump($districtIndex);
//            dump($streetIndex);

            $collectPoint['id'] = $point['id'];
            $collectPoint['name'] = $point['name'];
            $collectPoint['point_no'] = $point['point_no'];

            if($streetIndex != -1) {
                $district_list[$districtIndex]['children'][$streetIndex]['children'][] = $collectPoint;
            }
            else if($districtIndex != -1) {
                $street['id'] = $point['district_id'];
                $street['name'] = $point['district_name'];
                $street['children'][] = $collectPoint;

                $district_list[$districtIndex]['children'][] = $street;
            }
            else {

                $street['id'] = $point['district_id'];
                $street['name'] = $point['district_name'];
                $street['children'][] = $collectPoint;

                $district['id'] = $point['pid'];
                $district['name'] = $this->getDistrictName($districtNameTable, $district['id']);
                $district['children'][] = $street;

                $district_list[] = $district;
           }
//            p($district_list);
        }

        $ret['district_list'] = $district_list;
        echo (wrapResult('CM0000', $ret));
    }

    private function getDistrictName(&$nameTable, $id) {
        if(empty($nameTable)) {
            return null;
        }
        foreach($nameTable as $item) {
            if($item['id'] == $id) {
                return $item['name'];
            }
        }
        return null;
    }

    private function getDistrictIndex(&$district_list, $id) {
        for($i = 0; $i < count($district_list); $i++)
            if($district_list[$i]['id'] == $id) {
                return $i;
        }

        return -1;
    }

    private function getStreetIndex(&$district_list, $pid, $id) {
        if(empty($district_list)) {
            return -1;
        }
        $index = $this->getDistrictIndex($district_list, $pid);
        if($index == -1) {
            return -1;
        }
        if(empty($district_list[$index]['children'])) {
            return -1;
        }

        $i = 0;
        foreach ($district_list[$index]['children'] as $street) {
            if($street['id'] == $id) {
                return $i;
            }
            $i++;
        }

        return -1;
    }

    public function getDustbinInfo() {
        $collectPointList = I('post.collectPointList');

        if(empty($collectPointList)) {
            exit (wrapResult('CM0000'));
        }

        $dao = M('dustbin');
//        $data = $dao->alias('t1')->join('left join san_collect_point t2 ON t1.collect_point_id=t2.id')->join('left join san_company t3 ON t2.company_id=t3.id')->field('t1.id, t1.dustbin_no, t1.state, t1.online, t1.update_time, t2.id as point_id, t2.point_no, t3.company_name')->where('point_id in ('.$collectPointList.')')->select();
        $data = $dao->alias('t1')->join('left join san_collect_point t2 ON t1.collect_point_id=t2.id')->join('left join san_company t3 ON t2.company_id=t3.id')->field('t1.id, t1.dustbin_no, t1.state, t1.online, t1.cur_long, t1.cur_lat, t1.update_time, t2.id as point_id, t2.point_no, t3.company_name')->where('t2.id in ('.$collectPointList.')')->order('t2.id')->select();

        $i = 0;
        foreach($data as $dustbin) {
            $dustbin['state'] = $this->getStateDes(intval($dustbin['state']));
            $dustbin['location'] = getAddress($dustbin['cur_long'], $dustbin['cur_lat']);
            $data[$i++] = $dustbin;
        }

        $ret['dustbin_list'] = $data;
        echo (wrapResult('CM0000', $ret));
    }

    private function getStateDes($state) {
        switch ($state) {
            case 0:
                $des = '正常';
                break;
            default:
                $des = '已满';
                break;
        }

        return $des;
    }

}