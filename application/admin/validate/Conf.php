<?php
namespace app\admin\validate;
use think\Validate;
class Conf extends Validate
{
    protected $rule =   [
        'cname'  => 'require|unique:conf',
        'ename'  => 'require|unique:conf', 
    ];
    
    protected $message  =   [
        'cname.require' => '配置中文名称必须',
        'cname.unique'     => '配置中文名称不能重复',
        'ename.require' => '配置英文名称必须',
        'ename.unique'     => '配置英文名称不能重复',
    ];


}