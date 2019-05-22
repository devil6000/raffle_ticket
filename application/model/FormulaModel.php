<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/5/10
 * Time: 下午1:58
 */

namespace app\model;


use think\Model;

class FormulaModel extends Model {
    protected $pk = 'id';
    //protected $resultSetType = 'collection';

    public function getList($conditions = array()){
        $list = $this->where($conditions)->paginate();
        return $list;
    }

    public function getFormula($id){
        $item = $this->where('id',$id)->find();
        return $item;
    }

    public function add($data){
        $id = $this->insert($data);
        return $id;
    }

    public function edit($data){
        $id = $this->save($data);
        return $id;
    }
}