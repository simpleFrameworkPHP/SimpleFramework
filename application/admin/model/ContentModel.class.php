<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/20
 * Time: 15:01
 */
class ContentModel extends Model
{

    var $var_table = array('content'=>'n_content');

    public function getListByPage($where = array(), $page = 0, $limit = 10){
        $this->limit($limit, $page);
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