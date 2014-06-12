<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-12
 * Time: 上午10:41
 */

class Cache {

    public static function initCacheMode($type,$param = ''){
        $cache_conf = C('SF_CACHE_CONF');
        switch($type){
            case 'FILE':
                $cache = &new FileCache($cache_conf[$type]['CACHE_PATH'],$cache_conf[$type]['TIME']);
                break;
            default:
                $cache = &new FileCache($cache_conf['FILE']['CACHE_PATH'],$cache_conf['FILE']['TIME']);
                break;
        }
        return $cache;
    }
} 