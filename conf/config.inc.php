<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:34
 */
return array(
    'SF_DEFAULT_APP'=>'dictionary',
    'SF_DEFAULT_ACT'=>'List',
    'SF_DEFAULT_FUN'=>'index',
    'SF_REFRESH_PAGES'=>true,


    'SF_DB_CONNECT'=>array(
        0 => array(
            'DB_HOST'=>'127.0.0.1',
            'DB_NAME'=>'information_schema',
            'DB_PORT'=>3306,
            'DB_USER'=>'root',
            'DB_PASS'=>'root',
            'DB_MODE'=>'mysqli',
        ),
        1 => array(
            'DB_HOST'=>'127.0.0.1',
            'DB_NAME'=>'zl_blog',
            'DB_PORT'=>3306,
            'DB_USER'=>'root',
            'DB_PASS'=>'root',
            'DB_MODE'=>'mysqli',
        ),
    ),
    //调试阶段
    'SF_DEBUG'=> true,
);