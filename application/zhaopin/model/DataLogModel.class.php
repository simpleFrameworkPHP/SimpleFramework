<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/30
 * Time: 上午9:06
 */

class DataLogModel extends Model {

    var $var_table = array('d_log'=>'data_log');//model默认表 array('简称'=>'表名');

    public function getLastLog($type){
        $data = $this->where(array('type'=>$type))->order(array('id'=>'desc'))->limit(1)->find();
        return $data;
    }
} 