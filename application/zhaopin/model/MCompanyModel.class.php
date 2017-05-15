<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/17
 * Time: 上午11:10
 */

class MCompanyModel extends Model {

    var $var_table = array('m_company'=>'model_company');//model默认表 array('简称'=>'表名');

    public function getCNameListInId($ids){
        $ids = array_unique($ids);
        $ids = array_merge(array('in'),$ids);
        $list = $this->where(array('id'=>$ids))->select();
        $result = array();
        foreach($list as $key=>$value){
            $result[$value['id']] = $value['company_name'];
        }
        return $result;
    }

    public function getCInfoListInId($ids){
        $ids = array_unique($ids);
        $ids = array_merge(array('in'),$ids);
        $list = $this->where(array('id'=>$ids))->select();
        $result = array();
        foreach($list as $key=>$value){
            $result[$value['id']] = $value;
        }
        return $result;
    }
} 