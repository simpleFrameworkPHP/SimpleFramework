<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/31
 * Time: 下午1:40
 */

class CommonController extends Controller {

    public static $db_num = 0;

    public static function getLastLogTime($type){
        $time = date('Y-m-d');
        $data = M('DataLog',self::$db_num)->getLastLog($type);
        if(!empty($data)){
            $time = $data['content'];
        }
        return $time;
    }

    public static function getUrl($pid,$from_type){
        $url = '';
        switch($from_type){
            case 'lagou':$url = "http://www.lagou.com/jobs/".$pid.".html";break;
        }
        return $url;
    }

    public static function getFrom($from_type){
        $from_list = array('lagou'=>'拉勾网');
        $from = isset($from_list[$from_type]) ? $from_list[$from_type] : '';
        return $from;
    }

    public static function getCompanyUrl($cid,$from_type){
        $url = '';
        switch($from_type){
            case 'lagou':$url = "http://www.lagou.com/gongsi/{$cid}.html";break;
        }
        return $url;
    }

    //工作年限数据
    public static function showWorkYear(){
        $Dworkyear = M('zhaopin/WorkYear',self::$db_num);
        $data = $Dworkyear->fields(array('id','title'))->select();
        $workyearlist = array();
        foreach($data as $value){
            $workyearlist[$value['id']] = $value['title'];
        }
        return $workyearlist;
    }
} 