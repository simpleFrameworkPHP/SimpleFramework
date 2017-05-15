<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-9-17
 * Time: 下午2:44
 */
define('IN_ADMIN',1);
if(!isset($_REQUEST['a'])){
    $_REQUEST['a'] = 'admin';
}
if(!isset($_REQUEST['c'])){
    $_REQUEST['c'] = 'index';
}
if(!isset($_REQUEST['f'])){
    $_REQUEST['f'] = 'index';
}

define("__PATH__",str_replace('\\','/',dirname(__FILE__)));
include_once(__PATH__.'/core/index.php');