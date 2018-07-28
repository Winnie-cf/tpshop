<?php
namespace app\index\model;
use think\Model;
use catetree\Catetree;
class Goods extends Model
{
	//获取指定推荐位里的商品
   public function getRecposGoods($recposId,$limit=''){
   		$_hotIds=db('rec_item')->where(array('value_type'=>1,'recpos_id'=>$recposId))->select();
		$hotIds=array();
		foreach ($_hotIds as $k => $v) {
			$hotIds[]=$v['value_id'];
		}
		$map['id']=array('IN',$hotIds);
		$recRes=$this->field('id,mid_thumb,goods_name,shop_price,markte_price')->where($map)->limit($limit)->select();
		return $recRes;
   }

   //获取首页一、二级分类下的所有的推荐商品
   public function getIndexRecposGoods($cateId,$recposId){
   			$cateTree= new Catetree();
    		$sonIds=$cateTree->childrenids($cateId,db('category'));
    		$sonIds[]=$cateId;
    		//2、获取新品推荐位里符合条件的商品信息
    		$_recGoods=db('rec_item')->where(array('value_type'=>1,'recpos_id'=>$recposId))->select();
    		$recGoods=array();
    		foreach ($_recGoods as $kk => $vv) {
    			$recGoods[]=$vv['value_id'];
    		}
    		$map['category_id']=array('IN',$sonIds);
    		$map['id']=array('IN',$recGoods);
    		// dump($map); 
    		$goodsRes=db('goods')->where($map)->limit(6)->order('id DESC')->select();
    		return $goodsRes;
   }









}
