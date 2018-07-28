<?php
namespace app\admin\validate;
use think\Validate;
class Order extends Validate
{
    protected $rule =   [
        'username'   => 'require',
        'goods_total_price'   => 'require',
        'payment'   => 'require',
        'distribution'   => 'require',
        'order_status'   => 'require',
        'pay_status'   => 'require',
        'post_status'   => 'require',
        'post_spent'   => 'require',
        'phone'   => 'require',
        'province'   => 'require',
        'city'   => 'require',
        'country'   => 'require',
        'address'   => 'require',
        'order_time'   => 'require',
    ];
    

    protected $message =   [
        'username.require'   => '用户名不能为空！',
        'goods_total_price.require'   => '商品总价不能为空！',
        'payment.require'   => '支付方式不能为空！',
        'distribution.require'   => '配送方式不能为空！',
        'order_status.require'   => '订单状态不能为空！',
        'pay_status.require'   => '支付状态不能为空！',
        'post_status.require'   => '发货状态不能为空！',
        'post_spent.require'   => '运费不能为空！',
        'phone.require'   => '手机号不能为空！',
        'province.require'   => '省份不能为空！',
        'city.require'   => '市不能为空！',
        'country.require'   => '地区不能为空！',
        'address.require'   => '详细地址不能为空！',
        'order_time.require'   => '下单时间不能为空！',
    ];

}