<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/20
 * Time: 5:00
 */

namespace app\manage\model;


use think\Model;

class JurisdictionModel extends Model {
    protected $autoWriteTimestamp = true;
    protected $pk = 'id';
    protected $updateTime = false;

    public function user(){
        return $this->hasMany('UserModel');
    }
}