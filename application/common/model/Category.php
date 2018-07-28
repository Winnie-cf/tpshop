<?php
namespace app\common\model;
use think\Model;
class Category extends Model
{
	//顶级和二级分类获取
   public function getCates(){
      $cateRes=$this->where(array('pid'=>0))->order('sort ASC')->select();
      foreach ($cateRes as $k => $v) {
         $cateRes[$k]['children']=$this->where(array('pid'=>$v['id']))->select();
      }
      return $cateRes;
   }

	//通过顶级分类id获取二级和三级子分类
	public function getSonCates($id){
		$cateRes=$this->where(array('pid'=>$id))->select();//获取二级分类
		foreach ($cateRes as $k => $v) {
			$cateRes[$k]['children']=$this->where(array('pid'=>$v['id']))->select();//获取三级分类
		}
		return $cateRes;
	}   

	//通过顶级分类获取相关的关联搜索词
	public function getCategoryWords($id){
		$cwRes=db('categoryWords')->where('category_id','=',$id)->select();
		return $cwRes;
	}

	//获取当前栏目关联品牌及推广信息
	public function getCategoryBrands($id){
		$data=array();
		$brand=db('brand');
		$categoryBrands=db('categoryBrands')->where(array('category_id'=>$id))->find();
		$brandsIdArr=explode(',', $categoryBrands['brands_id']);
		foreach ($brandsIdArr as $k => $v) {
			$data['brands'][]=$brand->find($v);
		}
		//推广信息
		$data['promotion']['pro_img']=$categoryBrands['pro_img'];
		$data['promotion']['pro_url']=$categoryBrands['pro_url'];
		return $data;
	}

	//首页推荐分类获取
	public function getRecCategorys($recposId,$pid=0){
		$_cateRes=db('rec_item')->where(array('recpos_id'=>$recposId,'value_type'=>2))->select();
		$cateRes=array();
		foreach ($_cateRes as $k => $v) {
			$catesArr=db('category')->where(array('id'=>$v['value_id'],'pid'=>$pid))->find();
			if($catesArr){
				$cateRes[]=$catesArr;
			}
		}
		return $cateRes;
	}



}
