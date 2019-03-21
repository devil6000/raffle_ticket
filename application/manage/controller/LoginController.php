<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/20
 * Time: ����2:16
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
                $this->error('�û���������!');
                exit;
            }

            $password = md5($password . $user['signkey']);

            $user = $model->where(array('username' => $username, 'password' => $password))->find();

            if(empty($user)){
                $this->error('�û������������!');
                exit;
            }

            //���浽session
            Session::init(['prefix' => 'login','type' => '', 'auto_start' => true, 'expire' => time() + 3600]);
            Session::set('uid',$user['id']);
            Session::set('user',$user);

            $ret_url = input('ret_url','');
            $ret_url = empty($ret_url) ? url('index') : urldecode($ret_url);

            $this->success('��¼�ɹ�!', $ret_url,3);
        }
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function registerDo(){

    }

    public function outLoginDo(){
        Session::clear('login');
        $this->success('�˳��ɹ�!', url('login'), 3);
    }

    public function forgetDo(){

    }

}