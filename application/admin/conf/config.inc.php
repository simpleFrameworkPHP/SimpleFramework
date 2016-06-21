<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-9-22
 * Time: 下午1:45
 */
return array(
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
        array('class'=>'home','name'=>'系统','url'=>'admin/Home/index'),
        array('class'=>'user','name'=>'用户','url'=>'admin/User/selectUser'),
        array('class'=>'content','name'=>'内容','url'=>'admin/Content/index')
    ),
    'ADMIN_SUB_MENU' => array(
        array('class'=>'home','name'=>'系统介绍','url'=>'admin/Home/index'),
        array('class'=>'user','name'=>'用户管理','url'=>'admin/User/selectUser'),
        array('class'=>'content','name'=>'内容管理','url'=>'admin/Content/index'),
        array('class'=>'content','name'=>'板块管理','url'=>'admin/Category/index'),
    )
);