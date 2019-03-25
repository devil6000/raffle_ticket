<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/21
 * Time: 10:09
 */

namespace app\index\controller;


use app\index\base\LoginBaseController;
use app\model\DoubleModel;

class IndexController extends LoginBaseController {

    protected $config;
    protected $model;

    protected function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
        //grash_raffle_ticket();

        $this->config = config('double_ball');
        $this->model = new DoubleModel();
    }

    public function indexDo(){
        set_time_limit(0);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, 'http://kaijiang.500.com/shtml/ssq/19029.shtml');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4);
        $html = curl_exec($ch);
        curl_close($ch);
        var_dump($html);die();

        $issue = $this->config['issue'] - 1;
        $year = $this->config['particular_year'];
        $issue = create_raffle_format_issue($year, $issue);
        $item = $this->model->where('issue', $issue)->find();
        $item['red_ball'] = unserialize($item['red_ball']);

        $list = $this->model->where('issue_no','in',[$this->config['issue'] - 1, $this->config['issue']])->order('id asc')->select();
        if($list){
            foreach ($list as $key => $value){
                $value['red_ball'] = unserialize($value['red_ball']);
                $list[$key] = $value;
            }
        }

        $this->assign('current_lottery', $item);
        $this->assign('before_lottery', $list);
        $this->view->engine->layout('layout/layout');
        return $this->fetch();
    }
}