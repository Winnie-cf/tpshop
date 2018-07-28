<?php
namespace app\admin\validate;
use think\Validate;
class Article extends Validate
{
    protected $rule =   [
        'title'  => 'require|unique:article',
        'cate_id'  => 'require',
        'email'=>'email',
        'link_url'=>'url',
    ];
    
    protected $message  =   [
        'title.require' => '标题必须',
        'title.unique'     => '标题不能重复',
        'cate_id.require'=>'所属栏目不能为空',
    ];


}