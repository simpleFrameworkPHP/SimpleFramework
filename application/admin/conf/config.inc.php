<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-9-22
 * Time: 下午1:45
 */
return array(
    'SF_DEFAULT_APP'=>'admin',
    'SF_DEFAULT_CON'=>'Index',
    'SF_DEFAULT_FUN'=>'index',
    'SF_THEME_DEFAULT'=>'home',
    'SF_DB_CONNECT'=>array(
        0 => array(
            'DB_HOST'=>'127.0.0.1',
            'DB_NAME'=>'note',
            'DB_PORT'=>3306,
            'DB_USER'=>'root',
            'DB_PASS'=>'',
            'DB_MODE'=>'mysqli',
        )
    ),
    'ADMIN_MENU' => array(
        'home'=>array(
            'class'=>'home','name'=>'首页','url'=>'admin/Index/home',
            'children' => array(

            )
        ),
        'user'=>array(
            'class'=>'user','name'=>'用户','url'=>'admin/User/index',
            'children' => array(
                'Role'=>array('name'=>'角色管理','url'=>'admin/Role/index'),
                'User'=>array('name'=>'用户管理','url'=>'admin/User/index'),
            )
        ),
        'content'=>array(
            'class'=>'content','name'=>'内容','url'=>'admin/Content/index',
            'children' => array(
                'Content'=>array('name'=>'内容管理','url'=>'admin/Content/index'),
                'Special'=>array('name'=>'专题管理','url'=>'admin/Special/index'),
                'Category'=>array('name'=>'板块管理','url'=>'admin/Category/index'),
                'Template'=>array('name'=>'模板管理','url'=>'admin/Template/index'),
                'Attachment'=>array('name'=>'附件管理','url'=>'admin/Attachment/index'),
            )
        ),
        'review'=>array(
            'class'=>'review','name'=>'统计','url'=>'admin/Review/index',
            'children' => array(
                'content'=>array('name'=>'文章统计','url'=>'admin/Review/content'),
            )
        ),
        'set'=>array(
            'class'=>'set','name'=>'设置','url'=>'admin/Set/index',
            'children' => array(
                'Set'=>array('name'=>'属性设置','url'=>'admin/Set/index'),
                'clearCache'=>array('name'=>'清理缓存','url'=>'admin/Set/clearCache'),
            )
        )
    ),
    'EDITOR_CONF' => array(
        'imagePathFormat' => "/data/image/{yyyy}/{mm}/{dd}/{time}{rand:6}"
    ),
    'SF_CACHE_CONF'=>array(
        'FILE'=>array(
            'CACHE_PATH'=>CACHE_PATH.'/data/admin',//文件缓存根目录
            'TIME'=>ONE_DAY*7,//缓存文件过期默认时长
        ),
    ),
);