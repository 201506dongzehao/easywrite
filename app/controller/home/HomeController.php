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
            return "alert('登录成功');window.location.href = '/noteindex';";
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
        return $this->view->render($response,"home/pages/index.phtml");
    }
    //显示新建笔记
    public function newnote(Request $request,Response $response,$args)
    {
        return $this->view->render($response,"home/pages/noteindex.phtml");
    }
    //新建笔记
    public function donewnote(Request $request,Response $response,$args)
    {
        $title = $request->getParsedBody()['title'];
        $note = $request->getParsedBody()['note'];
        $username = $_SESSION['username'];
        return $note;die;
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
            $sql_note = "insert into mynote(`u_id`,`author`,`title`,`content`) VALUES ('$uid','$username','$title','$note')";
            // return $sql_note;die;
            $exec = $this->container->db->exec($sql_note);
            if ($exec) {
                return "alert('保存成功!')";
            }
        }
    }
    //更新笔记
    public function changenote(Request $request,Response $response,$args)
    {

    }
}