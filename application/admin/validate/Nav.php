<?php
namespace app\admin\validate;
use think\Validate;
class Nav extends Validate
{
    protected $rule =   [
        'nav_name'  => 'require|unique:nav',
        'nav_url'   => 'url',
        'pos' => 'require',    
    ];
    
    protected $message  =   [
        'nav_name.require' => '导航名称必须！',
        'nav_name.unique'     => '导航名称不能重复！',
        'nav_url.url'   => '导航地址格式不正确！',
        'pos.require'  => '导航位置不能为空！',
    ];


}