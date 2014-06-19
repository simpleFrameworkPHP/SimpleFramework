<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:52
 */

class ListController extends Controller {

    public function index(){
        $model = M();
        $data = $model->table(array('adfs'=>'GLOBAL_STATUS'))->fields('VARIABLE_NAME AS a')->select();
        print_r($model->db->sql_str);
        $this->display();
    }
} 