<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/11/17
 * Time: 18:58
 */

namespace Home\Controller;

use Think\Log;

class ClientController extends BaseController
{
    public function postCarData() {
        $data = I('post.data');

        dump($data);

        if($data == null) {
            wrapResult('CM0003');
        }

        echo(wrapResult('CM0000', $data));
    }

}