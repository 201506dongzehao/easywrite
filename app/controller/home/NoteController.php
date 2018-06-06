<?php
/**
 * Created by PhpStorm.
 * User: Fairytale
 * Date: 2018/5/18
 * Time: 8:23
 */

namespace Controller\home;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class NoteController extends Controller
{
    public function manageType(Request $request,Response $response,$args)
    {
        return $this->view->render($response,"home/pages/forms.html");
    }
    //联系我们
    public function contact(Request $request,Response $response,$args)
    {
        $name = $request->getParsedBody()['name'];
        $email = $request->getParsedBody()['email'];
        $message = $request->getParsedBody()['message'];
//        return "$name";

        if (empty($name) || empty($email)||empty($message)){
            return '请输入完整信息';
        }
        else{
            $sql = "insert into contact(`name`,`email`,`message`) VALUES ('$name','$email','$message')";
            $exec = $this->db->exec($sql);
            if ($exec){
                return '已提交';
            }
        }
    }
    
}