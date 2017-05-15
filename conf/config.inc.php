<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:34
 */
return array(
    'SF_DEFAULT_APP'=>'tools',
    'SF_DEFAULT_CON'=>'list',
    'SF_DEFAULT_FUN'=>'index',
//    'SF_REFRESH_PAGES'=>true,  //不读取缓存页面文件


    'SF_DB_CONNECT'=>array(
        1 => array(
//            'DB_HOST'=>'www.jiemo.net',//'218.60.94.27',
            'DB_HOST'=>'218.60.94.27',
            'DB_NAME'=>'jiemo',
            'DB_PORT'=>3306,
            'DB_USER'=>'jiemo',
            'DB_PASS'=>'jiemo@qwer',
            'DB_MODE'=>'mysqli',
        ),
//        0 => array(
//            'DB_HOST'=>'127.0.0.1',
//            'DB_NAME'=>'test',
//            'DB_PORT'=>3306,
//            'DB_USER'=>'root',
//            'DB_PASS'=>'',
//            'DB_MODE'=>'mysqli',
//        ),
        4 => array(
            'DB_HOST'=>'127.0.0.1',
            'DB_NAME'=>'note',
            'DB_PORT'=>3306,
            'DB_USER'=>'root',
            'DB_PASS'=>'',
            'DB_MODE'=>'mysqli',
        ),
    ),
    //调试阶段
    'SF_DEBUG'=> false,
);
