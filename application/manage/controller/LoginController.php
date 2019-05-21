<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/20
 * Time: 2:16
 */

namespace app\manage\controller;


use app\manage\base\BaseController;
use app\manage\model\UserModel;
use think\Session;

class LoginController extends BaseController {

    protected function init() {
        // TODO: Implement init() method.
        $this->systemTitle = '系统登录';
    }

    public function indexDo(){
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

            Session::init(['prefix' => 'login','type' => '', 'auto_start' => true, 'expire' => time() + 3600]);
            Session::set('uid',$user['id']);
            Session::set('user',$user);

            $ret_url = input('ret_url','');
            $ret_url = empty($ret_url) or (strpos('login', $ret_url) !== false) ? url('index') : urldecode($ret_url);
            var_dump($ret_url);die();
            $this->success('登录成功!', $ret_url,3);
        }
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function registerDo(){
        if(request()->isPost()){
            $username = input('post.username','');
            $password = input('post.password', '');
            $signKey = get_random(5);
            $password = md5($password . $signKey);
            $insert = array(
                'username' => $username,
                'password' => $password,
                'jurisdiction' => -1,
                'signkey' => $signKey
            );
            UserModel::create($insert);
            $this->success('注册成功!', url('index'), 3);
        }
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function outLoginDo(){
        Session::clear('login');
        $this->success('退出成功!', url('login'), 3);
    }

    public function forgetDo(){

    }

}