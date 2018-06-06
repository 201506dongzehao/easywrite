<?php

namespace Controller\admin;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \interop\Container\ContainerInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class NoteController
{
    protected $ci;

    public function __construct(ContainerInterface $c)
    {
        $this->ci=$c;
    }
    //文档管理首页
    public function index(Request $request,Response $response,$args)
    {
        $sql='select * from mynote';
	    $query = $this->ci->db->query($sql);
        $result = $query->fetchAll();
        //分页处理
        // $page=$request->getParam('page',1);
        // $perPage=$request->getParam('perPage',3);
        // $note=new LengthAwarePaginator(
        //     $slicedNote=array_slice($result,($page-1)*$perPage,$perPage),
        //     count($result),
        //     $perPage,
        //     $request->getParam('page',1),
        //     ['path'=>$request->getUri()->getPath(),'query'=>$request->getParams()]
        // );

	    $response=$this->ci->view->render($response,'admin/mynote.phtml',[
		    'title'=>'文档管理',
		    'result'=>$result,
		    'response'=>$response
        ]);
        return $response;
        // return $response->withJson([
        //     'data' =>$slicedNote,
        //     'meta' =>[
        //         'pagination' =>array($note->toArray(),['data'])
        //     ]
        // ]);
    }
    //查看文档内容
    public function showMynote(Request $request,Response $response,$args)
    {
        $id=$args['id'];
        $sql="select * from mynote where id='$id'";
        $query=$this->ci->db->query($sql);
        $result=$query->fetchAll();
        $response=$this->ci->view->render($response,'admin/showMynote.phtml',[
            'title'=>'查看文档',
            'result'=>$result,
            'response'=>$response
        ]);
        return $response;
    }
    //删除文档
    public function deleteNote(Request $request,Response $response,$args)
    {
        $id=$args['id'];
        $sql="delete from mynote where id='$id'"; 
        $sql2="select * from mynote where id='$id'";
        $query=$this->ci->db->query($sql2);
        $result1=$query->fetchAll();
        foreach($result1 as $val){
            $u_id=$val['u_id'];
            $n_id=$val['id'];
            $username=$val['author'];
            $title=$val['title'];
            $content=$val['content'];
        }
        $sql1="insert into recycle(u_id,n_id,username,title,content)values('$u_id','$n_id','$username','$title','$content')";
        $result = $this->ci->db->exec($sql);  //容器 dependencies里面
	    if($result){
            $this->ci->db->exec($sql1);
            echo "<script>alert('删除成功');location.href='/admin/mynote';</script>";
            
	    } 
    }
    //回收站管理
    public function recycleNote(Request $request,Response $response,$args)
    {
        $sql='select * from recycle';
	    $query = $this->ci->db->query($sql);
	    $result = $query->fetchAll();
	    $response=$this->ci->view->render($response,'admin/recycle.phtml',[
		    'title'=>'回收站管理',
		    'result'=>$result,
		    'response'=>$response
        ]);
        return $response;
    }
    //删除回收站数据
    public function recycleDelete(Request $request,Response $response,$args)
    {
        $id=$args['id'];
        $sql="delete from recycle where id='$id'";
        $result=$this->ci->db->exec($sql);
        if($result){
            echo "<script>alert('删除成功');location.href='/admin/recycle';</script>";
        }
    }
    //恢复回收站数据
    public function reback(Request $request,Response $response,$args)
    {
        $id=$args['id'];
        $sql="delete from recycle where id='$id'";
        $sql1="select * from recycle where id='$id'";
        $query=$this->ci->db->query($sql1);
        $result=$query->fetchAll();
        foreach($result as $val){
            $id=$val['n_id'];
            $u_id=$val['u_id'];
            $author=$val['username'];
            $title=$val['title'];
            $content=$val['content'];
        }
        $sql2="insert into mynote(id,u_id,author,title,content)values('$id','$u_id','$author','$title','$content')";
        $result=$this->ci->db->exec($sql);
        if($result){
            $this->ci->db->exec($sql2);
            echo "<script>alert('恢复成功');location.href='/admin/mynote';</script>";
        }   
    }
    //分享文档管理
    public function shareNote(Request $request,Response $response,$args)
    {
        $sql="select * from share";
        $query = $this->ci->db->query($sql);
	    $result = $query->fetchAll();
	    $response=$this->ci->view->render($response,'admin/share.phtml',[
		    'title'=>'分享文档管理',
		    'result'=>$result,
		    'response'=>$response
        ]);
        return $response;
    }
    //删除分享文档
    public function deleteShareNote(Request $request,Response $response,$args)
    {
        $id=$args['id'];
        $sql="delete from share where id='$id'";
        $result=$this->ci->db->exec($sql);
        if($result){
            echo "<script>alert('删除成功');location.href='/admin/share';</script>";
        }
    }
}