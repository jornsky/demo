<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


  function getCategoryNames($categoryId){
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
