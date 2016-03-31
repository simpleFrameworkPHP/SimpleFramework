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
        return $this->where(array('type'=>$type))->order(array('add_time'=>'desc'))->limit(1)->find();
    }
} 