<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 如果某个控制器必须用户登录才可以访问  
 * 请继承该控制器
 */
class BaseController extends Controller {
    public function _initialize()
    {
        if(!IS_POST){
            exit(wrapResult('CM0001'));
        }
    }

}