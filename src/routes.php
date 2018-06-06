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

$app->get('/manageType','Controller\home\NoteController:manageType');
$app->post('/contact','Controller\home\NoteController:contact');






//后台路由
$app->get('/admin/index','Controller\admin\IndexController:index');
//用户管理
$app->get('/admin/user','Controller\admin\UserController:index');
//管理员登录
$app->get('/admin/adminlogin','Controller\admin\IndexController:login');
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

