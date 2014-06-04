<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午2:59
 */
return array(
    'sf_db_connect'=>array(
        0 => array(
            'dbhost'=>'',
            'dbport'=>3306,
            'dbuser'=>'',
            'dbpass'=>'',
            'dbmode'=>'mysql',
        ),
    ),
    "type" => array(
        0 =>array("name"=>"表目录","value"=>"table"),
        1 =>array("name"=>"全部","value"=>"all"),
    ),
    "relate_table"=>array(
        //模式=>关联表
        "all" => "*",
    ),
    "relate_sql"=>array(
        //模式=>关联表
        "模式"=>array(
            "标题"=>"sql语句",
        ),
    ),
);
?>