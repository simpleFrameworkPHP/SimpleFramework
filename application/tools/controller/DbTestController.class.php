<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-19
 * Time: 下午3:59
 */

class DbTestController extends Controller {

    public function index(){
//        $model = M();
//        $data = $model->table(array('adfs'=>'GLOBAL_STATUS'))->fields('VARIABLE_NAME AS a')->select();
//        print_r($model->db->sql_str.'<br/>');
//        $data = $model->table(array('adfs'=>'GLOBAL_STATUS'))->fields(array('VARIABLE_NAME AS a'))->select();
//        print_r($model->db->sql_str.'<br/>');
//        $data = $model->table(array('adfs'=>'GLOBAL_STATUS'))->fields(array('a'=>'VARIABLE_NAME'))->where(array('VARIABLE_NAME'=>0,'VARIABLE_VALUE'=>0))->order(array('VARIABLE_VALUE'=>'abs'))->group('VARIABLE_VALUE')->select();
//        print_r($model->db->sql_str.'<br/>');
//        $model1 = M('ABC');
//        $data = $model1->join(array('GLOBAL_STATUS'=>array('as'=>'bbb','p'=>'bbb.VARIABLE_NAME=abc.ENGINE')))->fields(array('VARIABLE_NAME AS a'))->group('VARIABLE_NAME')->having(array('VARIABLE_NAME'=>0))->limit(1)->select();
//        print_r($model1->db->sql_str.'<br/>');
//        $this->assign('data',$data);
        $model = M('',3);
//        $data = $model->select('SELECT placeid,placename,width,height,adids,expend FROM cms_ads_place');
        $data = $model->table(array('aa'=>'cms_ads_place'))->fields('placeid,placename,width,height,adids,expend')->select();
//        print_r($model);
//        $file = fopen('ads_place.csv',"a");
//        fwrite($file,'placeid,placename,width,height,adids,expend'."\r\n");
//        foreach($data as $row){
//                fwrite($file,implode('##',$row)."\r\n");
//        }
//        fclose($file);
//        $data = array("adids"=>123,'placename'=>'nihao');
//        echo $model->table(array("cms_ads_place"))->add($data);
//        $model->table(array("cms_ads_place"))->where($data)->set(array('adids'=>12344));
//        $model->table(array("cms_ads_place"))->where($data)->delete();
        $this->display();
    }

} 