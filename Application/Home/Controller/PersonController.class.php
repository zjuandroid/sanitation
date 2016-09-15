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


    public function getPersonTrackSegments() {
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');
        $companyId = I('post.companyId');
        $employeeNo = I('post.employeeNo');
        $name = I('post.name');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_TIME_SPAN')) {
            exit(wrapResult('CM0002'));
        }
        else {
            $condition['report_time'] = array('egt', $startTime);
            $condition['report_time'] = array('elt', $endTime);
        }

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
        if($employeeNo) {
            $condition['plate'] = array('employee_no', '%'.$employeeNo.'%');
        }
        if($name) {
            $condition['name'] = array('name', '%'.$name.'%');
        }

        $dao = M('person_his_pos');
        $data = $dao->where($condition)->alias('t1')->join('left join san_employee t2 ON t1.person_id=t2.id')->join('left join san_company t3 ON t2.company_id=t3.id')->field('t1.id, t1.person_id, t1.report_time, t1.his_long, t1.his_lat, t2.name, t2.employee_no, t2.company_id, t3.company_name')->order('t2.company_id, t1.person_id, t1.report_time')->select();

//        dump($data);
        $lastCompanyId = -100;
        $lastPersonId = -100;
        $reportTime = -100;
        $company_list = array();

//        p($data);

        foreach($data as $personPoint) {
            $company = null;
            $person = null;
            $seg = null;
//            dump($personPoint['company_id']);
//            dump($lastCompanyId);
            if($personPoint['company_id'] != $lastCompanyId) {
//                dump('1----');
                $seg['startTime'] = $personPoint['report_time'];
                $seg['endTime'] = $personPoint['report_time'];

                $person['id'] = $personPoint['person_id'];
                $person['name'] = $personPoint['name'];
                $person['employee_no'] = $personPoint['employee_no'];
                $person['children'][] = $seg;

                $company['id'] = $personPoint['company_id'];
                $company['name'] = $personPoint['company_name'];
                $company['children'][] = $person;

                $company_list[] = $company;

                $lastCompanyId = $personPoint['company_id'];
                $lastPersonId = $personPoint['person_id'];
                $reportTime = $personPoint['report_time'];
            }
            else if($personPoint['person_id'] != $lastPersonId){
//                dump('2------');
                $seg['startTime'] = $personPoint['report_time'];
                $seg['endTime'] = $personPoint['report_time'];

                $person['id'] = $personPoint['person_id'];
                $person['name'] = $personPoint['name'];
                $person['employee_no'] = $personPoint['employee_no'];
                $person['children'][] = $seg;

//                p($company_list);
                $company_list[count($company_list)-1]['children'][] =  $person;
//                p($company_list);

                $lastPersonId = $personPoint['person_id'];
                $reportTime = $personPoint['report_time'];
            }
            else {
                if($personPoint['report_time'] - $reportTime >= C('MAX_SEG_SPAN')) {
//                    dump('3---------');
                    $seg['startTime'] = $personPoint['report_time'];
                    $seg['endTime'] = $personPoint['report_time'];

                    $lastPersonIndex = count( $company_list[count($company_list)-1]['children'])-1;
                    $company_list[count($company_list)-1]['children'][$lastPersonIndex]['children'][] = $seg;
                }
                else {
//                    dump('4---------');
                    $lastCompanyIndex = count($company_list)-1;
                    $lastPersonIndex = count( $company_list[$lastCompanyIndex]['children'])-1;
                    $lastSegIndex = count($company_list[$lastCompanyIndex]['children'][$lastPersonIndex]['children'])-1;
                    $company_list[$lastCompanyIndex]['children'][$lastPersonIndex]['children'][$lastSegIndex]['endTime'] = $personPoint['report_time'];
                }

                $reportTime = $personPoint['report_time'];
            }

//            p($company_list);
        }

        $ret['company_list'] = $company_list;
        echo (wrapResult('CM0000', $ret));
    }

    public function getPersonTrackPoints() {
        $personIds = I('post.personIds');
        $startTimes = I('post.startTimes');
        $endTimes = I('post.endTimes');

        if(empty($personIds) || empty($startTimes) || empty($endTimes)) {
            exit(wrapResult('CM0003'));
        }

        $idList = explode(',', $personIds);
        $startTimeList = explode(',', $startTimes);
        $endTimeList = explode(',', $endTimes);

//        dump($idList);

        $dao = M('person_his_pos');
        $personList = null;

        for($i = 0; $i < count($idList); $i++) {
            $condition['person_id'] = $idList[$i];
            $condition['report_time'] = array('egt', $startTimeList[$i]);
            $condition['report_time'] = array('elt', $endTimeList[$i]);

            $seg['person_id'] = $idList[$i];
            $seg['start_time'] = $startTimeList[$i];
            $seg['end_time'] = $endTimeList[$i];
            $seg['points'] = $dao->where($condition)->field('report_time, his_long, his_lat')->order('report_time')->select();

            $personList[] = $seg;
//            p($carList);
        }

//        $condition['car_id'] = $carId;
//        $condition['report_time'] = array('egt', $startTime);
//        $condition['report_time'] = array('elt', $endTime);
//
//        $dao = M('car_his_pos');

        $ret['person_list'] = $personList;

        echo (wrapResult('CM0000', $ret));
    }

}