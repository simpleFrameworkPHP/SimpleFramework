<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-17
 * Time: 下午3:05
 */

class Log {

    public static function write($title,$info,$file = 'log'){
        $path = CACHE_PATH.'/log/'.$file.'_'.date('Y_m_d').'.txt';
        if(!is_dir($path)){
            addDir($path);
        }
        $content = '['.date('Y-m-d H:n:s',nowTime()).'] '.$title.' LOG : '.$info . ".\r\n";
        file_put_contents($path,$content,FILE_APPEND);
    }
} 