<?php
namespace app\common\model;
use think\Model;
class Article extends Model
{
    public function getFooterArts()
    {
    	//获取帮助分类
        $helpCateRes=model('cate')->where(array('cate_type'=>3))->order('sort DESC')->select();
        foreach ($helpCateRes as $k => $v) {
        	$helpCateRes[$k]['arts']=$this->where(array('cate_id'=>$v['id']))->select();
        }
        return $helpCateRes;
    }

    //获取网店信息
    public function getShopInfo(){
    	$artArr=$this->where('cate_id','=',3)->field('id,title')->select();
    	return $artArr;
    }

    public function getArts($id,$limit){
        $arts=$this->where('cate_id','=',$id)->order('id DESC')->limit($limit)->select();
        return $arts;
    }
}
