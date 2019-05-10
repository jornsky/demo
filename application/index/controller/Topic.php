<?php
/**
 * Created by PhpStorm.
 * User: jornsky
 * Date: 2019/4/3
 * Time: 18:42
 */

namespace app\index\controller;


use app\index\model\Tag as TagModel;
use app\index\model\Topic as TopicModel;
use app\index\model\TopicTag as TopicTagModel;
use think\Controller;

class Topic extends Controller


{


    public function newTopic(){
        #表单验证
        if ($this->request->isPost()){
            $postData = input('post.');
            print_r($postData);
            $user  = session('user');
            $topic = new TopicModel();
            $topic->title = $postData['thread']['title'];
            $topic->content = $postData['thread']['body'];
            $topic->user_id = $user->id;
            $topic->category_id = $postData['thread']['node_id'];
            $topic->created_at = intval(microtime(true));
            $topic->is_delete = 0;
            $topic->save();

            $tags = $postData['thread']['tags'];
            foreach ($tags as $tagname){
                if (is_numeric($tagname)){
                    $this->createTagTopic($tagname,$topic->id);
                    continue;
                }
                $newTag = $this->createTag($tagname);
                $this->createTagTopic($newTag->id,$topic->id);
            }


        }


        #模板变量输出
        $this->assign([
            'user'=>session('user'),
            'category'=>config('category'),
            'tags'=>TagModel::all(),
        ]);

        echo $this->fetch('newtopic');



    }

    public function detail(){
        $detailId = input('get.id');
        $topic = TopicModel::getTopic($detailId);
        $user = session('user');

        $this->assign([
            'topic'=>$topic,
            'user'=>$user,
            'categoryNames'=>TopicModel::getCategoryNames($topic->category_id),
           'topictags' =>TopicTagModel::getTopicTagsByTopicId($detailId),
        ]);
//        $tags =TopicTagModel::getTopicTagsByTopicId($detailId);
//       dump($tags[0]->tag->name) ;

        echo $this->fetch('detail');
    }

    public function index(){
        $page = input('get.page');
        $pageInfo =  TopicModel::getPageInfo($page,config('limitNum'));

        $cacheName = 'index'.$pageInfo['page'].'topics';
        $topics =cache($cacheName);
        if (!$topics){
            $topics = TopicModel::getTopics();
            cache($cacheName,$topics,1000);
        }


        $this->assign([
            'topics'=>$topics,
            'user'=>session('user'),
            'page'=>$pageInfo['page'],
            'showPages'=>$pageInfo['showPages'],
            'pageNum'=>$pageInfo['pageNum'],
            'hotTags'=>TopicTagModel::gethotTags(config('hotTagsNum')),

        ]);
        echo  $this->fetch('index');
    }

    public function search(){
        $page = input('get.page');
        $pageInfo =  TopicModel::getPageInfo($page,config('limitNum'));

        $keyword = input('get.keyword');
        $topics  = TopicModel::search($keyword);

        $this->assign([
            'topics'=>$topics,
            'user'=>session('user'),
            'showPages'=>$pageInfo['showPages'],
            'pageNum'=>$pageInfo['pageNum'],
            'hotTags'=>TopicTagModel::gethotTags(config('hotTagsNum')),

        ]);

        echo $this->fetch('index');



    }



    private function createTagTopic($tag, $id)
    {
        $topictag = new TopicTagModel();
        $topictag->topic_id=$id;
        $topictag->tag_id=$tag;
        $topictag->save();


    }

    private function createTag($name){
        $tag = new TagModel();
        $tag->name = $name;
        $tag->save();
        return $tag;

    }

    public function tag(){
            $page =1;
            $tag = input('get.tag');
            $pageInfo =  TopicModel::getPageInfo($page,config('limitNum'));
            $topicIds = TopicTagModel::where(['tag_id'=>$tag])
                ->column('topic_id');

            $topics = TopicModel::getTopicsByIds($topicIds);


           #模板变量
                $this->assign([
                    'topics'=>$topics,
                    'user'=>session('user'),
                    'showPages'=>$pageInfo['showPages'],
                    'pageNum'=>$pageInfo['pageNum'],
                    'hotTags'=>TopicTagModel::gethotTags(config('hotTagsNum')),
                ]);





        echo $this->fetch('index');

    }

}

