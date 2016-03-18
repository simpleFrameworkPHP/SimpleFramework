<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/17
 * Time: 上午11:44
 */

class DicSalaryModel extends Model {

    var $var_table = array('d_salary'=>'dic_salary');//model默认表 array('简称'=>'表名');

    public function getMinSalaryList(){
        $salary_list = $this->select();
        $min_salary_list = array();
        foreach($salary_list as $row){
            $min_salary_list[$row['id']] = $row['min_salary'];
        }
        return $min_salary_list;
    }
} 