<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/2/23
 * Time: 22:43
 */
$path = explode('/',getcwd());
$crontab_path = end($path);
unset($path[count($path)-1]);
$app_path = end($path);
define('__PATH__',dirname(dirname(__FILE__)));
include_once("start.php");
loadDirFile(dirname(getcwd())."/common");
