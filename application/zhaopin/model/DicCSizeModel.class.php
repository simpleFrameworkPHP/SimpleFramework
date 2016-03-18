<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/17
 * Time: 下午4:43
 */

class DicCSizeModel extends Model {

    var $var_table = array('d_c_size'=>'dic_company_size');//model默认表 array('简称'=>'表名');

    public function gitMinSizeList(){
        $size_list = $this->select();
        $min_size_list = array();
        foreach($size_list as $row){
            $min_size_list[$row['id']] = $row['min_user'];
        }
        return $min_size_list;
    }
} 