<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/20
 * Time: 下午2:16
 */

namespace app\manage\controller;


use app\manage\base\BaseController;
use app\manage\model\UserModel;
use think\Session;

class LoginController extends BaseController {

    protected function init() {
        // TODO: Implement init() method.
    }

    public function indexDo(){
        var_dump('111');die();
        if(request()->isPost()){
            $username = input('post.username');
            $password = input('post.password');

            $model = new UserModel();
            $user = $model->where(array('username' => $username))->find();

            if(empty($user)){
                $this->error('用户名不存在!');
                exit;
            }

            $password = md5($password . $user['signkey']);

            $user = $model->where(array('username' => $username, 'password' => $password))->find();

            if(empty($user)){
                $this->error('用户名或密码错误!');
                exit;
            }

            //保存到session
            Session::init(['prefix' => 'login','type' => '', 'auto_start' => true, 'expire' => time() + 3600]);
            Session::set('uid',$user['id']);
            Session::set('user',$user);

            $ret_url = input('ret_url','');
            $ret_url = empty($ret_url) ? url('index') : urldecode($ret_url);

            $this->success('登录成功!', $ret_url,3);
        }
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function registerDo(){

    }

    public function outLoginDo(){
        Session::clear('login');
        $this->success('退出成功!', url('login'), 3);
    }

    public function forgetDo(){

    }

}