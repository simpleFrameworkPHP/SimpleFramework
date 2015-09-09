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
//        0 => array(
//            'DB_HOST'=>'172.16.0.231',
//            'DB_NAME'=>'eachbuyer',
//            'DB_PORT'=>3306,
//            'DB_USER'=>'root',
//            'DB_PASS'=>'db01123456',
//            'DB_MODE'=>'mysqli',
//        ),
//        1 => array(
//            'DB_HOST'=>'172.16.0.231',
//            'DB_NAME'=>'eb_pc_site',
//            'DB_PORT'=>3306,
//            'DB_USER'=>'root',
//            'DB_PASS'=>'db01123456',
//            'DB_MODE'=>'mysqli',
//        ),
        0 => array(
            'DB_HOST'=>'172.16.0.231',
            'DB_NAME'=>'eachbuyer_v3',
            'DB_PORT'=>3306,
            'DB_USER'=>'root',
            'DB_PASS'=>'db01123456',
            'DB_MODE'=>'mysqli',
        ),
    ),
    //调试阶段
    'SF_DEBUG'=> true,
);
