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
3. 添加缓存接口，待实现；<br/>
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
<b>2014.6.11</b><br/>
1. 剔除之前的sql渲染方法；<br/>
2. 添加TableInfo类，帮助查看sql语句中的表名和列名的合法性问题（为之后的sql渲染提供先决条件）；<br/>
3. 数据字典工具改为读取配置文件中的库；<br/>
<br/>
<b>2014.6.12</b><br/>
1. 实现缓存结构，并添加文件缓存模式，测试通过；<br/>
2. 整理文件引入方式，在一些情况下避免使用require_once方法；<br/>
3. 开启php调试模式，在index.php文件中；<br/>
4. Model中实现table方法和不包含as的fields方法；<br/>
5. 修改了一些可能报错的地方，完善错误代码；<br/>
<br/>
<b>2014.6.13</b><br/>
1. 添加mysqli链接方式以及查询方法的接口；<br/>
2. 完善代码中不够完善的地方；<br/>
3. 规范化了一下调试模式；<br/>
4. 将获取配置参数改到system.func.php文件中；<br/>
5. 添加引入文件的方法；<br/>
<br/>
<b>2014.6.14</b><br/>
1. 整合内部数据库连接的一些问题；<br/>
2. 添加where接口还未测试；<br/>
<br/>
<b>2014.6.15</b><br/>
1. 整理代码使单类文件不依赖于配置文件；<br/>
2. 优化addDir方法；<br/>