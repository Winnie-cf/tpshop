<?php
namespace app\admin\validate;
use think\Validate;
class Cate extends Validate
{
    protected $rule =   [
        'cate_name'  => 'require|unique:cate|min:6',
    ];
    
    protected $message  =   [
        'cate_name.require' => '分类名称必须',
        'cate_name.unique'     => '分类名称不能重复',
        'cate_name.min'     => '分类名称过短',
    ];


}