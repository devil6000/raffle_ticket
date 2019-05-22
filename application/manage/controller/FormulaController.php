<?php
/**
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/5/10
 * Time: 下午1:51
 */

namespace app\manage\controller;


use app\manage\base\LoginBaseController;
use app\model\FormulaModel;

class FormulaController extends LoginBaseController {

    public function init() {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function indexDo(){
        $keyWord = input('keyWord','');
        $conditions = array();
        if(!empty($keyWord)){
            $conditions = array('title', array('like',"%{$keyWord}%"));
        }
        $list = (new FormulaModel())->getList($conditions);
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('keyWord', $keyWord);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function addDo(){
        $this->post();
    }

    public function editDo(){
        $this->post();
    }

    protected function post(){
        $id = input('id',0);
        $model = new FormulaModel();
        if(request()->isPost()){
            $title = input('post.title', '');
            $formula = input('post.formula','');
            if(empty($formula)){
                $this->error('公式不能为空!',url('manage/formula/index'), 3);
            }

            $data = array(
                'title' => $title,
                'formula'   => $formula
            );
            if(empty($id)){
                $data['accuracy'] = 0;
                $result = $model->add($data);
            }else{
                $result = $model->edit($data);
            }

            if(empty($result)){
                $this->error('编辑公式失败!', url('manage/formula/index'), 3);
            }

            $this->success('编辑公式成功!', url('manage/formula/index'),3);
        }
        $item = $model->getFormula($id);
        $this->assign('item', $item);
        $this->assign('id', $id);
        return $this->fetch('add');
    }
}