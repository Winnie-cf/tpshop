<?php
namespace app\common\model;
use think\Model;
class CategoryAd extends Model
{
	//获取首页顶级栏目左侧图片
	public function getCategoryAd($id){
		$_data=db('CategoryAd')->where('category_id','=',$id)->select();
		$data=array();
		foreach ($_data as $k => $v) {
			$data[$v['position']][]=$v;
		}
		return $data;
	}

}
