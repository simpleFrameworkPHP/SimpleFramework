<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午2:59
 */
return array(
    "type" => array(
        0 =>array("name"=>"表目录","value"=>"table"),
        1 =>array("name"=>"全部","value"=>"all"),
        2 =>array('name'=>'查询','value'=>'sql'),
        3 =>array("name"=>'订单','value'=>'order')
    ),
    "relate_table"=>array(
        //模式=>关联表
        "all" => array("*"),
        "subscribe" => array('eb_customer','eb_subscribe'),
        "product_list" => array('eb_product','eb_category','eb_attribute','eb_attribute_lang','eb_attribute_value','eb_attribute_value_lang','eb_attribute_value_group','eb_attribute_category','eb_attribute_product','eb_product_recommend','eb_category_product'),
        "product" => array('eb_product','eb_category','eb_promote_range','eb_promotion','eb_qna','eb_slogan','eb_product_slogan','eb_note','eb_note_range','eb_product_sku'),
        'discount'=>array('eb_discount','eb_discount_range','eb_product_sku','eb_promote_discount','eb_promote_range'),
        'cart'=>array('eb_cart'),
        'sku'=>array('eb_complexattr_sku','eb_product_sku'),
        'home_product'=>array('eb_widget_product','eb_product'),
        'order'=>array('order_main','order_child')
    ),
    "relate_sql"=>array(
        //模式=>关联表
        "模式"=>array(
            "标题"=>"sql语句",
        ),
        'order'=>array(
            '测试'=>'select * from order_main;'
        )
    ),
);
