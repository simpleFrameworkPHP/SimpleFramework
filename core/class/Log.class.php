<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-17
 * Time: 下午3:05
 */

class Log {

    public static function write($title,$info,$file_prefix = 'log'){
        $path = CACHE_PATH.'/log/';
        if(!is_dir($path)){
            addDir($path);
        }
        $path .= $file_prefix.'_'.date('Y_m_d').'.txt';
        $content = '['.date('Y-m-d H:i:s',nowTime()).'] '.$title.' LOG : '.$info . ".\r\n";
        file_put_contents($path,$content,FILE_APPEND);
    }
} 