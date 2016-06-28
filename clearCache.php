<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-19
 * Time: 下午4:08
 */
define("__PATH__",str_replace('\\','/',dirname(__FILE__)));
include(__PATH__.'/core/start.php');
$cache = Cache::initCacheMode(C('SF_CACHE_MODE'));
$cache->clearCache();
