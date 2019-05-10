<?php
namespace app\index\controller;

use app\index\model\User;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    /**注册页面的控制器
     * @return mixed|void
     */
    public function register(){
        #判断是否提交post表单

        if($this->request->isPost()){
            #接受所有post数据
            $postData = input('post.');
            #验证表单
//            if (!checkInput($postData['verifycode'])){
//                return $this->error("验证码错误！");
//            }

            if (!$this->checkPassword($postData)){
                return $this->error("密码错误或者两次密码不一致！");
            }
            #数据插入
            $user = new User();

            $user->name = $postData['username'];
            $user->email = $postData['email'];
            $user->password = md5($postData['password']);
            $user->id_admin = 0;
            $user->id_delete = 0;
            $user->avatar = 'images/avatar.jpg';
            $user->create_at = intval(microtime(true));
            $user->save();

            return $this->success('注册成功！');
        }
	echo $this->fetch('register');

    }

    private function checkPassword($postData){
        if (!$postData['password']){
            return false;
        }

        if ($postData['password'] != $postData['password_confirmation']){
            return false;
        }

        return true;

    }

    /**
     *处理登入页面表单 并且存入Session
     */
    public function login(){
        if ($this->request->isPost()){
            $login = input('post.login');
            $password = input('post.password');
            #写条件
            $conn = [];
            $conn['email|name'] = $login;
            $conn['password']=md5($password);
            #条件查询
            $user =  User::get($conn);
            if ($user){
                #存入session
                session('user',$user);
                $this->success("登入成功！");
            }else{
                $this->success("登入失败！");
            }


        }

        echo $this->fetch('login',['user'=>session('user')]);
    }

    public function logout(){
        session('user',null);
        echo $this->fetch('login',['user'=>session('user')]);

    }

    public function test(){
        $topics = [
            'user1'=>[
                'id'=>1,
                'name'=>'xjf',

            ],
            'user2'=>[
                'id'=>2,
                'name'=>'xdf',

            ],
        ];
        $this->assign([
            'topics'=>$topics,


        ]);
        echo $this->fetch('test');
    }





}
