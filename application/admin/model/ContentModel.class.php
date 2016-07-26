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
        if(!empty($where)){
            $this->where($where);
        }
        $sum_row = $this->fields('count(1)',false)->find();
        $this->fields()->limit($limit, $page);
        $data['data'] = $this->getDataByWhere($where);
        $data['cur_page'] = $page;
        $data['limit'] = $limit;
        $data['sum_page'] = ceil($sum_row['count(1)'] / $limit);
        return $data;
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