<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/21
 * Time: ����10:09
 */

namespace app\index\controller;


use app\index\base\LoginBaseController;
use app\model\DoubleModel;

class IndexController extends LoginBaseController {

    protected $config;
    protected $model;

    protected function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
        grash_raffle_ticket();

        $this->config = config('double_ball');
        $this->model = new DoubleModel();
    }

    public function indexDo(){
        //��ȡ�����ںŵĺ���
        $issue = $this->config['issue'] - 1;    //��һ��
        $year = $this->config['particular_year'];
        $issue = create_raffle_format_issue($year, $issue);
        $item = $this->model->where('issue', $issue)->find();
        $item['red_ball'] = unserialize($item['red_ball']);

        //��ȡ������ݱ��ں���һ�ڵĺ���
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