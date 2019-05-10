<?php
/**
 * Created by PhpStorm.
 * User: jornsky
 * Date: 2019/4/4
 * Time: 23:39
 */

namespace app\index\model;


use think\Model;

class TopicTag extends  Model
{
        public static function getTopicTagsByTopicId($topicId){
            $result = self::where(['topic_id'=>$topicId])->select();

            return $result;

        }

        #建立关联关系
        public function tag(){
            $result = $this->belongsTo('Tag','tag_id');
            return $result;
        }

        #获取热门标签
        public static function gethotTags($num){
            $topictag = new self();
            return $topictag->field(['tag_id','count(topic_id) as topicNum'])
                ->group('tag_id')
                ->limit($num)
                ->select();
        }
}