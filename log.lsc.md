
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
<br/>
<b>2014.6.16</b><br/>
1. 修改db类为Db类并修改相应代码；<br/>
2. 修正mysql链接方式问题；<br/>
3. 模板渲染----后端controller传变量到前端页面中，实现代码通用；<br/>
4. 将数据字典的数据库改为使用一个数据库即可查出相应数据；<br/>
5. 向mvc模式开发靠拢将页面上所有查询数据的语句改到controller中书写；<br/>
<br/>
<b>2014.6.17</b><br/>
1. 添加各种常量以使程序更方便给其他人编辑；<br/>
2. 在html模块中输出示例以测试；<br/>
3. 目前注意include方法中需要写入绝对地址，因为缓存页面渲染时并没有执行include方法；<br/>
4. 添加获取文件信息的方法(优化file_get_contents方法)；<br/>
5. 优化了一下model类；<br/>
6. 添加curl类，待测试；<br/>
7. 添加Log类，基本功能已完善，待需要添加的地方添加上；<br/>
<br/>
<b>2014.6.18</b><br/>
1. CURL类通过测试；<br/>
2. 完善log记录；<br/>
<br/>
<b>2014.6.18</b><br/>
1. 添加部分where的field过滤；<br/>
2. 添加group和order接口，并完善；<br/>
<br/>
<b>2014.6.19</b><br/>
1. 优化添加文件夹方法；<br/>
2. 文件缓存提出可以输入任何内容的方法，以便后期修改内容结构使用；<br/>
3. 时间获取优化，添加时区设置；<br/>
4. 改善table接口的数据渲染，添加as处理，将table接口的输入简化；<br/>
5. 添加清除缓存文件接口，以后要添加权限验证；<br/>
6. 添加IP定位GPS的示例；<br/>
7. 将核心入口文件拆分；<br/>
8. 添加about页面示例；<br/>
<br/>
<b>2014.6.20</b><br/>
1. 将errorPage方法和C方法放到core/common/system.func.php文件中，以便以后纠错方便（该文件不适宜修改）；<br/>
2. 自定义fullPage示例；<br/>
3. 优化Model中的replaceValue方法；<br/>
4. 添加方法比较示例Controller；<br/>
5. 将框架里能用foreach的全部转换为foreach；<br/>
<br/>
<b>2014.6.22</b><br/>
1. 模板渲染部分优化；<br/>
2. 建立简单任务模块的大框；<br/>
3. 优化了模板渲染时，后端变量传到全台页面的方式；<br/>
4. 在页面上添加了页面的编码方式“<meta content="text/html; charset=utf-8" http-equiv="Content-Type">”，确保正常情况下不会再出现乱码的可能性；<br/>
5. 整理function文件中的一些方法名；<br/>
6. 创建静态页面在Controller类中添加createHtml方法；<br/>
<br/>
<b>2014.6.24</b><br/>
1. 添加任务类，在function中添加任务运行方法单个（runTask）和全部（runAllTask）两个方法；<br/>
2. 添加session处理，添加logout.php登出文件；<br/>
<br/>
<b>2014.6.25</b><br/>
1. 完善S函数，当他的第三个形参为0时永不清除缓存；<br/>
2. 改善数据字典工具；<br/>
<br/>
<b>2014.6.26</b><br/>
1. 优化缓存方法；<br/>
2. 优化生成html页面的方法；<br/>
3. 完善个人任务模块流程；<br/>
4. 完善Controller->display方法；<br/>
5. 完善首页列表；<br/>
6. Model类添加limit、join、having接口；<br/>
7. 完善sql日志；<br/>
<br/>
<b>2014.6.26</b><br/>
1. 完善个人任务模块流程；<br/>
<br/>
<b>2014.6.27</b><br/>
1. 添加initH方法，使页面能够添加公共头；<br/>
<br/>
<b>2014.7.1</b><br/>
1. 添加扩展模块接口，目前只支持如下形式：模块名/模块名.php，即引入入口文件；<br/>
2. 加入PHPExcel扩展模块，添加读取示例；<br/>
3. 完善个人任务的缓存；<br/>
4. 添加数据和上传文件夹和路径；<br/>
5. Model添加add、set、delete接口，完善where，filterColumns等接口；<br/>
6. 优化扩展接口，添加扩展路径常量；<br/>
7. 提出Excel读取方法；<br/>
<br/>
<b>2014.7.6</b><br/>
1. 添加缩略图方法；<br/>
2. 整理前端示例；<br/>
<br/>
<b>2014.7.7</b><br/>
1. 修改缩略图方法，将其变成缩略图的基类；<br/>
<br/>
<b>2014.7.8</b><br/>
1. 优化缩略图基类；<br/>
<br/>
<b>2014.7.10</b><br/>
1. 添加csv示例；<br/>
2. 添加excel生成示例；<br/>


1. 创建建表语句和示例，写出建表的表单页；
2. 建立一个任务历史单页，可以建立任务，以及查看任务和完成任务；
