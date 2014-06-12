<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:25
 */
return array(
    //默认参数设置
    'sf_default_app'=>'home',
    'sf_default_act'=>'index',
    'sf_default_fun'=>'index',

    //缓存页面实时更新(建议关闭)
    'sf_refresh_pages'=> false,

    //调试阶段
    'sf_debug'=> false,

    //默认数据库链接
    'sf_db_connect' => array(
        0 => array(
            'dbhost'=>'127.0.0.1',
            'dbport'=>3306,
            'dbuser'=>'root',
            'dbpass'=>'',
            'dbname'=>'',
            'dbmode'=>''
        ),
    ),
    //数据库字符集
    'sf_db_charset'=>'utf8',
    //表前缀
    'sf_db_prefix'=>'sf_',
    //多数据库处理
    'sf_db_multi' => false,

    //缓存设置
    'sf_cache_mode'=>'FILE',
    'sf_cache_conf'=>array(
        'FILE'=>array(
            'cache_path'=>CACHE_PATH.'/data',//文件缓存根目录
            'time'=>86400*7,//缓存文件过期默认时长
        ),
    ),
);