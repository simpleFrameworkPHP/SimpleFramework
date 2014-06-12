<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:34
 */
return array(
    'SF_DEFAULT_APP'=>'dictionary',
    'SF_DEFAULT_ACT'=>'list',
    'SF_DEFAULT_FUN'=>'index',
    'SF_REFRESH_PAGES'=>true,


    'SF_DB_CONNECT'=>array(
        0 => array(
            'DBHOST'=>'',
            'DBNAME'=>'information_schema',
            'DBPORT'=>3306,
            'DBUSER'=>'',
            'DBPASS'=>'',
            'DBMODE'=>'mysql',
        ),
        1 => array(
            'DBHOST',
            'DBNAME'=>'cms',
            'DBPORT'=>3306,
            'DBUSER'=>'',
            'DBPASS'=>'',
            'DBMODE'=>'mysql',
        ),
    ),
    //调试阶段
    'SF_DEBUG'=> true,
);