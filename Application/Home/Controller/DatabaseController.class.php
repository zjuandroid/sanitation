<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/10/13
 * Time: 20:42
 */

namespace Home\Controller;


class DatabaseController
{
    //http://115.159.66.204/sanitation/?s=home/database/createDB

    var $map = array(
        array(121.522011,31.243158, 121.542259,31.235007),
        array(121.489115,31.236524, 121.49914,31.23109),
        array(121.402965,31.277869, 121.414967,31.269721),
        array(121.66291,31.191031, 121.675666,31.183771),
        array(121.638828,31.061651, 121.643176,31.059424)
    );

    var $dustbinMap = array(
      array(121.580966,31.214511,121.607412,31.20364),
      array(121.609999,31.243414, 121.630983,31.22637),
     array(121.652368,31.279991,121.672778,31.254804),
        array(121.677665,31.240479, 121.736019,31.217011),
        array(121.393221,31.265545, 121.416073,31.238254),
        array(121.451415,31.397394, 121.472112,31.34831),
        array(121.510048,31.245348, 121.520684,31.238555),
    );

    private function  randomFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    public function createDB() {

        $dao = M('employee');
        $k = 1;
        for($i = 0; $i < 4; $i++) {
            for($j = 0; $j < 10; $j++) {
                $data['id'] = $k;
                $data['cur_long'] = $this->randomFloat($this->map[$i][0], $this->map[$i][2]);
                $data['cur_lat'] = $this->randomFloat($this->map[$i][3], $this->map[$i][1]);
                $data['update_time'] = time();
                $data['employee_no'] = sprintf("%04d", $j+1);
                $data['company_id'] = $i + 1;
                $dao->save($data);
                $k++;
            }
        }

        $dao = M('car');
        $dao->where('1=1')->delete();
        $k = 1;
        for($i = 0; $i < 4; $i++) {
            for($j = 0; $j < 10; $j++) {
                $data['id'] = $k;
                $data['cur_long'] = $this->randomFloat($this->map[$i][0], $this->map[$i][2]);
                $data['cur_lat'] = $this->randomFloat($this->map[$i][3], $this->map[$i][1]);
                $data['update_time'] = time();
                $data['plate'] = '沪A'.(13871+$k);
                $data['company_id'] = $i + 1;
                $data['car_type'] = 100+($j%2);
                $data['car_online'] = $data['car_state'] = $j%2;
                $data['cur_velocity'] = sprintf("%.2f", $this->randomFloat(0, 80));
                $data['cur_oil_amount'] = sprintf("%.2f", $this->randomFloat(10, 60));
                $data['cur_oil_amount'] = 'carMonitor.flv';
                $dao->add($data);
                $k++;
            }
        }

        $dao = M('dustbin');
        $dao->where('1=1')->delete();
        $k = 1;
        for($i = 0; $i < 7; $i++) {
            for($j = 0; $j < 10; $j++) {
                $data['id'] = $k;
                $data['cur_long'] = $this->randomFloat($this->dustbinMap[$i][0], $this->dustbinMap[$i][2]);
                $data['cur_lat'] = $this->randomFloat($this->dustbinMap[$i][3], $this->dustbinMap[$i][1]);
                $data['update_time'] = time();
                $data['online'] = $data['state'] = $j%2;
                $data['collect_point_id'] = $i+1;
                $data['dustbin_no'] = sprintf("%04d", $j+1);
                $dao->add($data);
                $k++;
            }
        }

    }

}