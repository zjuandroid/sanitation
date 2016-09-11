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
            $streetList = M('district')->where('pid = '.$districtId)->getField('id');
            if(!empty($streetList)) {
                $str = implode(',', $streetList);
                $condition['district_id'] = array('in', $str);
            }

        }
        if($name) {
            $condition['name'] = array('like', '%'.$name.'%');
        }
        if($pointNo) {
            $condition['point_no'] = array('like', '%'.$pointNo.'%');
        }

        $dao = M('collect_point');
//        $data = $dao->join('san_company ON car.company_id = company.id')->field('car.id, car.plate, car.car_type, car.car_online, car.car_state, car.company_id, company.company_name')->order('car.company_id')->select();
//        $data = $dao->alias('t1')->join('company t2', 't1.company_id=t2.id')->field('t1.id, t1.plate, t1.car_type, t1.car_online, t1.car_state, t1.company_id, t2.company_name')->select();
        $data = $dao->where($condition)->alias('t1')->join('left join san_district t2 ON t1.district_id=t2.id')->field('t1.id, t1.name, t1.point_no, t1.online, t1.state, t1.district_id, t2.name as district_name')->order('t1.district_id')->select();

        $lastDistrictId = -100;
        $district_list = array();

        foreach($data as $point) {
            if($point['district_id'] != $lastDistrictId) {
                $district['id'] = $point['district_id'];
                $district['name'] = $point['district_name'];
//                $company['property'] = 'company';
                $district['children'] = array();

                addStationChild($district, $point);

                $district_list[] = $district;

                $lastDistrictId = $point['district_id'];
            }
            else {
                addStationChild($district_list[count($district_list)-1], $point);
            }
        }

        $ret['district_list'] = $district_list;
        echo (wrapResult('CM0000', $ret));
    }

    public function getStationInfo() {
        $stationList = I('post.stationList');

        if(empty($stationList)) {
            exit (wrapResult('CM0000'));
        }

        $dao = M('waste_station');
        $data = $dao->where('t1.id in ('.$stationList.')')->alias('t1')->join('left join san_company t2 ON t1.company_id=t2.id')->field('t1.id, t1.station_no, t1.name, t1.address, t1.online, t1.state, t1.update_time, t1.company_id, t2.company_name')->select();
        $i = 0;
        foreach($data as $station) {
            $station['state'] = $this->getStateDes(intval($station['state']));
            $station['video_url'] = C('VIDEO_ROOT').$station['video_url'];
            $data[$i++] = $station;
        }

//        p($this->getAddress(121.506126,31.245475));
        $ret['station_list'] = $data;
        echo (wrapResult('CM0000', $ret));
    }

    private function getStateDes($state) {
        switch ($state) {
            case 0:
                $des = '正常';
                break;
            default:
                $des = '未知';
                break;
        }

        return $des;
    }

}