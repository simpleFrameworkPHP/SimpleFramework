<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午2:59
 */
return array(
    "type" => array(
        0 =>array("name"=>"表目录","value"=>"table"),
        1 =>array("name"=>"全部","value"=>"all"),
        2 =>array('name'=>'预告片','value'=>'prevue'),
    ),
    "relate_table"=>array(
        //模式=>关联表
        "all" => array("*"),
    ),
    "relate_sql"=>array(
        //模式=>关联表
        "模式"=>array(
            "标题"=>"sql语句",
        ),
    ),
);
