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
        grash_raffle_ticket();

        $this->config = config('double_ball');
        $this->model = new DoubleModel();
    }

    public function indexDo(){
        $issue = $this->config['issue'] - 1;
        $year = $this->config['particular_year'];
        $issue = create_raffle_format_issue($year, $issue);
        $item = $this->model->issue($issue)->find();
        $item['red_ball'] = unserialize($item['red_ball']);

        $lottery = new \Lottery();

        $list = $this->model->issueNoList([$this->config['issue'] - 1, $this->config['issue']])->select();
        if($list){
            foreach ($list as $key => $value){
                $value['red_ball'] = unserialize($value['red_ball']);
                $value['AC'] = compute_ac($value['issue']);
                //质数
                $prime = $lottery->getPrimeTimes(array('issue' => $value['issue']));
                $value['prime'] = $prime[$value['year']][$value['issue']]['redball']['prime'];
                $value['composite'] = $prime[$value['year']][$value['issue']]['redball']['composite'];
                $list[$key] = $value;
            }
        }

        $this->assign('current_lottery', $item);
        $this->assign('before_lottery', $list);
        $this->view->engine->layout('layout/layout');
        return $this->fetch();
    }
}