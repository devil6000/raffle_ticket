<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/21
 * Time: 10:38
 */

namespace app\model;


use think\Model;

class DoubleModel extends Model {
    protected $pk = 'id';

    public function getYearList(){
        $info = $this->order('id asc')->limit(1)->find();
        $info = $info->toArray();
        $year = $info['year'];
        $cur = date('y', time());
        $year_list = array();
        for (;$year <= $cur; $year++){
            $year_list[] = 2000 + $year;
        }
        return $year_list;
    }

    public function getNumberList($condition = array()){
        $list = $this->where($condition)->order('id desc')->select();
        var_dump($list);die();
        return $list->toArray();
    }
}