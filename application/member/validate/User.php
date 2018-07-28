<?php
namespace app\member\validate;
use think\Validate;
class User extends Validate
{
    protected $rule =   [
        'username'  => 'require|max:25|min:4|unique:user',
        'password'   => 'require|min:6',
        'email' => 'email|unique:user',    
        'mobile_phone' => 'number|unique:user|length:11',    
        'send_code'=>'number|length:6',
        'mobile_code'=>'number|length:6',
        'mobileagreement'=>'accepted',
        'register_type'=>'require|in:0,1',
    ];
    
    protected $message  =   [
        'username.require' => '用户名必须',
        'username.max' => '用户名过长',
        'username.min' => '用户名过短',
        'username.unique' => '用户名重复',
        'password.require' => '密码必须',
        'password.min' => '密码过短',
        'email.email'=>'邮件格式不正确',
        'email.unique'=>'邮件必须唯一',
        'mobile_phone.number'=>'手机号必须是数字',
        'mobile_phone.unique'=>'手机号必须唯一',
        'mobile_phone.length'=>'手机号错误',
        'send_code.number'     => '邮件验证码必须为数字',
        'send_code.legth'     => '邮件验证码长度不正确',
        'mobile_code.number'     => '短信验证码必须为数字',
        'mobile_code.legth'     => '短信验证码长度不正确',
        'mobileagreement.accepted'   => '请同意许可协议',
        'register_type.require'  => '未选择验证类型',
        'register_type.in'  => '验证类型错误',
    ];
    
}