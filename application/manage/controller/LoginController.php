<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/20
 * Time: ÏÂÎç2:16
 */

namespace app\manage\controller;


use app\manage\base\BaseController;

class LoginController extends BaseController {

    protected function init() {
        // TODO: Implement init() method.
    }

    public function indexDo(){
        if(request()->isPost()){

        }
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function registerDo(){

    }

    public function outLoginDo(){

    }

    public function forgetDo(){

    }

}