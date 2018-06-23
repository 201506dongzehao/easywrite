<?php

namespace Controller\admin;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \interop\Container\ContainerInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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
         //分页
         $page=$request->getParam('page',1);
         $perPage=$request->getParam('perPage',4);
         $user=new LengthAwarePaginator(
             $slicedNote=array_slice($result,($page-1)*$perPage,$perPage),
             count($result),
             $perPage,
             $page,
             ['path'=>$request->getUri()->getPath(),'query'=>$request->getParams()]
         );
	    $response=$this->ci->view->render($response,'admin/user.phtml',compact('user'));
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