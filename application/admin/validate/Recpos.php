<?php
namespace app\admin\validate;
use think\Validate;
class Recpos extends Validate
{
    protected $rule =   [
        'rec_name'  => 'require|unique:recpos', 
    ];
    
    protected $message  =   [
        'rec_name.require' => '推荐位名称必须',
        'rec_name.unique'     => '推荐位名称不能重复',
    ];


}