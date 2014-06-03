﻿<?php
return array ('remark'=>array (
    "cms_admin" => array ( "0" => "管理员","userid" => "会员ID","username" => "用户名","allowmultilogin" => "是否允许同帐号多人登陆","alloweditpassword" => "是否允许修改密码","editpasswordnextlogin" => "下次登录需要修改密码","disabled" => "是否禁用"),
    "cms_admin_role" => array ( "0" => "管理员权根","userid" => "用户ID","roleid" => "权限ID"),
    "cms_admin_role_priv" => array ( "0" => "管理员权限","roleid" => "权限ID","field" => "类型标识","value" => "值","priv" => "操作动作"),
    "cms_ads" => array ( "0" => "广告","adsid" => "广告ID","adsname" => "广告名称","introduce" => "广告介绍","placeid" => "所属广告位","type" => "广告类型","linkurl" => "广告链接地址","imageurl" => "图片地址","s_imageurl" => "第二张图地址","alt" => "图片提示","flashurl" => "flash地址","wmode" => "是否透明","text" => "文本内容","code" => "代码内容","fromdate" => "广告开始日期","todate" => "广告结束日期","username" => "客户帐号","addtime" => "添加日期","views" => "浏览次数","clicks" => "点击次数","passed" => "是否通过","status" => "广告状态" ),
    "cms_ads_place" => array ("0" => "广告位","placeid" => "广告位ID","placename" => "广告位名称","template" =>"","introduce" => "广告位介绍","price" => "广告价格","items" => "广告个数","width" => "广告位宽度","height" => "广告位高度","passed" => "是否启用","option" => "多广告时选项" ),
    "cms_ads_stat" => array ("0" => "广告统计","id" => "ID","adsid" => "广告ID","username" => "用户名","area" => "地域","ip" => "IP","referer" => "来源地址","clicktime" => "浏览/点击时间","type" => "点击/浏览" ),
    "cms_announce" => array ("0" => "公告","announceid" => "公告ID","title" => "标题","content" => "公告内容","hits" => "点击数","fromdate" => "开始时间","todate" => "结束时间","username" => "用户名","addtime" => "添加时间","passed" => "是否通过","template" => "当前模板" ),
    "cms_area" => array ("0" => "地区","areaid" => "地区ID","name" => "地区名称","style" => "样式","parentid" => "父ID","arrparentid" => "所有父ID","child" => "是否有子地区","arrchildid" => "子地区ID","template" => "当前模板","listorder" => "排序","hits" => "点击" ),
    "cms_ask" => array ("0" => "问吧问题","askid" => "提问ID","catid" => "所属栏目ID","title" => "标题","reward" => "悬赏","userid" => "用户ID","username" => "用户名","addtime" => "添加时间","endtime" => "编辑时间","status" => "状态","flag" => "标识（1、2、3）","answercount" => "回复总数","anonymity" => "匿名","hits" => "点击数","ischeck" => "是否已经检查过期","searchid" => "全站搜索ID" ),
    "cms_ask_actor" => array ("0" => "问吧角色","id" => "问吧角色ID","typeid" => "所属类型","grade" => "等级","actor" => "角色名","min" => "最小值","max" => "最大值" ),
    "cms_ask_credit" => array ("0" => "问吧积分","cid" => "积分ID","userid" => "用户ID","username" => "用户名","premonth" => "上月积分","month" => "本月积分","preweek" => "上周积分","week" => "本周积分","addtime" => "更新时间"),
    "cms_ask_posts" => array ("0" => "问吧回复","pid" => "数据主键","askid" => "提问ID","isask" => "是否标注为提问","message" => "信息内容","addtime" => "添加时间","candidate" => "投票候选","optimal" => "最佳答案","reversion" => "回复(主题是否有回复)","userid" => "用户ID","status" => "状态","anonymity" => "匿名","username" => "用户名","solvetime" => "解决时间","votecount" => "投票数"),
    "cms_ask_vote" => array ("0" => "问吧投票统计","voteid" => "投票ID","askid" => "提问ID","pid" => "回复ID","userid" => "用户ID","addtime" => "投票时间"),
    "cms_attachment" => array ("0" => "附件","aid" => "附件ID","module" => "所属模块","catid" => "栏目ID","contentid" => "内容ID","field" => "字段名","filename" => "文件名称","filepath" => "文件路径","filetype" => "文件类型","filesize" => "文件大小","fileext" => "文件后缀","description" => "文件描述","isimage" => "是否图片","isthumb" => "是否缩略图","downloads" => "下载次数","listorder" => "排序","userid" => "用户ID","uploadtime" => "上传时间","uploadip" => "IP记录"),
    "cms_author" => array ("0" => "作者","authorid" => "作者ID","username" => "添加用户名","name" => "作者名称","gender" => "性别","birthday" => "生日","email" => "Email","qq" => "QQ","msn" => "MSN","homepage" => "主页","telephone" => "电话","address" => "地址","postcode" => "邮编","photo" => "照片","introduce" => "介绍","updatetime" => "更新时间","listorder" => "排序","elite" => "推荐","disabled" => "是否禁用"),
    "cms_block" => array ("0" => "碎片","blockid" => "碎片ID","pageid" => "页面标识","blockno" => "碎片序号","name" => "碎片名称","isarray" => "格式化","rows" => "行数","data" => "碎片数据","listorder" =>"","disabled" => ""),
    "cms_c_down" => array ("0" => "下载模型","contentid" => "ID","template" =>"","content" => "描述","version" => "版本号","classtype" => "软件类型","language" => "软件语言","copytype" => "软件授权形式","systems" => "软件平台","stars" => "评分等级","filesize" => "文件大小","downurl" => "下载地址","downurls" => "下载列表","groupids_view" => "下载权限","readpoint" => "阅读所需点数"),
    "cms_c_info" => array ("0" => "信息模型","contentid" => "ID","template" => "内容页模板","content" => "描述","endtime" => "截止日期","telephone" => "联系电话","email" => "E-mail","address" => "地址","groupids_view" => "阅读权限","readpoint" => "阅读所需点数"),
    "cms_c_ku6video" => array ("0" =>"","contentid" =>"","template" => "内容页模板","content" => "描述","photo" =>"","ku6video" =>"","readpoint" => "阅读所需点数","groupids_view" => "阅读权限","director" =>"","actor" => ""),
    "cms_c_news" => array ("0" => "文章模型","contentid" => "ID","template" => "内容页模板","titleintact" => "完整标题","content" => "内容","groupids_view" => "阅读权限","readpoint" => "阅读所需点数","author" => "作者","copyfrom" => "来源","paginationtype" =>"","maxcharperpage" => ""),
    "cms_c_picture" => array ("0" => "图片模型","contentid" => "ID","template" => "内容页模板","content" => "描述","pictureurls" => "组图","author" => "作者","copyfrom" => "来源"),
    "cms_c_product" => array ("0" => "产品模型","contentid" => "ID","template" => "内容页模板","content" => "产品介绍","price" => "价格","size" => "产品型号","pictureurls" => "组图","unit" => "产品单位","stock" => "库存","stars" => "推荐等级"),
    "cms_c_video" => array ("0" =>"","contentid" => "ID","template" => "内容页模板","content" => "影片简介","video" => "视频","director" => "导演","actor" => "主演","rank" => "影片评分","photo" => "剧照"),
    "cms_c_vv" => array ("0" =>"","contentid" =>"","template" => "内容页模板","content" => "影片简介"),
    "cms_cache_count" => array ("0" => "缓存统计","id" => "统计ID标识","count" => "统计结果","updatetime" => "最后更新时间"),
    "cms_category" => array ("0" => "栏目","catid" => "栏目ID","module" => "模块","type" => "栏目类型","modelid" => "模型ID","parentid" => "上级栏目ID","arrparentid" => "所有上级栏目ID","child" => "是否有子栏目","arrchildid" => "所有子栏目ID","catname" => "栏目名称","style" => "样式","image" => "栏目图片","description" => "描述","parentdir" => "父目录","catdir" => "栏目目录","url" => "链接","content" => "单网页内容","items" => "信息数","hits" => "点击数","setting" => "设置","listorder" => "排序","ismenu" => "在导航显示","letter" =>"","citems" =>"","pitems" => ""),
    "cms_collect" => array ("0" =>"","id" =>"","contentid" =>"","userid" =>"","addtime" => ""),
    "cms_comment" => array ("0" => "评论","commentid" => "评论ID","keyid" => "评论KEY","userid" => "用户ID","username" => "用户名","score" => "其他","support" => "支持数","against" => "反对数","content" => "评论内容","ip" => "评论IP","addtime" => "评论时间","status" => "审核状态"),
    "cms_content" => array ("0" => "内容模型","contentid" => "ID","catid" => "栏目ID","typeid" => "分类ID","areaid" => "地区ID","title" => "标题","style" => "标题样式","thumb" => "缩略图","keywords" => "关键词","description" => "摘要","posids" => "推荐位","url" => "链接地址","listorder" => "排序","status" => "状态","userid" => "用户ID","username" => "用户名","inputtime" => "发布时间","updatetime" => "更新时间","searchid" => "搜索ID","islink" => "外部链接","prefix" => ""),
    "cms_content_count" => array ("0" => "内容模型统计","contentid" => "信息ID","hits" => "浏览次数","hits_day" => "一天点击数","hits_week" => "一个星期点击数","hits_month" => "一个月点击数","hits_time" => "点击时间","comments" => "评论数","comments_checked" => "已审核评论数")
,"cms_content_position" => array ("0" => "内容模型推荐位","contentid" => "信息ID","posid" => "推荐位ID"),
    "cms_content_tag" => array ("0" => "内容模型TAG","tag" => "标签","contentid" => "信息ID"),
    "cms_copyfrom" => array ("0" => "来源","copyfromid" => "来源ID","name" => "来源名称","url" => "来源链接","usetimes" => "使用次数","listorder" => "排序","updatetime" => "上次使用时间"),
    "cms_datasource" => array ("0" => "数据源","name" => "数据源名称","dbtype" => "数据库类型","dbhost" => "数据库主机","dbuser" => "数据库帐号","dbpw" => "数据库密码","dbname" => "数据库名称","dbcharset" => "数据库字符集","tablename" => "表名","fields" => "字段名称","status" => "状态"),
    "cms_digg" => array ("0" => "顶一下","contentid" => "文章ID","supports" => "支持数","againsts" => "反对数","supports_day" => "一天支持数","againsts_day" => "一天反对数","supports_week" => "一周支持数","againsts_week" => "一周反对数","supports_month" => "一个月支持数","againsts_month" => "一个月反对数","updatetime" => "更新时间"),
    "cms_digg_log" => array ("0" => "顶一下记录","contentid" => "文章ID","flag" => "标记","userid" => "用户ID","username" => "用户名","ip" => "IP","time" => "时间"),
    "cms_editor_data" => array ("0" => "编辑器数据恢复保存","id" => "ID","userid" => "用户ID","editorid" => "编辑器ID","ip" => "IP","created_time" => "保存时间","data" => "保存数据"),
    "cms_error_report" => array ("0" => "错误报告","error_id" => "ID","userid" => "报告用户ID","username" => "报告用户名","error_title" => "标题","error_text" => "内容","error_link" => "连接","typeid" => "类型","addtime" => "添加时间","status" => "审核时间","contentid" => ""),
    "cms_form_34" => array ("0" =>"","dataid" =>"","userid" => "报告用户ID","datetime" =>"","ip" => ""),
    "cms_formguide" => array ("0" => "表单向导","formid" => "表单ID","name" => "表单名称","tablename" => "表名","introduce" => "介绍","setting" => "表单设置","addtime" => "表单添加时间","template" => "表单模板","disabled" => "表单状态"),
    "cms_formguide_fields" => array ("0" => "表单向导字段","fieldid" => "字段ID","formid" => "表单ID","field" => "字段名称","name" => "字段别名","tips" => "字段提示","css" => "css样式","minlength" => "输入最小字符长度","maxlength" => "输入最大字符长度","pattern" => "正则匹配","errortips" => "错误提示","formtype" => "表单类型","setting" => "相关设置","formattribute" => "表现形式","unsetgroupids" => "不能设置的用户组","issystem" => "是否必填","isbackground" => "是否在后台列表显示","isunique" => "是否唯一","issearch" => "是否","isselect" =>"","islist" =>"","isshow" =>"","listorder" =>"","disabled" => ""),
    "cms_guestbook" => array ("0" => "友情链接","gid" => "留言ID","title" => "标题","content" => "内容","reply" => "回复","userid" => "用户ID","username" => "用户名","gender" => "性别","head" => "头像","email" => "E-mail","qq" => "QQ","homepage" => "主页","hidden" => "隐藏","passed" => "批准","ip" => "IP","addtime" => "发布时间","replyer" => "回复人","replytime" => "回复时间"),
    "cms_hits" => array ("0" => "浏览次数统计","field" => "字段","value" => "值","date" => "日期","hits" => "浏览次数"),
    "cms_ipbanned" => array ("0" => "IP禁止","ip" => "被禁IP","expires" => "过期时间"),
    "cms_keylink" => array ("0" => "关联链接","keylinkid" => "ID","word" => "关联词","url" => "关联链接","listorder" => "排序"),
    "cms_keyword" => array ("0" => "关键字","tagid" => "TAG","tag" => "Tag名称","style" =>"","usetimes" => "使用字数","lastusetime" => "最后使用时间","hits" => "点击数","lasthittime" => "最后点击时间","listorder" => "排序"),
    "cms_link" => array ("0" => "友情链接","linkid" => "友情链接ID","typeid" => "分类ID","linktype" => "链接类型","style" => "样式","name" => "站点名","url" => "链接地址","logo" => "Logo","introduce" => "介绍","username" => "用户名","listorder" => "排序","elite" => "推荐","passed" => "通过","addtime" => "加入时间","hits" => "点击数"),
    "cms_log" => array ("0" => "操作日志","logid" => "日志ID","field" => "字段","value" => "值","module" => "模块","file" => "文件","action" => "操作","querystring" => "查询字串","data" => "数据","userid" => "用户ID","username" => "用户名","ip" => "IP","time" => "操作时间"),
    "cms_mail" => array ("0" => "邮件订阅","mailid" => "邮件内容ID","typeid" => "类型ID","title" => "邮件内容标题","content" => "邮件内容","addtime" => "添加时间","sendtime" => "发送时间","username" => "用户名","userid" => "用户ID","period" => "期刊号","count" => "发送次数"),
    "cms_mail_email" => array ("0" => "邮件地址","email" => "订阅邮箱","userid" => "用户ID","username" => "用户名","ip" => "订阅IP","addtime" => "订阅时间","status" => "邮件状态","authcode" => "其他"),
    "cms_mail_email_type" => array ("0" => "邮件类型","email" => "订阅邮箱","typeid" => "订阅类型ID"),
    "cms_member" => array ("0" => "会员","userid" => "用户ID","username" => "用户名","password" => "用户密码","touserid" =>"","email" => "用户邮件","groupid" => "用户组ID","areaid" => "用户所在地ID","amount" => "用户金钱","point" => "用户点数","modelid" => "用户类型ID","message" => "用户是否有新短消息","disabled" => "状态"),
    "cms_member_cache" => array ("0" => "会员缓存","userid" => "用户ID","username" => "用户名","password" => "用户密码","touserid" =>"","email" => "用户邮件","groupid" => "用户组ID","areaid" => "用户所在地ID","amount" => "用户金钱","point" => "用户点数","modelid" => "用户类型ID","message" => "用户是否有新短消息","disabled" => "状态"),
    "cms_member_company" => array ("0" =>"","userid" => "用户ID","sitedomain" =>"","tplname" =>"","products" => "主营产品或服务","companyname" => "企业名称","catids" => "企业库","genre" => "公司类型","areaname" => "公司所在地","establishtime" => "公司成立日期","linkman" =>"","telephone" =>"","email" =>"","address" => "经营地址","grade" => "会员等级","endtime" => "服务期截止时间","status" => "公司审核状态：0-未审核","diy" => "自定义风格：1为自定义，0","map" => "地理位置参数","menu" => "企业主页使用的菜单","introduce" => "公司介绍","logo" => "企业LOGO","banner" => "公司Banner","pattern" => "经营模式","employnum" => "员工人数","turnover" => "年营业额","elite" => ""),
    "cms_member_detail" => array ("0" => "会员详细信息","userid" => "用户ID","truename" => "姓名","gender" => "性别","msn" => "MSN","telephone" => "电话","address" => "地址","qq" => "QQ","birthday" => "生日","postcode" => "邮编","mobile" => "手机"),
    "cms_member_group" => array ("0" => "会员组","groupid" => "用户组ID","name" => "名称","issystem" => "是否为系统组","allowmessage" => "允许发送消息数","allowvisit" => "是否允许访问","allowpost" => "是否允许发布","allowsearch" => "是否允许搜索","allowupgrade" =>"","price_y" => "包年价格","price_m" => "包月价格","price_d" => "包年价格","description" => "简介","listorder" => "排序","disabled" => "禁用"),
    "cms_member_group_extend" => array ("0" => "扩展会员组","userid" => "用户ID","groupid" => "用户组ID","unit" => "购买类型","price" => "价格","number" => "个数","amount" => "总价","startdate" => "开始时间","enddate" => "结束时间","ip" => "IP","time" => "购买时间","disabled" => "状态"),
    "cms_member_group_priv" => array ("0" => "会员权限","groupid" => "会员组ID","field" => "字段","value" => "值","priv" => "执行动作"),
    "cms_member_info" => array ("0" => "会员基本信息","userid" =>"","question" =>"","answer" =>"","avatar" =>"","regip" =>"","regtime" =>"","lastloginip" =>"","lastlogintime" =>"","logintimes" =>"","note" =>"","actortype" =>"","answercount" =>"","acceptcount" => ""),
    "cms_menu" => array ("0" => "菜单","menuid" => "菜单ID","parentid" => "上级菜单ID","name" => "菜单名称","image" => "菜单图片","url" => "菜单链接","description" => "描述","target" => "打开窗口","style" => "菜单样式","js" => "JS","groupids" => "有权限的用户组ID","roleids" => "有权限的角色ID","isfolder" => "是否为目录","isopen" => "默认展开","listorder" => "排序","userid" => "用户ID","keyid" => "Keyid"),
    "cms_message" => array ("0" => "短消息","messageid" => "短信ID","send_from_id" => "发信人用户ID","send_to_id" => "收信人用户ID","folder" => "信箱","status" => "短消息状态","message_time" => "短消息时间","subject" => "短消息题目","content" => "短消息正文","replyid" => "回复ID"),
    "cms_model" => array ("0" => "模型","modelid" => "模型ID","name" => "模型名称","description" => "描述","tablename" => "保存表名","itemname" => "项目名称","itemunit" => "项目单位","workflowid" => "工作流方案","template_category" => "栏目首页模板","template_list" => "栏目列表页模板","template_show" => "内容页模板","template_print" => "打印页模板","ishtml" => "是否生成html","category_urlruleid" => "栏目URL规则","show_urlruleid" => "内容页URL规则","enablesearch" =>"","ischeck" =>"","isrelated" =>"","disabled" => "禁用","modeltype" => "类型"),
    "cms_model_field" => array ("0" => "模型字段","fieldid" => "字段ID","modelid" => "模型ID","field" => "字段名","name" => "字段别名","tips" => "提示信息","css" => "CSS","minlength" => "最小长度","maxlength" => "最大长度","pattern" => "正则表达式","errortips" => "错误提示","formtype" => "表单类型","setting" => "表单设置","formattribute" => "附加事件","unsetgroupids" => "禁止设置的会员组","unsetroleids" => "禁止设置的角色","iscore" => "核心字段","issystem" => "系统字段","isunique" => "是否值唯一","isbase" =>"","issearch" => "是否为搜索条件","isselect" => "是否默认读取","iswhere" => "是否为调用条件","isorder" => "是否为排序字段","islist" => "在列表页显示","isshow" => "在内容页显示","isadd" => "前台允许设置","isfulltext" =>"","listorder" => "排序","disabled" => "禁用"),
    "cms_module" => array ("0" => "模块","module" => "模块英文名称","name" => "模块中文名","path" => "安装路径","url" => "链接URL","iscore" => "是否系统模块","version" => "版本号","author" => "作者","site" => "版权网站地址","email" => "Email","description" => "描述","license" => "授权说明","faq" => "帮助","tagtypes" => "标签类型","setting" => "模块配置","listorder" => "排序","disabled" => "是否禁用","publishdate" => "发布时间","installdate" => "安装时间","updatedate" => "更新时间"),
    "cms_mood" => array ("0" => "心情指数","moodid" => "心情方案ID","name" => "方案名称","number" => "总数","m1" => "名称1","m2" => "名称2","m3" => "名称3","m4" => "名称4","m5" => "名称5","m6" => "名称6","m7" => "名称7","m8" => "名称8","m9" => "名称9","m10" => "名称10","m11" => "名称11","m12" => "名称12","m13" => "名称13","m14" => "名称14","m15" => "名称15"),
    "cms_mood_data" => array ("0" => "心情指数统计","moodid" => "心情方案ID","contentid" => "内容id","total" => "投票总数","n1" => "投票总数1","n2" => "投票总数2","n3" => "投票总数3","n4" => "投票总数4","n5" => "投票总数5","n6" => "投票总数6","n7" => "投票总数7","n8" => "投票总数8","n9" => "投票总数9","n10" => "投票总数10","n11" => "投票总数11","n12" => "投票总数12","n13" => "投票总数13","n14" => "投票总数14","n15" => "投票总数15","updatetime" => ""),
    "cms_order" => array ("0" => "订单记录","orderid" => "ID","goodsid" => "商品ID","goodsname" => "商品名称","goodsurl" => "商品URL","unit" => "商品单位","price" => "商品单价","number" => "商品数量","carriage" => "运费","amount" => "付款金额","consignee" => "收货人姓名","areaid" => "地区ID","telephone" => "固定电话","mobile" => "移动电话","address" => "收货地址","postcode" => "邮政编码","note" => "买家留言","memo" => "买家备注","userid" => "用户ID","username" => "用户名","ip" => "IP","time" => "下单时间","date" => "下单日期","status" => "订单状态"),
    "cms_order_deliver" => array ("0" => "配送地址","deliverid" => "ID","userid" => "用户ID","username" => "用户名","consignee" => "收货人姓名","areaid" => "地区ID","address" => "配送地址","postcode" => "邮编","telephone" => "固定电话","mobile" => "移动电话"),
    "cms_order_log" => array ("0" => "订单状态","logid" => "ID","orderid" => "订单ID","laststatus" => "上次订单状态","status" => "订单状态","note" => "备注","userid" => "操作者ID","username" => "操作者用户名","ip" => "IP","time" => "时间"),
    "cms_pay_card" => array ("0" => "支付卡","id" => "ID","userid" => "使用者ID","username" => "使用者用户名","ptypeid" => "点卡类型","cardid" => "卡号","inputerid" => "生成点卡用户ID","inputer" => "生成点卡用户名","mtime" => "生成时间","regtime" => "使用时间","endtime" =>"","regip" => "生成点卡地址","point" => "点数","amount" => "金钱","status" => "使用状态"),
    "cms_pay_exchange" => array ("0" => "交易记录","id" => "id","module" => "消费模块","type" => "消费类型","number" => "消费数量","blance" => "余数","userid" => "消费者ID","username" => "消费者用户名","inputid" => "添加用户ID","inputer" => "添加用户名","ip" => "消费IP","time" => "消费时间","note" => "备注","authid" => "其他"),
    "cms_pay_payment" => array ("0" => "支付类型","pay_id" => "ID","pay_code" => "支付英文名","pay_name" => "支付名称","pay_desc" => "支付类型描述","pay_fee" => "手续费","config" => "支付配置","is_cod" => "其他","is_online" => "其他","pay_order" => "排序","enabled" => "状态","author" => "作者","website" => "网址","version" => "版本号"),
    "cms_pay_pointcard_type" => array ("0" => "点卡类型","ptypeid" => "ID","name" => "类型名称","point" => "点数","amount" => "金额"),
    "cms_pay_stat" => array ("0" => "财务统计","date" => "时间","type" => "交易类型","receipts" => "入款(点)数","advances" => "扣款(点)数"),
    "cms_pay_user_account" => array ("0" => "汇款记录","id" => "ID","userid" => "汇款用户ID","username" => "汇款用户名","email" => "E-mail","contactname" => "汇款人","telephone" => "电话","sn" => "汇款号","inputer" => "审核用户名","inputerid" => "审核用户ID","amount" => "汇款数量","quantity" =>"","addtime" => "汇款时间","paytime" => "审核时间","adminnote" => "审核人员留言","usernote" => "汇款用户留言","type" => "汇款类型","payment" => "汇款类型名称","ip" => "汇款IP","ispay" => "汇款状态"),
    "cms_player" => array ("0" =>"","playerid" =>"","subject" =>"","code" =>"","disabled" => ""),
    "cms_position" => array ("0" => "推荐位","posid" => "推荐位ID","name" => "推荐位名称","listorder" => "排序"),
    "cms_process" => array ("0" => "工作流方案","processid" => "审核流程ID","workflowid" => "工作流方案ID","name" => "流程名称","description" => "描述","passname" => "通过名称","passstatus" => "通过状态值","rejectname" => "退稿名称","rejectstatus" => "退稿状态值"),
    "cms_process_status" => array ("0" => "工作流ID状态","processid" => "流程ID","status" => "状态值"),
    "cms_role" => array ("0" => "角色","roleid" => "角色ID","name" => "角色名称","description" => "描述","ipaccess" => "允许访问的IP范围","listorder" => "排序","disabled" => "禁用"),
    "cms_search" => array ("0" => "全站搜索","searchid" => "搜索ID","type" => "分类","data" => "全文索引内容"),
    "cms_search_type" => array ("0" => "全站搜索类型","type" => "分类","name" => "分类名称","md5key" => "密钥","description" => "描述","listorder" => "排序","disabled" => "禁用"),
    "cms_session" => array ("0" => "Session","sessionid" => "Session","userid" => "用户ID","ip" => "IP","lastvisit" => "最后访问时间","groupid" => "会员组ID","module" => "模块名","catid" => "栏目ID","contentid" => "内容ID","data" => "Session数据"),
    "cms_space" => array ("0" => "个人空间","userid" => "用户ID","spacename" => "空间名","blockid" => "展示的API信息"),
    "cms_space_api" => array ("0" => "个人空间接口","apiid" => "API的ID","module" => "所属模型","name" => "名称","url" => "链接地址","template" => "模板","listorder" => "排序","disable" => "是否禁止"),
    "cms_special" => array ("0" => "专题","specialid" => "专题ID","typeid" => "类型ID","title" => "专题标题","style" => "样式","thumb" => "缩略图","banner" => "横幅图片","filename" => "专题英文名","description" => "描述","url" => "链接地址","template" => "当前模板","userid" => "用户ID","username" => "用户名","createtime" => "生成时间","listorder" => "排序","elite" => "推荐","disabled" => "是否禁用"),
    "cms_special_content" => array ("0" => "专题文章","specialid" => "专题ID","contentid" => "文章ID"),
    "cms_spider_job" => array ("0" =>"","JobId" =>"","SiteId" =>"","CatId" =>"","JobName" =>"","JobDescription" =>"","StartUrl" =>"","SpiderStep" =>"","UseSpecialLink" =>"","ScriptLink" =>"","TrueLink" =>"","ListPageMust" =>"","ListPageForbid" =>"","ContentPageMust" =>"","ContentPageForbid" =>"","ListUrlStart" =>"","ListUrlEnd" =>"","SpiderRule" =>"","Cookie" =>"","DividePageStyle" =>"","DividePageStart" =>"","DividePageEnd" =>"","DividePageUnion" =>"","AutoPageSize" =>"","SourceEncode" =>"","SiteEncode" =>"","LabelCycle" =>"","TestPageUrl" =>"","DownImg" =>"","DownSwf" =>"","DownOther" =>"","OtherFileType" =>"","ThreadNum" =>"","ThreadRequest" =>"","ThreadSleep" =>"","TimeOut" =>"","CreateOn" =>"","UpdateOn" => ""),
    "cms_spider_sites" => array ("0" =>"","Id" =>"","SiteName" =>"","SiteUrl" =>"","Description" => ""),
    "cms_spider_urls" => array ("0" =>"","Id" =>"","JobId" =>"","Title" =>"","Thumb" =>"","PageUrl" =>"","BaseUrl" =>"","CreateOn" =>"","SpiderOn" =>"","Spidered" =>"","Content" =>"","StartTimeStamp" =>"","IsOut" => ""),
    "cms_status" => array ("0" => "稿件状态","status" => "状态值","name" => "状态名称","description" => "描述","issystem" => "系统内置状态"),
    "cms_times" => array ("0" => "操作限制","action" => "操作","ip" => "IP","time" => "最后更新时间","times" => "次数"),
    "cms_type" => array ("0" => "分类","typeid" => "分类ID","module" => "模块","modelid" =>"","name" => "名称","style" => "样式","typedir" => "分类目录","description" => "描述","thumb" => "分类图片","url" => "链接地址","template" => "模板","listorder" => "排序"),
    "cms_urlrule" => array ("0" => "URL规则","urlruleid" => "URL权限ID","module" => "模块名称","file" => "文件名","ishtml" => "是否静态","urlrule" => "URL规则","example" => "样例"),
    "cms_vote_data" => array ("0" => "投票数据","userid" => "用户ID","username" => "用户名","subjectid" => "主题ID","time" => "投票时间","ip" => "IP记录","data" => "投票数据","userinfo" => "用户信息"),
    "cms_vote_option" => array ("0" => "投票选项","optionid" => "选项ID","subjectid" => "主题ID","option" => "选项","image" => "图片","listorder" => "排序"),
    "cms_vote_subject" => array ("0" => "投票主题","subjectid" => "主题ID","subject" => "主题","ismultiple" => "是否多选选择","ischeckbox" => "是否多选","credit" => "积分","addtime" => "添加时间","fromdate" => "开始时间","todate" => "结束时间","interval" => "投票时间间隔","enabled" => "是否启用","template" => "当前模板","parentid" => "父ID","description" => "描述","userinfo" => "用户资料","listorder" => "排序","enablecheckcode" => "验证码开启？","allowguest" => "允许游客投票","groupidsvote" => "允许参与投票的会员组","groupidsview" => "允许查看结果的会员组","maxval" => "最大值","minval" => "最小值","userid" => "用户ID","allowview" => "允许查看","optionnumber" => "选项数","votenumber" => "投票数"),
    "cms_vote_useroption" => array ("0" => "投票用户选项","optionid" => "选项ID","subjectid" => "主题ID","userid" => "用户ID","username" => "用户名"),
    "cms_workflow" => array ("0" => "审核方案","workflowid" => "工作流方案ID","name" => "方案名称","description" => "方案描述"),
    "cms_yp_apply" => array ("0" =>"","applyid" =>"","truename" => "真实姓名","gender" => "性别：0","birthday" => "生日","idcard" => "身份证号码","province" => "户籍所在省份","city" => "户籍所在城市","placeprovince" => "目前居住地省份","placecity" => "目前居中地城市","edulevel" => "教育水平","userface" => "照片地址","station" => "欲从事岗位","experience" => "工作年限","worktype" =>"","pay" => "薪资要求","introduce" => "个人鉴定","story" => "工作经历","status" => "状态:3","elite" => "推荐：1推荐，0普通","linkurl" =>"","userid" => "用户ID","hits" =>"","listorder" =>"","addtime" =>"","edittime" =>"","graduatetime" => "毕业时间","school" => "毕业学校","specialty" => "所学专业","area" => "期待地区","period" => "上岗时间","mobile" => "联系电话","telephone" => "家庭电话","email" => "E-Mail","qq" => "QQ","address" => "通讯地址","zip" => "邮编","homepage" => "个人主页"),
    "cms_yp_buy" => array ("0" =>"","id" => "ID","title" => "产品名称","standard" => "产品型号","listorder" => "排序","style" => "颜色和字型","keywords" => "关键字","thumb" => "缩略图","content" => "详细说明","userid" => "发布人","price" => "产品价格","catid" => "所属分类","inputtime" => "添加时间","updatetime" => "更新时间","tid" => "商机类型","period" => "信息有效期","quantifier" => "计量单位","elite" => "推荐","status" => "状态"),
    "cms_yp_cert" => array ("0" =>"","id" => "主键","userid" => "所属用户ID","name" => "证书名称","organization" => "发证机构","thumb" => "证书图片","status" => "证书状态：0","addtime" => "添加时间","effecttime" => "生效日期","endtime" => "截至日期"),
    "cms_yp_collect" => array ("0" =>"","cid" => "主键","userid" => "被收藏商家ID","vid" => "收藏者ID","title" => "收藏标题","url" => "链接地址","addtime" => "收藏时间"),
    "cms_yp_count" => array ("0" =>"","id" =>"","model" => "模型表名：长度为7","hits" =>"","hits_day" =>"","hits_week" =>"","hits_month" =>"","hits_time" => ""),
    "cms_yp_guestbook" => array ("0" =>"","gid" =>"","userid" =>"","id" => "内容ID，与lable共用","vid" => "留言会员ID，非会员则为0","username" => "提交人姓名，可为匿名和会员名","fax" =>"","telephone" =>"","qq" =>"","unit" => "所在单位","msn" =>"","email" =>"","style" => "标记样式","forwardpage" => "询价页面URL","content" =>"","status" => "0","label" => "所属模型","addtime" => ""),
    "cms_yp_job" => array ("0" =>"","id" =>"","listorder" => "排序","style" => "颜色和字型","content" => "职位描述","userid" => "发布人","inputtime" => "添加时间","updatetime" => "更新时间","station" => "岗位","title" => "职位名称","employ" => "招聘人数","experience" => "工作经验","worktype" => "工作性质","sex" => "性别要求","degree" => "学历要求","pay" => "月薪","period" => "有效期","elite" => "推荐","workplace" => "工作地点","status" => "状态"),
    "cms_yp_news" => array ("0" =>"","id" => "ID","userid" => "发布人","title" => "标题","keywords" => "关键字","content" => "内容","style" => "颜色和字型","thumb" => "缩略图","listorder" => "排序","inputtime" => "添加时间","updatetime" => "更新时间","status" => "状态","elite" => "推荐"),
    "cms_yp_product" => array ("0" =>"","id" => "ID","title" => "产品名称","standard" => "产品型号","listorder" => "排序","style" => "颜色和字型","keywords" => "关键字","thumb" => "缩略图","content" => "详细说明","userid" => "发布人","price" => "产品价格","catid" => "所属分类","inputtime" => "添加时间","updatetime" => "更新时间","searchid" =>"","status" => "状态","elite" => "推荐","quantifier" => "计量单位"),
    "cms_yp_relation" => array ("0" =>"","userid" =>"","catid" => ""),
    "cms_yp_stats" => array ("0" =>"","sid" => "统计主键","userid" => "被访问商家ID","vid" => "访问者ID","addtime" => "初访时间","updatetime" => "最后访问时间","hits" => "访问者总访问数","ip" => "访问IP"),
    "cms_yp_stock" => array ("0" =>"","stockid" =>"","jobid" =>"","applyid" =>"","label" =>"","status" => "0未查看1已查看2邀请面试","addtime" => ""),
    "cms_c_film" => array("0" =>"电影表"),
    "cms_rank" =>array("0" =>"排行榜总表"),
    "cms_rank_film"=>array("0"=>"排行详情表","cms_id"=>"电影id(对应电影表的contentid)","title"=>"片名","rankid"=>"排行榜总表rankid"),
),
);
?>