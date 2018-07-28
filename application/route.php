<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::rule('cate/:id','index/Cate/index','get',['ext'=>'html|htm'],['id'=>'\d{1,3}']);
Route::rule('article/:id','index/Article/index','get',['ext'=>'html|htm'],['id'=>'\d{1,3}']);
//首页路由
Route::rule('index','index/Index/index','get',['ext'=>'html|htm']);
Route::rule('category/:id','index/Category/index','get',['ext'=>'html|htm'],['id'=>'\d{1,3}']);
Route::rule('goods/:id','index/Goods/index','get',['ext'=>'html|htm'],['id'=>'\d{1,3}']);
Route::rule('flow1','index/Flow/flow1','get',['ext'=>'html|htm']);