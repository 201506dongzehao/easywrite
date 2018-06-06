<?php

namespace Controller\admin;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \interop\Container\ContainerInterface;

class UserController
{
    protected $ci;

    public function __construct(ContainerInterface $c)
    {
        $this->ci=$c;
    }
    //用户管理首页
    public function index(Request $request,Response $response,$args)
    {
        $sql='select * from users';
	    $query = $this->ci->db->query($sql);
	    $result = $query->fetchAll();
	    $response=$this->ci->view->render($response,'admin/user.phtml',[
		    'title'=>'用户管理',
		    'result'=>$result,
		    'response'=>$response
        ]);
        return $response;
    }
    //删除用户
    public function deleteUser(Request $request,Response $response,$args)
    {
        $id=$args['id'];
        $sql="delete from users where id='$id'";
        $result=$this->ci->db->exec($sql);
        if($result){
		    echo "<script>alert('删除成功');location.href='/admin/user';</script>";
	    } 
    }
}