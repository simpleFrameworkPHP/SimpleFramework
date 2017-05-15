<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/18
 * Time: 下午2:07
 */

class DicPositionTypeModel extends Model {

    var $var_table = array('d_p_type'=>'dic_position_type');//model默认表 array('简称'=>'表名');

    public function getPNameListInId($ids){
        $ids = array_unique($ids);
        $ids = array_merge(array('in'),$ids);
        $list = $this->where(array('id'=>$ids))->select();
        $result = array();
        foreach($list as $key=>$value){
            $result[$value['id']] = $value['pos_name'];
        }
        return $result;
    }

    public function getIdListByType($type){
        $list = $this->where(array('pos_type'=>intval($type)))->select();
        $result = array();
        foreach($list as $key=>$value){
            $result[$value['pos_name']] = $value['id'];
        }
        return $result;
    }

    public function getPTListByPid($pid = 0){
        if(!$pid) $where['pos_type'] = 2;
        else $where['pid'] = $pid;
        $result = $this->where($where)->select();
        return $result;
    }
} 