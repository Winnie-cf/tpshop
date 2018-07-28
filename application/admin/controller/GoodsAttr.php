<?php
namespace app\admin\controller;
use think\Controller;
class GoodsAttr extends Controller
{
    // 异步获取指定类型下的属性
    public function ajaxdelga(){
        $del=db('goodsAttr')->delete(input('gaid'));
        if($del){
            echo 1; //删除成功
        }else{
            echo 2; //删除失败
        }
    }




}