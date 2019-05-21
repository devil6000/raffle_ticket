<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/20
 * Time: 8:50
 */

namespace app\manage\base;


use app\manage\model\UserModel;
use think\Session;

class LoginBaseController extends BaseController {

    protected function init() {
        //获取会员信息
        $model = new UserModel();
        $user = $model->idByItem(Session::get('uid','login'))->find();
        $this->assign('user', $user);
    }
}