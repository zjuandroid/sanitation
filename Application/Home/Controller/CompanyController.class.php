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
        $managerId =  I('post.managerId');

        $dao = M('company');
        $data = $dao->field('ID, COMPANY_NAME')->select();

        $ret['companyList'] = $data;

        echo (wrapResult('CM0000', $ret));
    }
}