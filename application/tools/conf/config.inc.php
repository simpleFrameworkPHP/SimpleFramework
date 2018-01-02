<?php
return array(
    "type" => array(
        0 =>array("name"=>"表目录","value"=>"table"),
        1 =>array("name"=>"全部","value"=>"all"),
        2 =>array('name'=>'查询','value'=>'sql'),
        3 =>array("name"=>'院校库','value'=>'school'),
        4 =>array("name"=>'院校详情专业','value'=>'school_zhuanye'),
        5 => array('name'=>'攻击','value'=>'gongji'),
        6 => array('name'=>'京东活动','value'=>'jingdong'),
        7 => array('name'=>'角色系统','value'=>'role'),
        8 => array('name'=>'offer库','value'=>'offer'),
        9 => array('name'=>'活动','value'=>'active'),
        10 => array('name'=>'offer用户资料','value'=>'offeruser'),
        11 => array('name'=>'择校订单','value'=>'schoolorder'),
        12 => array('name'=>'前程日本文案流程','value'=>'qcwriter'),
    ),
    "relate_table"=>array(
        //模式=>关联表
        "all" => array("*"),
        'school'=>array('t_college_school','t_college_professional','t_college_degree','t_college_direction','t_college_school_course','t_college_school_course_exam','t_college_school_name','t_college_school_extend','t_college_school_label','t_college_school_tdk'),
        'school_zhuanye'=>array('t_college_professional','t_college_degree','t_college_direction','t_college_organization'),
        'gongji'=>array('t_school_log','t_college_post_chuanke_record'),
        'jingdong'=>array('pre_train_activity'),
        'role'=>array('cms_role','cms_admin_role','t_online_roletype','pre_auth_extend','pre_auth_group','pre_auth_group_access','pre_auth_power','pre_auth_rule'),
        'offer'=>array('t_online_offer','t_online_offer_file_new','t_online_offer_notes','t_online_offer_school','t_online_offer_school_name','t_online_graduate_school_professional','t_online_graduate_school','cms_member','t_online_crm'),
        'active'=>array('t_activity','t_activity_detail'),
        'offeruser'=>array('t_online_crm','t_online_offer','t_online_offer_school'),
        'schoolorder'=>array('t_online_offer','t_online_offer_school','t_online_crm','t_online_assessment_score','t_online_dict','t_college_dic','cms_member','t_online_graduate_school','t_online_graduate_school_professional','t_online_offer_school_aotu'),
        'qcwriter'=>array('t_online_material_yun', 't_online_material_user_view', 'fib_write_qcrb', 'fib_write_qcrb_school', 'fib_write_qcrb_school_professor', 'fib_write_qcrb_zailiu'),
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
