<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/5
 * Time: 22:03
 */

namespace Home\Controller;


class PersonController extends BaseController
{
    public function getPersons() {
        $companyId = I('post.companyId');
        $employeeNo = I('post.employeeNo');
        $name = I('post.name');

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
        if($employeeNo) {
            $condition['employee_no'] = array('like', '%'.$employeeNo.'%');
        }
        if($name) {
            $condition['name'] = array('like', '%'.$name.'%');
        }

        $dao = M('employee');
//        $data = $dao->join('san_company ON car.company_id = company.id')->field('car.id, car.plate, car.car_type, car.car_online, car.car_state, car.company_id, company.company_name')->order('car.company_id')->select();
//        $data = $dao->alias('t1')->join('company t2', 't1.company_id=t2.id')->field('t1.id, t1.plate, t1.car_type, t1.car_online, t1.car_state, t1.company_id, t2.company_name')->select();
        $data = $dao->where($condition)->alias('t1')->join('left join san_company t2 ON t1.company_id=t2.id')->field('t1.id, t1.name, t1.employee_no, t1.online, t1.state, t1.company_id, t2.company_name')->order('t1.company_id')->select();

        $lastCompanyId = -1;
        $company_list = array();

        foreach($data as $person) {
            if($person['company_id'] != $lastCompanyId) {
                $company['id'] = $person['company_id'];
                $company['name'] = $person['company_name'];
//                $company['property'] = 'company';
                $company['children'] = array();

                addPersonChild($company, $person);

                $company_list[] = $company;

                $lastCompanyId = $person['company_id'];
            }
            else {
                addPersonChild($company_list[count($company_list)-1], $person);
            }
        }

        $ret['company_list'] = $company_list;
        echo (wrapResult('CM0000', $ret));
    }

    public function getPersonInfo() {
        $personList = I('post.personList');

        if(empty($personList)) {
            exit (wrapResult('CM0000'));
        }

        $dao = M('employee');
        $data = $dao->where('t1.id in ('.$personList.')')->alias('t1')->join('left join san_company t2 ON t1.company_id=t2.id')->field('t1.id, t1.name, t1.online, t1.state, t1.cur_long, t1.cur_lat, t1.cur_velocity, t1.update_time, t1.company_id, t2.company_name')->select();
        $i = 0;
        foreach($data as $person) {
            $person['location'] = getAddress($person['cur_long'], $person['cur_lat']);
            $person['state'] = $this->getStateDes(intval($person['state']));
            $data[$i++] = $person;

        }

//        p($this->getAddress(121.506126,31.245475));
        $ret['employee_list'] = $data;
        echo (wrapResult('CM0000', $ret));
    }

    private function getStateDes($state) {
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