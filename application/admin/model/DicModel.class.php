<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/6/22
 * Time: 07:28
 */

class DicModel extends Model
{

    var $var_table = array('dic'=>'n_dic');

    public function getDicByType($type){
        $where['dic_type'] = $type;
        $where['dic_status'] = 1;
        $data = $this->fields(array('dic_key','dic_val'))->where($where)->select();
        $result = array();
        foreach($data as $row){
            $result[$row['dic_key']] = $row['dic_val'];
        }
        return $result;
    }

} 