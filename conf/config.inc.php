<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:34
 */
return array(
    'sf_default_app'=>'dictionary',
    'sf_default_act'=>'list',
    'sf_default_fun'=>'index',
    'sf_refresh_pages'=>true,


    'sf_db_connect'=>array(
        0 => array(
            'dbhost'=>'127.0.0.1',
            'dbname'=>'information_schema',
            'dbport'=>3306,
            'dbuser'=>'root',
            'dbpass'=>'',
            'dbmode'=>'mysql',
        ),
    ),
    //调试阶段
    'sf_debug'=> true,
);