<?php
namespace app\admin\validate;
use think\Validate;
class CategoryWords extends Validate
{
    protected $rule =   [
        'category_id'  => 'require',
        'word'  => 'require|unique:category_words', 
    ];
    
    protected $message  =   [
        'category_id.require' => '所属分类必须',
        'word.require' => '关联词名称必须',
        'word.unique'     => '关联词名称不能重复',
    ];


}