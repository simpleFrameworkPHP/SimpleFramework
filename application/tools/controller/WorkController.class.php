<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/15
 * Time: 10:37
 */
class WorkController extends Controller
{
    public function getKeyCode(){
        $code = '';
        if($_POST['mobile']){
            $data = M('cms_member_sendtel',1)->fields('code')->where(array('tel'=>$_POST['mobile']))->find();
            if(!empty($data)){
                $code = $data['code'];
            }
        }
        $this->assign('code',$code);
        $this->assign('mobile',$_POST['mobile']);
        $this->display();
    }

    public function loadLog(){
        $file = 'error (2).log';
        $fp = fopen($file, "r");
        $data = array();
        $type_key = isset($_POST['key']) ? strtolower($_POST['key']) : 'school';
        $tk = ( isset($_POST['type']) && $_POST['type'] <> '' ) ? $_POST['type'] : 'default';
        while(!feof($fp))
        {
            //fgets() Read row by row
            $str = fgets($fp);
            $list = explode(',',$str);
            $cur = current($list);
            $request = $this->formatStr($cur);
            unset($list[0]);
            foreach($list as $value){
                preg_match('/\w+/',$value,$key);
                $key = current($key);
                $value1 = preg_replace('/(\w+):/','',$value);
                $request[$key] = $value1;
            }
            $bool = $type_key == '' ? true : strstr(strtolower($request['str']),$type_key);
            if($bool && isset($request['referrer'])){
                $data[$type_key]['default'][$request['str']] = $request;
            }

            if($bool && !isset($request['referrer'])){
                $data[$type_key]['black'][$request['client']] = $request;
            }
        }
        fclose ($fp);
        $this->assign('data',$data[$type_key][$tk]);
        $this->assign('key',$type_key);
        $this->assign('black',$tk);
        $this->display();
    }

    public function formatStr($str){
        preg_match('/\d{4}\/\d{2}\/\d{2} \d{2}\:\d{2}\:\d{2}/',$str,$time);
        $time = current($time);
        preg_match('/\[(\w+)\]/',$str,$type);
        $type = current($type);
        $str = preg_replace('/\d{4}\/\d{2}\/\d{2} \d{2}\:\d{2}\:\d{2} \[\w+\] (\d){4}\#0\: \*(\d){7} /','',$str);
        return array('time'=>$time,'type'=>$type,'str'=>$str);
    }
}