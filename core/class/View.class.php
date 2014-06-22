<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-16
 * Time: 下午1:31
 */

class View {


    public function replaceParam($key,$value){
        $str = '<?php $'.$key.'=';
        $str .= $this->replaceValue($value);
        $str .="; ?>\r\n";
        return $str;
    }

    function replaceArray($data){
        $str = 'array(';
        if(count($data)){
            $i_data = array();
            foreach($data as $key=>$value){
                $i_data[$key] = '';
                $i_data[$key] .= $this->replaceValue($key);
                $i_data[$key] .= '=>';
                $i_data[$key] .= $this->replaceValue($value);
            }
            $str .= implode(',',$i_data);
        }
        $str .= ')';
        return $str;
    }

    function replaceValue($value){
        $str = '';
        if(is_array($value)){
            $str .= $this->replaceArray($value);
        } elseif(is_string($value)){
            $str .= '\''.addslashes($value).'\'';
        } elseif(is_bool($value)){
            $str = $value?'true':'false';
        } elseif(is_null($value)){
            $str .= 'NULL';
        } else {
            $str .= '\''.addslashes($value).'\'';
        }
        return $str;
    }

    function rander($content,$charset = '',$contentType = ''){
        if(empty($charset))  $charset = C('DEFAULT_CHARSET');
        if(empty($contentType)) $contentType = C('TMPL_CONTENT_TYPE');
        // 网页字符编码
        header('Content-Type:'.$contentType.'; charset='.$charset);
        header('Cache-control: '.C('HTTP_CACHE_CONTROL'));  // 页面缓存控制
        header('X-Powered-By:Fish');

        echo $content;
    }
} 