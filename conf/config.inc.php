<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:34
 */
return array(
    'SF_DEFAULT_APP'=>'tools',
    'SF_DEFAULT_CON'=>'Dictionary',
    'SF_DEFAULT_FUN'=>'index',
//    'SF_REFRESH_PAGES'=>true,  //不读取缓存页面文件


    'SF_DB_CONNECT'=>array(
        0 => array(
            'DB_HOST'=>'10.11.81.11',
            'DB_NAME'=>'api',
            'DB_PORT'=>5001,
            'DB_USER'=>'api',
            'DB_PASS'=>'hxZradh98Z',
            'DB_MODE'=>'mysqli',
        ),
        1 => array(
            'DB_HOST'=>'10.11.81.11',
            'DB_NAME'=>'opsys',
            'DB_PORT'=>5001,
            'DB_USER'=>'opsys',
            'DB_PASS'=>'hxZradh98Z',
            'DB_MODE'=>'mysqli',
        ),
    ),
    //调试阶段
    'SF_DEBUG'=> true,
);
