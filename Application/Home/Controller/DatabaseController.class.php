<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/10/13
 * Time: 20:42
 */

namespace Home\Controller;

use Org\Util\Date;


class DatabaseController
{
    //http://115.159.66.204/sanitation/?s=home/database/createDB
    //http://localhost/sanitation/?s=home/test/getCarTrackSegments

    var $hisLength = 60;
    var $localFileDir = 'C:\wamp\www\sanitation\res\data\\';
    var $fileDir = '/yjdata/www/thinkphp/sanitation/res/data/';

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

    var $carHisMap = array(
        array(121.523736,31.239422, 121.537066,31.244578, 121.542456,31.235007),   //陆家嘴
        array(121.530707,31.247573, 121.530994,31.245103, 121.536456,31.233),
        array(121.515112,31.247017, 121.521867,31.235655, 121.524311,31.228924),
        array(121.524455,31.228862, 121.536671,31.232938, 121.542672,31.234945),
        array(121.526576,31.246181, 121.529882,31.23871, 121.5352,31.226914),

        array(121.486088,31.235065, 121.487615,31.233104, 121.489232,31.230233),
        array(121.486788,31.235266, 121.486788,31.235266, 121.48889,31.232518),
        array(121.487327,31.235605, 121.488459,31.2341, 121.489681,31.232579),
        array(121.488172,31.23593, 121.489501,31.234092, 121.490471,31.232904),
        array(121.48995,31.236563, 121.490579,31.235497, 121.490579,31.235497),

        array(121.400407,31.2748, 121.408851,31.274924, 121.416756,31.274831),
        array(121.381543,31.263874, 121.399078,31.262762, 121.399797,31.274738),
        array(121.399725,31.271066, 121.4086,31.269399, 121.416146,31.268103),
        array(121.429225,31.270942, 121.428398,31.265818, 121.428183,31.261898),
        array(121.410181,31.267454, 121.411474,31.264059, 121.413774,31.256465)
    );

    private function  randomFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    public function createDB() {
        set_time_limit(0);

        $dao = M('employee');
        $k = 1;
        for($i = 0; $i < 3; $i++) {
            for($j = 0; $j < 5; $j++) {
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
        $personNum = $k-1;

        $dao = M('car');
        $dao->where('1=1')->delete();
        $k = 1;
        for($i = 0; $i < 3; $i++) {
            for($j = 0; $j < 5; $j++) {
                $data['id'] = $k;
//                $data['cur_long'] = $this->randomFloat($this->map[$i][0], $this->map[$i][2]);
//                $data['cur_lat'] = $this->randomFloat($this->map[$i][3], $this->map[$i][1]);
                $data['cur_long'] = $this->carHisMap[$k-1][0];
                $data['cur_lat'] = $this->carHisMap[$k-1][1];

                $data['update_time'] = time();
                $data['plate'] = '沪A'.(13871+$k);
                $data['company_id'] = $i + 1;
                $data['car_type'] = 101+($j%2);
//                $data['car_online'] = $data['car_state'] = $j%2;
                if($i == 0 && $j == 2) {
                    $data['car_online'] = 0;
                    $data['car_state'] = 0;
                    $data['injector_state'] = '良好';
                }
                else if($i == 0 && $j == 1) {
                    $data['car_online'] = 1;
                    $data['car_state'] = 1;
                    $data['injector_state'] = '异常';
                }
                else {
                    $data['car_online'] = 1;
                    $data['car_state'] = 0;
                    $data['injector_state'] = '良好';
                }
                $data['cur_velocity'] = sprintf("%.2f", $this->randomFloat(0, 80));
                $data['cur_oil_amount'] = sprintf("%.2f", $this->randomFloat(10, 60));
                $data['video_url'] = 'carMonitor.flv';
                $data['fan_speed'] = rand(1000, 2300);
                $data['tank_allowance'] = rand(10, 30);
//                $data['injector_state'] = '良好';
                $data['sweep_state'] = '良好';
                $data['fuel_quantity'] = rand(10, 20);
                $data['need_maintain'] = '否';
                $dao->add($data);
                $k++;
            }
        }
        $carNum = $k - 1;

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

        echo 'done';
    }

    public function createCarHis() {
        set_time_limit(0);

        $dao = M('car_his');
        $ymd = date('Y-m-d');
        $curDate = new Date($ymd);
        $dao->where('1=1')->delete();
        $workTime = 4*60*60;
        $deltaTime = 30;
        $pointNum = $workTime/$deltaTime;

//        $dir = __ROOT__.C('SIM_DATA_PATH');
        $carNum = 15;
        $id = 1;
        for($num = 1; $num <= $carNum; $num++) {
            $fileName = $this->fileDir.$num.'.txt';
            if(!file_exists($fileName)) {
                exit;
            }
            $list = $this->getPointList($fileName, $pointNum);
            for($day = -$this->hisLength; $day < 0; $day++) {
                $date = $curDate->dateAdd($day, 'd');
                $startTime = $date->getUnixTime();
                $startTime += 4 * 60 * 60 + rand(5 * 60, 15 * 60);

                $tNum = 0;
                $commit = array();
                for($i = 0; $i < count($list); $i++) {
                    $data['car_id'] = $num;
                    $data['his_velocity'] = sprintf("%.2f", $this->randomFloat(0, 80));
                    $data['his_oil_amount'] = sprintf("%.2f", $this->randomFloat(10, 50));
                    $data['report_time'] = $startTime + $tNum*$deltaTime;
                    $data['his_long'] = $list[$i]['his_long'];
                    $data['his_lat'] = $list[$i]['his_lat'];
                    $data['id'] = $id++;
                    $commit[] = $data;
                    $tNum++;
                }
                $dao->addAll($commit);
                $commit = null;
            }
        }

        echo 'done';
    }

    private function getPointList($fileName, $pointNum) {
//        $fileName = 'C:\wamp\www\sanitation\res\data\1.txt';

//        dump(file_exists('C:\wamp\www\sanitation\res\data\1.txt'));
        $content = file_get_contents($fileName);
//        dump($content);
        $list = explode('),', $content);
        $parseList = array();
        foreach($list as $str) {
            $pos1 = strpos($str, '(');
            $pos2 = strpos($str, ',');
            $point['his_long'] = substr($str, $pos1+1, $pos2-$pos1-1);
            $point['his_lat'] = substr($str, $pos2+1, strlen($str)-$pos2);
            $parseList[] = $point;
        }

        $lengthList = array();
        $dotNumList = array();
        $n = 0;
        $sumLength = 0;
        for($i = 1; $i < count($parseList); $i++) {
            $x1 = $parseList[$i-1]['his_long'];
            $y1 = $parseList[$i-1]['his_lat'];
            $x2 = $parseList[$i]['his_long'];
            $y2 = $parseList[$i]['his_lat'];

            $length = sqrt(($x2-$x1)*($x2-$x1) + ($y2-$y1)*($y2-$y1));
            $lengthList[] = $length;
            $sumLength += $length;
        }

        $dotNumList = array();
        foreach($lengthList as $length) {
            $dotNumList[] = round($length/$sumLength*($pointNum-count($lengthList)-1));
        }

        $result = array();
        for($i = 0; $i < count($dotNumList); $i++) {
            $point = null;
            $point['his_long'] = (float)$parseList[$i]['his_long'];
            $point['his_lat'] = (float) $parseList[$i]['his_lat'];
            $result[] = $point;
            for($j = 0; $j < $dotNumList[$i]; $j++) {
                $point['his_long'] = $this->getDotByIndex($parseList[$i]['his_long'], $parseList[$i+1]['his_long'], $dotNumList[$i], $j+1);
                $point['his_lat'] = $this->getDotByIndex($parseList[$i]['his_lat'], $parseList[$i+1]['his_lat'], $dotNumList[$i], $j+1);
                $result[] = $point;
            }
        }

        return $result;
    }

    public function createCarHis1() {
        set_time_limit(0);

        $dao = M('car_his');
        $ymd = date('Y-m-d');
        $curDate = new Date($ymd);
        $dao->where('1=1')->delete();
        $workTime = 4*60*60;
        $deltaTime = 3*60;
        $pointNum = $workTime/$deltaTime;
        $pieces = 2;
        $carNum = 15;
        $id = 1;
        for($num = 0; $num < $carNum; $num++) {
            for($day = -$this->hisLength; $day < 0; $day++) {
                $date = $curDate->dateAdd($day, 'd');
//                dump($date);
                $startTime = $date->getUnixTime();
//                $startTime = Date.parse($date);
//                dump($startTime);
                $startTime += 4*60*60 + rand(5*60, 15*60);
//                $endTime = $startTime + $workTime;

                $pointQ = $pointNum/$pieces;
                $tNum = 0;
                for($p = 0; $p < $pieces; $p++) {
                    for($i = 0; $i <= $pointQ; $i++) {
                        $data=null;
                        $data['his_long'] = $this->getDotByIndex($this->carHisMap[$num][$p*2], $this->carHisMap[$num][$p*2+2], $pointQ, $i);
                        $data['his_lat'] = $this->getDotByIndex($this->carHisMap[$num][$p*2+1], $this->carHisMap[$num][$p*2+3], $pointQ, $i);
                        $data['car_id'] = $num+1;
                        $data['his_velocity'] = sprintf("%.2f", $this->randomFloat(0, 80));
                        $data['his_oil_amount'] = sprintf("%.2f", $this->randomFloat(10, 50));
                        $data['report_time'] = $startTime + $tNum*$deltaTime;
                        $data['id'] = $id++;
//                        dump($data);
                        $dao->add($data);
                        $tNum++;
                    }
                }

            }
        }

        echo 'done';
    }

    public function createPersonHis() {
        set_time_limit(0);

        $dao = M('person_his');
        $ymd = date('Y-m-d');
        $curDate = new Date($ymd);
        $dao->where('1=1')->delete();
        $workTime = 8*60*60;
        $deltaTime = 20*60;
        $pointNum = $workTime/$deltaTime;
        $pieces = 2;
        $personNum = 15;
        $id = 1;
        for($num = 0; $num < $personNum; $num++) {
            for($day = -$this->hisLength; $day < 0; $day++) {
                $date = $curDate->dateAdd($day, 'd');
//                dump($date);
                $startTime = $date->getUnixTime();
//                $startTime = Date.parse($date);
//                dump($startTime);
                $startTime += 4*60*60 + rand(5*60, 15*60);
//                $endTime = $startTime + $workTime;

                $pointQ = $pointNum/$pieces;
                $tNum = 0;
                for($p = 0; $p < $pieces; $p++) {
                    for($i = 0; $i < $pointQ; $i++) {
                        $data=null;
                        $data['his_long'] = $this->getDotByIndex($this->carHisMap[$num][$p*2], $this->carHisMap[$num][$p*2+2], $pointQ, $i);
                        $data['his_lat'] = $this->getDotByIndex($this->carHisMap[$num][$p*2+1], $this->carHisMap[$num][$p*2+3], $pointQ, $i);
                        $data['person_id'] = $num+1;
                        $data['his_velocity'] = sprintf("%.2f", $this->randomFloat(0, 5));
                        $data['report_time'] = $startTime + $tNum*$deltaTime;
                        $data['id'] = $id++;
//                        dump($data);
                        $dao->add($data);
                        $tNum++;
                    }
                }
            }
        }

        echo 'done';
    }

    public function createCollectPointHis() {
        set_time_limit(0);

        $dao = M('collect_point_his');
        $ymd = date('Y-m-d');
        $curDate = new Date($ymd);
        $dao->where('1=1')->delete();
        $deltaTime = 60*60;
        $pointNum = 6;
        $id = 1;
        $lastFullNum = -1;
        for($num = 0; $num < $pointNum; $num++) {
            for($day = -$this->hisLength; $day < 0; $day++) {
                $date = $curDate->dateAdd($day, 'd');
//                dump($date);
                $startTime = $date->getUnixTime();
//                $startTime = Date.parse($date);
//                dump($startTime);
                $startWorkTime = $startTime + 8*60*60;
                $endWorkTime = $startTime + 18*60*60;

                for($i = $startTime; $i < $startTime+24*60*60; $i += $deltaTime) {
                    if($i >= $startWorkTime && $i <= $endWorkTime) {
                        $data['full_num'] = rand(0,10);
                        $data['delta_weight'] = sprintf("%.2f", $this->randomFloat(0, 1000));
                    }
                    else {
                        if($lastFullNum == -1) {
                            $data['full_num'] = rand(0,10);
                        }
                        else {
                            $data['full_num'] = $lastFullNum;
                        }
                        $data['delta_weight'] = 0;
                    }
                    $data['collect_point_id'] = $num+1;
                    $data['id'] = $id++;
                    $data['report_time'] = $i;
                    $dao->add($data);
                    $lastFullNum = $data['full_num'];
                }
            }
        }

        echo 'done';
    }

    public function createWasteStationHis()
    {
        set_time_limit(0);

        $dao = M('waste_station_his');
        $ymd = date('Y-m-d');
        $curDate = new Date($ymd);
        $dao->where('1=1')->delete();
        $workTime = 8*60*60;
        $deltaTime = 60*60;
        $pointNum = $workTime/$deltaTime;
        $pieces = 2;
        $stationNum = 7;
        $id = 1;
        $lastWaterLevel = -1;
        for($num = 0; $num < $stationNum; $num++) {
            for($day = -$this->hisLength; $day < 0; $day++) {
                $date = $curDate->dateAdd($day, 'd');
//                dump($date);
                $startTime = $date->getUnixTime();
//                $startTime = Date.parse($date);
//                dump($startTime);
                $startWorkTime = $startTime + 8*60*60;
                $endWorkTime = $startTime + 18*60*60;

                for($i = $startTime; $i < $startTime+24*60*60; $i += $deltaTime) {
                    if($i >= $startWorkTime && $i <= $endWorkTime) {
                        $data['water_level'] = sprintf("%.2f", $this->randomFloat(0, 2));
                        $data['delta_weight'] = sprintf("%.2f", $this->randomFloat(0, 1000));
                    }
                    else {
                        if($lastWaterLevel == -1) {
                            $data['water_level'] = sprintf("%.2f", $this->randomFloat(0, 2));
                        }
                        else {
                            $data['water_level'] = $lastWaterLevel;
                        }
                        $data['delta_weight'] = 0;
                    }
                    $data['waste_station_id'] = $num+1;
                    $data['id'] = $id++;
                    $data['report_time'] = $i;
                    $dao->add($data);
                    $lastWaterLevel = $data['water_level'];
                }
            }
        }

        echo 'done';
    }

    public function createCollectStationHis() {
        set_time_limit(0);

        $dao = M('collect_station_his');
        $ymd = date('Y-m-d');
        $curDate = new Date($ymd);
        $dao->where('1=1')->delete();
        $workTime = 8*60*60;
        $deltaTime = 60*60;
        $pointNum = $workTime/$deltaTime;
        $pieces = 2;
        $stationNum = 3;
        $id = 1;
        for($num = 0; $num < $stationNum; $num++) {
            for($day = -$this->hisLength; $day < 0; $day++) {
                $date = $curDate->dateAdd($day, 'd');
//                dump($date);
                $startTime = $date->getUnixTime();
//                $startTime = Date.parse($date);
//                dump($startTime);
                $startWorkTime = $startTime + 8*60*60;
                $endWorkTime = $startTime + 18*60*60;

                for($i = $startTime; $i < $startTime+24*60*60; $i += $deltaTime) {
                    if($i >= $startWorkTime && $i <= $endWorkTime) {
                        $data['delta_weight'] = sprintf("%.2f", $this->randomFloat(0, 1000));
                    }
                    else {
                        $data['delta_weight'] = 0;
                    }
                    $data['collect_station_id'] = $num+1;
                    $data['id'] = $id++;
                    $data['report_time'] = $i;
                    $dao->add($data);
                }
            }
        }

        echo 'done';
    }

    private function getDotByIndex($x1, $x2, $n, $index) {
        return $x1 + ($x2 - $x1)*($index*1.0/$n);
    }
}