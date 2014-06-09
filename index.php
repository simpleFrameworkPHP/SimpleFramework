<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:19
 */
$self =explode('/',$_SERVER['PHP_SELF']);
define("__ROOT__",count($self) == 3 ?$_SERVER['DOCUMENT_ROOT'].'/'.$self[1] : $_SERVER['DOCUMENT_ROOT']);
include_once(__ROOT__.'/core/index.php');
$config = include_once(__ROOT__."/conf/config.inc.php");
