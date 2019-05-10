<?php
/**
 * Created by PhpStorm.
 * User: jornsky
 * Date: 2019/4/11
 * Time: 22:26
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\Topic as TopicModel;

class Index extends Controller
{
    public function login(){
        if(request()->isPost()){
            $login = input('post.username');
            $password = input('post.password');
            $conn = array();
            $conn['name|email'] = $login;
            $conn['password'] = md5($password);
            $user = \app\index\model\User::where($conn)->find();
            if($user){
                $this->success('登入成功！','index/index');
            }else{
                $this->error('登入失败，账号密码错误！');

            }




        }
        echo $this->fetch('login');
    }

    public  function index(){
        $adminInfo = array();
        $adminInfo['useraccount'] = \app\admin\model\User::count();
        $adminInfo['topicaccount'] = TopicModel::count();



        $this->assign([
            'adminInfo'=>$adminInfo,
            'active'=>'index',


        ]);


        echo $this->fetch('index');

    }

    public  function topics(){
        $topics = TopicModel::all();
        $this->assign([
            'active'=>'topic',
            'topics'=>$topics,
        ]);
        return $this->fetch('topic_manage');
    }

}