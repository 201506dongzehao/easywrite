<?php

use Slim\Http\Request;
use Slim\Http\Response;
// Routes
//前台路由
//index
$app->get('/home/index','Controller\home\HomeController:index');
//前台登录
$app->get('/login','Controller\home\HomeController:login');
$app->post('/dologin','Controller\home\HomeController:dologin');
//前台注册
$app->get('/register','Controller\home\HomeController:register');
$app->post('/doregister','Controller\home\HomeController:doregister');
//注销用户
$app->get('/logout','Controller\home\HomeController:logout');
//笔记首页
$app->get('/noteindex','Controller\home\HomeController:noteindex');
//新建笔记
$app->get('/newnote','Controller\home\HomeController:newnote');
$app->post('/donewnote','Controller\home\HomeController:donewnote');
//删除笔记
$app->get('/delnote/{id}','Controller\home\HomeController:delnote');

//新建文件夹
$app->get('/newfile','Controller\home\HomeController:newfile');
$app->post('/donewfile','Controller\home\HomeController:donewfile');

$app->get('/manageType','Controller\home\NoteController:manageType');
$app->post('/contact','Controller\home\NoteController:contact');
//我的资源
// $app->get('/mynote','Controller\home\HomeController:mynote');
$app->get('/mynoteindex/{id}','Controller\home\HomeController:mynoteindex');

//回收站
$app->get('/noterecycle','Controller\home\HomeController:noteRecycle');
//删除回收站数据
$app->get('/noterecycle/delete/{id}','Controller\home\HomeController:deleteRecycleNote');
//恢复回收站数据
$app->get('/rebackrecycle/{id}','Controller\home\HomeController:rebackRecycleNote');

//修改笔记
$app->get('/changenote/{id}','Controller\home\HomeController:changenote');
$app->post('/dochangenote/{id}','Controller\home\HomeController:dochangenote');






//后台路由
$app->get('/admin/index','Controller\admin\IndexController:index');
//用户管理
$app->get('/admin/user','Controller\admin\UserController:index');
//管理员登录
$app->get('/admin/adminlogin','Controller\admin\IndexController:login');
//管理员注销
$app->get('/admin/loginout','Controller\admin\IndexController:loginout');
$app->post('/admin/dologin','Controller\admin\IndexController:dologin');
//用户删除
$app->get('/user/delete/{id}','Controller\admin\UserController:deleteUser');
//文档管理
$app->get('/admin/mynote','Controller\admin\NoteController:index');
//查看文档内容
$app->get('/admin/showMynote/{id}','Controller\admin\NoteController:showMynote');
//删除文档
$app->get('/mynote/delete/{id}','Controller\admin\NoteController:deleteNote');
//回收站管理
$app->get('/admin/recycle','Controller\admin\NoteController:recycleNote');
//删除回收站数据
$app->get('/recycle/delete/{id}','Controller\admin\NoteController:recycleDelete');
//恢复回收站数据
$app->get('/recycle/reback/{id}','Controller\admin\NoteController:reback');
//分享文档管理
$app->get('/admin/share','Controller\admin\NoteController:shareNote');
//删除分享文档
$app->get('/share/delete/{id}','Controller\admin\NoteController:deleteShareNote');

