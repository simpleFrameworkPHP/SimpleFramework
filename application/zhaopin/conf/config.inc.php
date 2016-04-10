<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-10-9
 * Time: 上午11:06
 */
return array(
    'SF_DEFAULT_APP'=>'zhaopin',
    'SF_DEFAULT_CON'=>'Home',
    'SF_DEFAULT_FUN'=>'index',
    //网站主题
    'SF_THEME_DEFAULT'=>'zhaopin',
    //菜单
    'MENU'=> array(
        'zhaopin/home/index'=>array('title'=>'首页','role'=>0),
        'zhaopin/positionlist/index'=>array('title'=>'职位信息查询','role'=>0),
        'zhaopin/pulllagoudata/index'=>array('title'=>'拉钩数据处理','role'=>1),
        'zhaopin/position/parsing'=>array('title'=>'职位分析','role'=>0),
    )
);