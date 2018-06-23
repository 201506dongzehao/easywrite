<?php

namespace Controller\admin;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \interop\Container\ContainerInterface;


class IndexController
{
    protected $ci;

    public function __construct(ContainerInterface $c)
    {
        $this->ci=$c;
    }
    //后台主页
    public function index(Request $request,Response $response,$args)
    {
        $sql="select * from adminuser";
        $query=$this->ci->db->query($sql);
        $result=$query->fetchAll();
        $count=count($result);
        $response=$this->ci->view->render($response,'admin/index.phtml',[
            'count'=>$count,
            'response'=>$response
        ]);
        return $response;
    }
    //登录功能
    public function login(Request $request,Response $response,$args)
    {
        return $this->ci->view->render($response,'admin/login.phtml');
    }
    //判断登录各项
    public function dologin(Request $request,Response $response,$args)
    {
        $username=$request->getParsedBody()['username'];
        $password=$request->getParsedBody()['password'];
        $sql="select * from adminuser where username='$username' and password='$password'";
        $query=$this->ci->db->query($sql);
        $result = $query->fetchColumn();
        if(!empty($result))
        {
            $_SESSION['username']=$username;
            // session('loginedUser',$username);
            echo "<script>window.location.href = '/admin/index';</script>";
        }
        else{
            echo "<script>alert('用户名或密码错误！');window.location.href = '/admin/adminlogin';</script>";
        }
    }
    //退出登录
    public function loginout(Request $request,Response $response,$args)
    {
        unset($_SESSION['username']);
        echo "<script>window.location.href = '/admin/adminlogin';</script>";
    }
}