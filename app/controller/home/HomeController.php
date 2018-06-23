<?php
/**
 * Created by PhpStorm.
 * User: Fairytale
 * Date: 2018/5/3
 * Time: 15:18
 */

namespace Controller\home;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Pagination\LengthAwarePaginator;


class HomeController extends Controller
{
   public function index(Request $request,Response $response,$args)
    {
        return $this->view->render($response,"home/index.html");
    }
    //显示登录界面
    public function login(Request $request,Response $response,$args)
    {
        return $this->view->render($response,"home/login.html");
    }
    //登录
    public function dologin(Request $request,Response $response,$args)
    {
        $username = $request->getParsedBody()['username'];
        $password = md5($request->getParsedBody()['password']);
        // return $password;die;
        if (empty($username) || empty($password)){
            //判断用户非空
            return "alert('请输入用户信息')";
        }
        $sql = "select username from users where username='$username' and password ='$password'";
        // return $sql; die;
        $query = $this->container->db->query($sql);
        $result = $query->fetchColumn();
        // return $result;die;
        if(!empty($result)){
             // print_r($this->container->db->errorInfo());
            //写入session
            $_SESSION['username'] = $username;
            //跳转链接
            return "window.location.href = '/noteindex';";
//                header('Location:http://127.0.0.1:86');
        }else{
            return "alert('用户名或密码错误！');window.location.href = '/login';";
        }
    }
    //显示注册页面
    public function register(Request $request,Response $response,$args)
    {
        $this->logger->info("Slim-Skeleton '/' route");
        return $this->view->render($response, 'home/register.html');
    }
    //注册
    public function doregister(Request $request,Response $response,$args)
    {
        $username = $request->getParsedBody()['username'];
        $password = md5($request->getParsedBody()['password']);
        $repassword = md5($request->getParsedBody()['repassword']);
        $email = $request->getParsedBody()['email'];
        $sql = "select username from users where username = '$username' and email ='$email'";
        $query = $this->container->db->query($sql);
        $result = $query->fetchColumn();
        if (empty($username) || empty($password) || empty($email)){
            //判断用户非空
            return "alert('请输入用户信息！');";
        } elseif ($password != $repassword){
           return"alert('两次密码不一致！');";
        }elseif (!empty($result)) {
            return "alert('请您登录!');window.location.href = '/login'";
        }else{
            $sql = "insert into users(`username`,`password`,`email`) VALUES ('$username','$password','$email')";
            $exec = $this->db->exec($sql);
            if ($exec){
                return "alert('注册成功,请您登录!');window.location.href = '/login'";
            }
        }
    }
    //注销用户
    public function logout(Request $request,Response $response,$args)
    {
        session_unset($_SESSION['username']);
        echo "<script>alert('注销成功！');window.location.href='/login'</script>";
    }
    //显示笔记主页
    public function noteindex(Request $request,Response $response,$args)
    {
        //通过session查询当前用户的文件夹并显示
        if(!empty($_SESSION['username'])){
            $username = $_SESSION['username'];
            $sql= "select id from users where username='$username'";
            $query= $this->db->query($sql);
            $result=$query->fetchAll();
            $id=$result[0]['id'];
            $sql2="select * from file where u_id='$id'";
            $query2=$this->db->query($sql2);
            $result1=$query2->fetchAll();
            // exit;
            // var_dump($result1);exit;
            return $this->view->render($response,"home/pages/index.phtml",[
                'result1'=>$result1,
                'response'=>$response
            ]);
        }
        return $this->view->render($response,"home/pages/index.phtml",[
            'response'=>$response
        ]);
       
    }
    //显示新建笔记
    public function newnote(Request $request,Response $response,$args)
    {
         //通过session查询当前用户的文件夹并显示
         if(!empty($_SESSION['username'])){
            $username = $_SESSION['username'];
            $sql= "select id from users where username='$username'";
            $query= $this->db->query($sql);
            $result=$query->fetchAll();
            $id=$result[0]['id'];
            $sql2="select * from file where u_id='$id'";
            $query2=$this->db->query($sql2);
            $result1=$query2->fetchAll();
            // exit;
            // var_dump($result1);exit;
            return $this->view->render($response,"home/pages/noteindex.phtml",[
                'result1'=>$result1,
                'response'=>$response
            ]);
        }
        return $this->view->render($response,"home/pages/noteindex.phtml",[
            'response'=>$response
        ]);
        // return $this->view->render($response,"home/pages/noteindex.phtml");
    }
    //新建笔记
    public function donewnote(Request $request,Response $response,$args)
    {
        $title = $request->getParsedBody()['title'];
        $note = $request->getParsedBody()['note'];
        $f_name = $request->getParsedBody()['filename'];
        $sql = "select id from file where f_name='$f_name'";
        $query = $this->db->query($sql);
        foreach ($query as $key => $val) {
            $fid = $val['id'];
        }
        $username = $_SESSION['username'];
        // return $f_name;die;
        if (empty($username)) {
            return "alert('请登录!');window.location.href='/login';";
        }
        $sql = "select id from users where username = '$username'";
        $result = $this->db->query($sql); 
        foreach ($result as $value) {
            $uid = $value['id'];
        }
        // return($uid);die;
        if (empty($note)) {
            return "alert('please write your note')";
        }else{
            if (empty($title)) {
                $title = $note;
            }
            $sql_note = "insert into mynote(`f_id`,`u_id`,`author`,`title`,`content`) VALUES ('$fid','$uid','$username','$title','$note')";
            // return $sql_note;die;
            $exec = $this->container->db->exec($sql_note);
            if ($exec) {
                return "alert('保存成功!')";
            }
        }
    }
    //显示更新笔记
    public function changenote(Request $request,Response $response,$args)
    {
        $n_id = $args['id'];
        if(!empty($_SESSION['username'])){
            $username = $_SESSION['username'];
            $sql= "select id from users where username='$username'";
            $query= $this->db->query($sql);
            $result=$query->fetchAll();
            // var_dump($result);die();

            $id=$result[0]['id'];
            $sql2="select * from file where u_id='$id'";
            $query2=$this->db->query($sql2);
            $result1=$query2->fetchAll();
            // var_dump($result1);die();

            $sql3="select * from mynote where u_id='$id' and f_id = '$f_id'";
            $query3=$this->db->query($sql3);
            $result2=$query3->fetchAll();
            // var_dump($result2);die();
            
            $sql = "select id,title,content from mynote where id = '$n_id'";
            $query = $this->db->query($sql);
            $note = $query->fetchAll();
            // var_dump($note);die();

            return $this->view->render($response,"home/pages/changenote.phtml",[
                'result1'=>$result1,
                'result2'=>$result2,
                'note'=>$note,
                'response'=>$response
            ]);
        }
        return $this->view->render($response,'home/pages/changenote.phtml');
    }
    //改变笔记
    public function dochangenote(Request $request,Response $response,$args)
    {
        $n_id = $args['id'];
        // var_dump($n_id);die();
        $title = $request->getParsedBody()['title'];
        $content = $request->getParsedBody()['note'];
        // var_dump($content);die();
        if (!empty($_SESSION['username'])) {
            $sql = " update mynote  set title='$title',content='$content' where id='$n_id' ";
            // var_dump($sql);die();
            $exec = $this->db->query($sql);
            // var_dump($exec);die();
            if ($exec) {
                return "111";
            }
        }
    }
    //显示新建文件夹
    public function newfile(Request $request,Response $response,$args)
    {
        if(!empty($_SESSION['username'])){
            $username = $_SESSION['username'];
            $sql= "select id from users where username='$username'";
            $query= $this->db->query($sql);
            $result=$query->fetchAll();
            $id=$result[0]['id'];
            $sql2="select * from file where u_id='$id'";
            $query2=$this->db->query($sql2);
            $result1=$query2->fetchAll();
            // exit;
            // var_dump($result1);exit;
            return $this->view->render($response,"home/pages/newfile.phtml",[
                'result1'=>$result1,
                'response'=>$response
            ]);
        }
        return $this->view->render($response,'home/pages/newfile.phtml');
    }
    //新建文件夹
    public function donewfile(Request $request,Response $response,$args)
    {
        $username = $_SESSION['username'];
        $sql= "select id from users where username='$username'";
        $query= $this->db->query($sql);
        $result=$query->fetchAll();
        $id=$result[0]['id'];
        // return $id;die();
        $pre_file = $request->getParsedBody()['pre_file'];
        $fname = $request->getParsedBody()['name'];
        // var_dump($fname);die();
        if (empty($fname)) {
            return "alert('请输入信息')";
        }
        $sql = "select id,level from file where f_name='$pre_file'";
        $query = $this->db->query($sql);
        // var_dump($query);die();
        $result = $query->fetchAll();
        // var_dump($result);die();
        foreach ($result as $val) {
            $pre_fid=$val['id'];
            $level=$val['level']+1;
        }
        if (empty($pre_fid)) {
            $pre_fid='pre_fid';
            $level=1;
        }
        $sql1 = "insert into file(`u_id`,`f_name`,`pre_fid`,`level`) values('$id','$fname','$pre_fid','$level')";
        $exec = $this->db->exec($sql1);
        if ($exec) {
            return "alert('新建文件夹成功!');window.location.href='/newnote'";
        }
    }

    //我的资源

    public function mynoteindex(Request $request,Response $response,$args)
    {
        $f_id = $args['id'];
         if(!empty($_SESSION['username'])){
            $username = $_SESSION['username'];
            $sql= "select id from users where username='$username'";
            $query= $this->db->query($sql);
            $result=$query->fetchAll();
            // var_dump($result);die();
            $id=$result[0]['id'];

            // var_dump($id);die();
            $sql2="select * from file where u_id='$id'";
            $query2=$this->db->query($sql2);
            $result1=$query2->fetchAll();
            // var_dump($result1);die();

            $sql3="select * from mynote where u_id='$id' and f_id = '$f_id'";
            $query3=$this->db->query($sql3);
            $result2=$query3->fetchAll();
            // var_dump($result2);die();
            // exit;
            // var_dump($result1);exit;
            return $this->view->render($response,"home/pages/mynoteindex.phtml",[
                'result1'=>$result1,
                'result2'=>$result2,
                'response'=>$response
            ]);
        }
        //查询显示文件夹
        return $this->view->render($response,"home/pages/mynoteindex.phtml");
    }

    //回收站
    public function noteRecycle(Request $request,Response $response,$args)
    {
        if(!empty($_SESSION['username'])){
            $username = $_SESSION['username'];
            $sql= "select id from users where username='$username'";
            $query= $this->db->query($sql);
            $result=$query->fetchAll();
            $id=$result[0]['id'];
            $sql2="select * from file where u_id='$id'";
            $query2=$this->db->query($sql2);
            $result1=$query2->fetchAll();
            //获取回收站数据
            $sql3="select * from noterecycle where u_id='$id'";
            $query3=$this->db->query($sql3);
            $result3=$query3->fetchAll();
             //分页
            $page=$request->getParam('page',1);
            $perPage=$request->getParam('perPage',2);
            $recyclenote=new LengthAwarePaginator(
                $slicedNote=array_slice($result3,($page-1)*$perPage,$perPage),
                count($result3),
                $perPage,
                $page,
                ['path'=>$request->getUri()->getPath(),'query'=>$request->getParams()]
            );
            // exit;
            // var_dump($result1);exit;
            return $this->view->render($response,"home/pages/noterecycle.phtml",compact('recyclenote','result1'));
        }
        return $this->view->render($response,"home/pages/noterecycle.phtml");
    }
    //永久删除回收站数据
    public function deleteRecycleNote(Request $request,Response $response,$args)
    {
        $id=$args['id'];
        $sql="delete from noterecycle where id='$id'";
        $result=$this->db->exec($sql);
        if($result){
            echo "<script>alert('删除成功');location.href='/noterecycle';</script>";
        }
    }
    //恢复回收站数据
    public function rebackRecycleNote(Request $request,Response $response,$args)
    {
        $id=$args['id'];
        $sql="delete from noterecycle where id='$id'";
        $sql1="select * from noterecycle where id='$id'";
        $query=$this->db->query($sql1);
        $result=$query->fetchAll();
        foreach($result as $val){
            $u_id=$val['u_id'];
            $f_id=$val['f_id'];
            $title=$val['title'];
            $content=$val['content'];
        }
        $author=$_SESSION['username'];
        $sql2="insert into mynote(u_id,f_id,author,title,content)values('$u_id','$f_id','$author','$title','$content')";
        $result1=$this->db->exec($sql);
        if($result1){
            $this->db->exec($sql2);
            echo "<script>alert('恢复成功');location.href='/noterecycle';</script>";
        }   
    }
    //删除笔记到回收站
    public function delnote(Request $request,Response $response,$args)
    {
        $id=$args['id'];
        $sql="delete from mynote where id='$id'";
        $sql1="select * from mynote where id='$id'";
        $query=$this->db->query($sql1);
        $result=$query->fetchAll();
        foreach($result as $val){
            $u_id=$val['u_id'];
            $f_id=$val['f_id'];
            $title=$val['title'];
            $content=$val['content'];
        }
        $sql2="insert into noterecycle(u_id,f_id,title,content)values('$u_id','$f_id','$title','$content')";
        $result1=$this->db->exec($sql);
        if($result1){
            $this->db->exec($sql2);
            echo "<script>alert('删除成功');location.href='/noterecycle';</script>";
        }   
    }
    
}