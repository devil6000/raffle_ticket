<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/5/21
 * Time: 下午3:22
 */

namespace app\manage\model;


use think\Model;

class FieldsModel extends Model {
    protected $pk = 'id';

    protected function getList(){
        return $this->order('id asc')->paginate();
    }

    //新增
    protected function add($data){
        $id = $this->insert($data);
        return $id;
    }

    //编辑
    protected function edit($id,$data){
        $id = $this->where('id',$id)->save($data);
        return $id;
    }

    //获取一条记录
    protected function getOne($id){
        return $this->where('id', $id)->find();
    }

    //删除记录
    protected function del($id){
        $this->where('id', $id)->delete();
    }
}