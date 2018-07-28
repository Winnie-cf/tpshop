<?php
namespace app\admin\validate;
use think\Validate;
class Goods extends Validate
{
    protected $rule =   [
        'goods_name'  => 'require|unique:goods',
        'category_id'   => 'require',
        'markte_price' => 'require',    
        'shop_price' => 'require',    
        'goods_weight' => 'require',    
    ];
    
    protected $message  =   [
        'goods_name.require' => '商品名称不能为空！',
        'goods_name.unique'     => '商品名称不能重复',
        'category_id.require'   => '商品所属栏目不能为空！',
        'markte_price.require'  => '商品市场价格不能为空！',
        // 'markte_price.num'  => '商品市场价格必须是数字！',
        'shop_price.require'  => '商品本店价格不能为空！',
        // 'shop_price.num'  => '商品本店价格必须是数字！',
        'goods_weight.require'=>'商品重量不能为空！',
        // 'goods_weight.num'=>'商品重量必须是数字！',
    ];


}