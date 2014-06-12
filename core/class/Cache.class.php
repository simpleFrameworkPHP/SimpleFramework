<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-12
 * Time: 上午10:41
 */

class Cache {

    public static function initCacheMode($type,$param = ''){
        $cache_conf = C('sf_cache_conf');
        switch($type){
            case 'FILE':
                $cache = &new FileCache($cache_conf[$type]['cache_path'],$cache_conf[$type]['time']);
                break;
            default:
                $cache = &new FileCache($cache_conf[$type]['cache_path'],$cache_conf[$type]['time']);
                break;
        }
        return $cache;
    }
} 