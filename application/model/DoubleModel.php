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
    protected $resultSetType = 'collection';

    //查询单条记录
    protected function scopeIssue($query,$issue){
        $query->where('issue', $issue);
    }

    //查询多条记录
    protected function scopeIssueList($query, $data){
        $query->where('issue', 'in', $data)->order('year asc,issue_no asc');
    }

    protected function scopeIssueNoList($query, $data){
        $query->where('issue_no', 'in', $data)->order('year asc,issue_no asc');
    }

    //查询所有记录
    protected function scopeList($query){
        $query->order('year asc, issue_no asc');
    }

    //查询年范围内的所有记录
    protected function scopeYearList($query, $data){
        $query->where('year','in',$data)->order('year asc,issue_no asc');
    }

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
        $list = $this->where($condition)->order('id asc')->select();
        return $list->toArray();
    }
}