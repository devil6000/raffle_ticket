<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/21
 * Time: ����10:01
 */

namespace app\index\base;


use think\Config;
use think\Controller;

class BaseController extends Controller {

    protected function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Config::load(APP_PATH . 'lottery.php');
    }
}