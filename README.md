SimpleFramework
===============

<b>2014.5.29</b><br/>
1.文件架构初成，活用include方法引入和自动加载必要文件；<br/>
<br/>
<b>2014.5.30</b><br/>
1. 添加了常量定义文件；<br/>
2. 添加了模板缓存的流程（简单编译式模板引擎过程实现，后期单独提出来）；<br/>
3. 添加了自动创建目录的方法；<br/>
4. 添加了模板引擎的{$abc}和{:abc()}这种格式的渲染，还不是很健全；<br/>
<br/>
<b>2014.6.3</b><br/>
1. 修改config文件的加载方式；<br/>
2. 模板渲染的正则表达式修正，使其达到应有的效果；<br/>
3. 添加错误处理流程；<br/>
4. 规范了一下系统参数名称，添加调试模式系统参数；<br/>
<br/>
<b>2014.6.4</b><br/>
1. 修正了配置参数获取方式；<br/>
2. 将常量文件放入common文件夹内；<br/>
<br/>
<b>2014.6.5</b><br/>
1. 添加mysql的连接；<br/>
2. 数据库查询流程跑通；<br/>
3. 获取数据中的key值；<br/>
<br/>
<b>2014.6.6</b><br/>
1. 添加sql语句select和from部分替换和判断的流程；<br/>
2. 整理index页面；<br/>
3. 该query方法名为select方法，正式列入外部使用；<br/>
<b>2014.6.10</b><br/>
1. 将sql语句渲染的select和from部分跑通；<br/>
2. 更改常量__ROOT__为__PATH__是根目录字符串，保留__ROOT__为url基础值（不包含http://）；<br/>
<p style='color:red;'>3. 添加缓存接口，待实现；<br/></p>
<p style='color:red;'>4. （待修正问题）任务1的流程完善工作以及table关系的存储和缓存机制的建立；<br/></p>
