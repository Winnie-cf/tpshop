<?php
namespace app\admin\model;
use think\Model;
class Category extends Model
{
    protected $field=true;
    protected static function init()
    {

        category::beforeUpdate(function ($category) {
            // 商品id
            $categoryId=$category->id;
            // 新增商品属性
            $categoryData=input('post.');
            //处理商品推荐位
            db('rec_item')->where(array('value_type'=>2,'value_id'=>$categoryId))->delete();
            if(isset($categoryData['recpos'])){
                foreach ($categoryData['recpos'] as $k => $v) {
                    db('rec_item')->insert(['recpos_id'=>$v,'value_id'=>$categoryId,'value_type'=>2]);
                }
            } 
        });

        category::afterInsert(function($category){
            //接受表单数据
            $categoryData=input('post.');
            $categoryId=$category->id;
            //处理商品推荐位
            if(isset($categoryData['recpos'])){
                foreach ($categoryData['recpos'] as $k => $v) {
                    db('rec_item')->insert(['recpos_id'=>$v,'value_id'=>$categoryId,'value_type'=>2]);
                }
            }
        });
    }
        

}