<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:34
 */
return array(
    'SF_DEFAULT_APP'=>'tools',
    'SF_DEFAULT_CON'=>'List',
    'SF_DEFAULT_FUN'=>'index',
    'SF_REFRESH_PAGES'=>true,


    'SF_DB_CONNECT'=>array(
        0 => array(
            'DB_HOST'=>'127.0.0.1',
            'DB_NAME'=>'test',
            'DB_PORT'=>3306,
            'DB_USER'=>'root',
            'DB_PASS'=>'',
            'DB_MODE'=>'mysqli',
        ),
        1 => array(
            'DB_HOST'=>'127.0.0.1',
            'DB_NAME'=>'zl_blog',
            'DB_PORT'=>3306,
            'DB_USER'=>'root',
            'DB_PASS'=>'',
            'DB_MODE'=>'mysqli',
        ),
    ),
    //调试阶段
    'SF_DEBUG'=> true,
);