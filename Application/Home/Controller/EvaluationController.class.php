<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/10/9
 * Time: 20:55
 */
namespace Home\Controller;

use Think\Log;

class EvaluationController extends BaseController {
    public function getCarEvaluation() {
        $companyId = I('post.companyId');
        $plate = I('post.plate');
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_EVALUATION_TIME_SPAN')) {
            exit(wrapResult('CM0002'));
        }

        $beginDate = date('Y-m-d', $startTime);
        $endDate = date('Y-m-d', $endTime);

        $condition['date_time'] = array(array('egt', $beginDate), array('elt', $endDate));

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
        if($plate) {
            $condition['plate'] = array('like', '%'.$plate.'%');
        }

        $dao = M('evaluation_car');
        $data = $dao->where($condition)->alias('t1')->join('left join san_car t2 ON t1.car_id=t2.id')->join('left join san_company t3 ON t2.company_id=t3.id')->field('t1.id, t1.car_id, t1.date_time, t1.preset_road, t1.preset_start_time, t1.preset_end_time, t1.preset_velocity, t1.preset_work_times, t1.actual_start_time, t1.actual_end_time, t1.actual_max_velocity, t1.actual_velocity, t1.actual_work_times, t1.reaching_standard_rate, t2.plate, t2.car_type, t2.company_id, t3.company_name')->order('t2.company_id, t1.car_id, t1.date_time')->select();

        $i = 0;
        foreach($data as $item) {
            $data[$i]['car_type_desc'] = $this->getDeviceType($item['car_type']);
            $i++;
        }

        $ret['evaluation_records'] = $data;

        echo (wrapResult('CM0000', $ret));
    }

    private function getDeviceType($id) {
        $table = C('DEVICE_TYPE');
        foreach($table as $item) {
            if($item['id'] == $id) {
                return $item['name'];
            }
        }

        return null;
    }

    public function getPersonEvaluation() {
        $companyId = I('post.companyId');
        $employeeNo = I('post.employeeNo');
        $name = I('post.name');
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_EVALUATION_TIME_SPAN')) {
            exit(wrapResult('CM0002'));
        }

        $beginDate = date('Y-m-d', $startTime);
        $endDate = date('Y-m-d', $endTime);

        $condition['date_time'] = array(array('egt', $beginDate), array('elt', $endDate));

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
        if($employeeNo) {
            $condition['employee_no'] = array('employee_no', '%'.$employeeNo.'%');
        }
        if($name) {
            $condition['name'] = array('name', '%'.$name.'%');
        }

        $dao = M('evaluation_person');
        $data = $dao->where($condition)->alias('t1')->join('left join san_employee t2 ON t1.person_id=t2.id')->join('left join san_company t3 ON t2.company_id=t3.id')->field('t1.id, t1.person_id, t1.date_time, t1.preset_road, t1.preset_start_time, t1.preset_end_time, t1.preset_length, t1.actual_start_time, t1.actual_end_time, t1.actual_length, t1.reaching_standard_rate, t2.name, t2.employee_no, t2.company_id, t3.company_name')->order('t2.company_id, t1.person_id, t1.date_time')->select();

        $ret['evaluation_records'] = $data;

        echo (wrapResult('CM0000', $ret));
    }

    public function getCompanyEvaluation() {
        $companyId = I('post.companyId');
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_EVALUATION_TIME_SPAN')) {
            exit(wrapResult('CM0002'));
        }

        $beginDate = date('Y-m-d', $startTime);
        $endDate = date('Y-m-d', $endTime);

        if($companyId) {
            $condition['company_id'] = $companyId;
        }
    }
}