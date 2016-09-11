<?php
// 1.引入模板类
include "libs/Template.class.php";
// 2.实例化模板对象
$tpl = new Template();
// 3.设置相关属性
$tpl->template_dir = "templates";
$tpl->compile_dir = "templates_c";
$tpl->caching = true;
$tpl->cache_dir = "cache";
// 4.分配数据
$tpl->assign('title','自定义模板引擎');
$tpl->assign('content','通过自定义模板引擎，深入理解smarty');
$tpl->assign('love',false);
$user = array('张无忌','李寻欢','王语嫣','赵敏');
$tpl->assign('user',$user);
// 5.载入模板文件
$tpl->display('index.html');