<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/5
 * Time: 22:03
 */

namespace Home\Controller;


class StationController extends BaseController
{
    public function getDistricts() {
        $managerId =  I('post.userId');

        $dao = M('district');
        $condition['pid'] = 0;
        $data = $dao->field('id, name')->where($condition)->select();

        $ret['district_list'] = $data;

        echo (wrapResult('CM0000', $ret));
    }

    public function getStations() {
        $districtId = I('post.districtId');
        $name = I('post.name');

        if($districtId) {
            $condition['district_id'] = $districtId;
        }
        if($name) {
            $condition['name'] = array('like', '%'.$name.'%');
        }

        $dao = M('waste_station');
//        $data = $dao->join('san_company ON car.company_id = company.id')->field('car.id, car.plate, car.car_type, car.car_online, car.car_state, car.company_id, company.company_name')->order('car.company_id')->select();
//        $data = $dao->alias('t1')->join('company t2', 't1.company_id=t2.id')->field('t1.id, t1.plate, t1.car_type, t1.car_online, t1.car_state, t1.company_id, t2.company_name')->select();
        $data = $dao->where($condition)->alias('t1')->join('left join san_district t2 ON t1.district_id=t2.id')->field('t1.id, t1.name, t1.online, t1.state, t1.district_id, t1.station_no, t2.name as district_name')->order('t1.district_id')->select();

        $lastDistrictId = -1;
        $district_list = array();

        foreach($data as $station) {
            $district = null;
            if($station['district_id'] != $lastDistrictId) {
                $district['id'] = $station['district_id'];
                $district['name'] = $station['district_name'];
//                $company['property'] = 'company';
                $district['children'] = array();

                addStationChild($district, $station);

                $district_list[] = $district;

                $lastDistrictId = $station['district_id'];
            }
            else {
                addStationChild($district_list[count($district_list)-1], $station);
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
        $data = $dao->where('t1.id in ('.$stationList.')')->alias('t1')->join('left join san_company t2 ON t1.company_id=t2.id')->field('t1.id, t1.station_no, t1.name, t1.address, t1.online, t1.state, t1.update_time, t1.video_url, t1.company_id, t2.company_name')->select();
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

    public function getStationTrackSegments() {
        $districtId = I('post.districtId');
        $name = I('post.name');
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_TIME_STATION_SPAN')) {
            exit(wrapResult('CM0002'));
        }

        $hisDao = M('waste_station_his');
        $where['report_time'] = array('egt', $startTime);
        $where['report_time'] = array('elt', $endTime);
        $data = $hisDao->where($where)->field('waste_station_id')->group('waste_station_id')->select();
//        $data = $dao->where($where)->getField('waste_station_id')->group('waste_station_id');
        $idList = null;
        foreach($data as $item) {
            $idList[] = $item['waste_station_id'];
        }

//        dump($idList);

        if($idList)
        {
            $str = implode(',', $idList);
            $condition['t1.id'] = array('in', $str);
        }
        else {
            exit(wrapResult('CM0000'));
        }

        if($districtId) {
            $condition['district_id'] = $districtId;
        }
        if($name) {
            $condition['name'] = array('like', '%'.$name.'%');
        }

        $data = null;

        $dao = M('waste_station');
        $data = $dao->where($condition)->alias('t1')->join('left join san_district t2 ON t1.district_id=t2.id')->field('t1.id, t1.name, t1.station_no, t1.district_id,  t2.name as district_name')->order('t1.district_id')->select();

        $lastDistrictId = -1;
        $district_list = array();

        foreach($data as $station) {
            $district = null;
            $child = null;
            if($station['district_id'] != $lastDistrictId) {
                $district['id'] = $station['district_id'];
                $district['name'] = $station['district_name'];
//                $company['property'] = 'company';
//                $district['children'] = array();

                $child['id'] = $station['id'];
                $child['name'] = $station['name'];
                $child['station_no'] = $station['station_no'];
                $child['his_full_state'] = $this->getHisFullState($hisDao, $station['id'], $startTime, $endTime);
                $child['start_time'] = $startTime;
                $child['end_time'] = $endTime;

                $district['children'][] = $child;
                $district_list[] = $district;

                $lastDistrictId = $station['district_id'];
            }
            else {
                $child['id'] = $station['id'];
                $child['name'] = $station['name'];
                $child['station_no'] = $station['station_no'];
                $child['his_full_state'] = $this->getHisFullState($hisDao, $station['id'], $startTime, $endTime);
                $child['start_time'] = $startTime;
                $child['end_time'] = $endTime;

                $district_list[count($district_list)-1]['children'][] = $child;
            }
        }

        $ret['district_list'] = $district_list;
        echo (wrapResult('CM0000', $ret));
    }

    private function getHisFullState($dao, $id, $startTime, $endTime) {
        $where['report_time'] = array('egt', $startTime);
        $where['report_time'] = array('elt', $endTime);
        $where['waste_station_id'] = $id;
        $where['water_level'] = array('egt', C('MAX_WATER_LEVEL'));

        $data = $dao->where($where)->select();
        if(empty($data)) {
            return 0;
        }

        return 1;
    }

    public function getStationTrackPoints() {
        $stationList = I('post.stationList');
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_TIME_STATION_SPAN')) {
            exit(wrapResult('CM0002'));
        }

        if(empty($stationList)) {
            exit (wrapResult('CM0000'));
        }

        $dao = M('waste_station');
        $data = $dao->where('t1.id in ('.$stationList.')')->alias('t1')->join('left join san_company t2 ON t1.company_id=t2.id')->field('t1.id, t1.station_no, t1.name, t1.address, t1.company_id, t2.company_name')->select();
        $hisDao = M('waste_station_his');
        $i = 0;
        foreach($data as $station) {
            $condition = null;
            $condition['waste_station_id'] = $station['id'];
            $condition['report_time'] = array('egt', $startTime);
            $condition['report_time'] = array('elt', $endTime);

            $data[$i++]['points'] = $hisDao->where($condition)->field('report_time, water_level')->order('report_time')->select();
        }

//        p($this->getAddress(121.506126,31.245475));
        $ret['station_list'] = $data;
        echo (wrapResult('CM0000', $ret));
    }

}