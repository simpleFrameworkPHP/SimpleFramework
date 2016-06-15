<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/12
 * Time: 10:57
 */
class CategoryModel extends Model
{

    var $var_table = array('category'=>'n_category');

    public function getListByPage($where = array(), $page = 0, $limit = 10){
        $this->limit($limit, $page);
        return $this->getDataByWhere($where);
    }

    public function getFristCategory(){
        $where['category_sid'] = 0;
        return $this->getDataByWhere($where);
    }

    protected function getDataByWhere($where){
        if(!empty($where)){
            $this->where($where);
        }
        $result = $this->select();
        if(!$result){
            $result = array();
        }
        return $result;
    }
}