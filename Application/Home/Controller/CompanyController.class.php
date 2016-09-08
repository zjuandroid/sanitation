<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/5
 * Time: 22:03
 */

namespace Home\Controller;


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
                $company['children'] = array();

//                $child['id'] = $car['id'];
//                $child['plate'] = $car['plate'];
//                $child['type'] = $car['car_type'];
//                $child['online'] = $car['car_online'];
//                $child['state'] = $car['car_state'];
//                $company['children'][] = $child;
//                array_push($company['children'], $child);
                addChild($company, $car);

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
                addChild($company_list[count($company_list)-1], $car);
            }
        }

        $ret['company_list'] = $company_list;
        echo (wrapResult('CM0000', $ret));
    }

}