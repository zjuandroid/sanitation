<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/5
 * Time: 22:03
 */
namespace Home\Controller;

use Think\Log;


class CompanyController extends BaseController
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
        $data = $dao->where('t1.id in ('.$carList.')')->alias('t1')->join('left join san_company t2 ON t1.company_id=t2.id')->field('t1.id, t1.plate, t1.car_type, t1.car_online, t1.car_state, t1.cur_long, t1.cur_lat, t1.cur_velocity, t1.cur_oil_amount, t1.update_time, t1.company_id, t2.company_name, t1.video_url')->select();
        $i = 0;
        foreach($data as $car) {
            $car['location'] = getAddress($car['cur_long'], $car['cur_lat']);
//            $car['video_url'] = 'http://115.159.66.204/uploads/video/carMonitor.flv';
            $car['video_url'] = C('VIDEO_ROOT').$car['video_url'];
            $car['car_state'] = $this->getCarStateDes(intval($car['car_state']));
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
                $des = '未知';
                break;
        }

        return $des;
    }
}