<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/20
 * Time: 3:29
 */

namespace app\manage\model;


use think\Model;

class UserModel extends Model {
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $pk = 'id';
    //protected $fk = 'jurisdiction';

    /*
    public function jurisdiction(){
        return $this->belongsTo('JurisdictionModel');
    }
    */

    //通过ID获取会员信息
    protected function scopeIdByItem($query,$id){
        $query->where('id',$id);
    }
}