<?php
/**
 * Created by PhpStorm.
 * User: jornsky
 * Date: 2019/4/4
 * Time: 23:02
 */

namespace app\index\model;


use think\Model;

class Topic extends Model
{

    public static function search($keyword){
       $result = self::where('title','like','%'.$keyword.'%')->select();
        
        return $result;
    }

    public function user(){
        return $this->belongsTo('User','user_id');
    }
    public static function getTopic($id){
        return self::find(['id'=>$id]);
    }
    #根据category的id得到具体的一级菜单二级菜单
    public static function getCategoryNames($categoryId){
        $categories = config('category');
        foreach ($categories as $k1=>$v1){
            foreach ($v1 as $k2=>$v2){
                if ($categoryId == $k2){
                    return [$k1,$v2];
                }
            }
        }
        return [];

    }

    public static function getTopics(){
        return self::all();
    }

    public static function getTopicsByIds($topicIds){

        return self::where('id','in',$topicIds)->select();
    }

    public static  function  getPageInfo($page,$limtNum){
        $page = intval($page) < 1 ?1:intval($page);
        #页面总数
        $count = self::count();
        #向上取整
        $pageNum =  ceil($count/$limtNum);
        $page = $page > $pageNum ?$pageNum :$page;

        $showPages = [];
        for ($leftPage = $page - 3;$leftPage<=$page;$leftPage++){
            if ($leftPage>0){
                $showPages[] = $leftPage;
            }
        }

        for ($i=1;$i<=3;$i++){
           if ($page + $i<=$pageNum ){
               $showPages[]=$page+$i;

           }
        }

        return ['page'=>$page,
                'showPages'=>$showPages,
                'pageNum'=>$pageNum];


    }




}