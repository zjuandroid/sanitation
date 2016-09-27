<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/9/27
 * Time: 21:07
 */
namespace Home\Controller;
use Think\Log;

class ReportController extends BaseController
{
    public function getCarReport()
    {
        $companyId = I('post.companyId');
        $plate = I('post.plate');
        $startTime = I('post.startTime');
        $endTime = I('post.endTime');

        if(empty($startTime) || empty($endTime) || $endTime-$startTime>C('MAX_REPORT_TIME_SPAN')) {
            exit(wrapResult('CM0002'));
        }
    }
}