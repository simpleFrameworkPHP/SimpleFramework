<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/16
 * Time: 下午2:14
 */

class DicIndustryModel extends Model {

    var $var_table = array('dic_ind'=>'dic_company_industry');//model默认表 array('简称'=>'表名');

    public function getAllData(){
        return $this->db->select();
    }
} 