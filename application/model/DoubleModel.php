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
        return $info;
    }
}