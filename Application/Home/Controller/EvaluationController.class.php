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
    public function getCarEvluation() {
        $time = time();
        echo date('Y-m-d', $time);
    }
}