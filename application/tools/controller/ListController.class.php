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
        print_r($model->db->sql_str.'<br/>');
        $data = $model->table(array('adfs'=>'GLOBAL_STATUS'))->fields(array('VARIABLE_NAME AS a'))->select();
        print_r($model->db->sql_str.'<br/>');
        $data = $model->table(array('adfs'=>'GLOBAL_STATUS'))->fields(array('a'=>'VARIABLE_NAME'))->where(array('VARIABLE_NAME'=>0,'VARIABLE_VALUE'=>0))->order(array('VARIABLE_VALUE'=>'abs'))->group('VARIABLE_VALUE')->select();
        print_r($model->db->sql_str.'<br/>');
        $this->display();
    }
} 