<?php
/**
 * Created by PhpStorm.
 * User: jornsky
 * Date: 2019/4/12
 * Time: 10:35
 */

namespace app\admin\model;


use think\Model;

class Topic extends Model
{
    public function user(){
        return $this->belongsTo('User','user_id');
    }

}