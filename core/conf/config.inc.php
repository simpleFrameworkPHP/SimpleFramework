<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:25
 */
return array(
    //默认参数设置
    'SF_DEFAULT_APP'=>'home',
    'SF_DEFAULT_ACT'=>'index',
    'SF_DEFAULT_FUN'=>'index',

    //缓存页面实时更新(建议关闭)
    'SF_REFRESH_PAGES'=> false,

    //调试阶段
    'SF_DEBUG'=> false,

    //默认数据库链接
    'SF_DB_CONNECT' => array(
        0 => array(
            'DB_HOST'=>'127.0.0.1',
            'DB_PORT'=>3306,
            'DB_USER'=>'root',
            'DB_PASS'=>'',
            'DB_NAME'=>'',
            'DB_MODE'=>''
        ),
    ),
    //数据库字符集
    'SF_DB_CHARSET'=>'utf8',
    //表前缀
    'SF_DB_PREFIX'=>'sf_',
    //预留--目前还没有判断多数据库处理
    'SF_DB_MULTI' => false,

    //缓存设置
    'SF_CACHE_MODE'=>'FILE',
    'SF_CACHE_CONF'=>array(
        'FILE'=>array(
            'CACHE_PATH'=>CACHE_PATH.'/data',//文件缓存根目录
            'TIME'=>ONE_DAY*7,//缓存文件过期默认时长
        ),
    ),

    //网站主题
    'SF_THEME_DEFAULT'=>'default',

    //时区设置
    'SF_TIME_ZONE' => 'Asia/Shanghai',
);