<?php
namespace app\admin\controller;
use think\Controller;
class Type extends Controller
{
    public function lst2()
    {
        $typeRes=array();
        $typeRes=db('type')->order('id DESC')->paginate(6);
        return json($typeRes);
    }

    public function lst()
    {
    	$typeRes=db('type')->order('id DESC')->paginate(6);
    	$this->assign([
    		'typeRes'=>$typeRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		//验证
   //  		$validate = validate('type');
   //  		if(!$validate->check($data)){
			//     $this->error($validate->getError());
			// }
    		$add=db('type')->insert($data);
    		if($add){
    			$this->success('添加商品类型成功！','lst');
    		}else{
    			$this->error('添加商品类型失败！');
    		}
    		return;
    	}
        return view();
    }

    public function edit()
    {
    	if(request()->isPost()){
    		$data=input('post.');
            //验证
   //  		$validate = validate('type');
   //  		if(!$validate->check($data)){
			//     $this->error($validate->getError());
			// }
    		$save=db('type')->update($data);
    		if($save !== false){
    			$this->success('修改商品类型成功！','lst');
    		}else{
    			$this->error('修改商品类型失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$types=db('type')->find($id);
    	$this->assign([
    		'types'=>$types,
    		]);
        return view();
    }

    public function del($id)
    {
    	$del=db('type')->delete($id);
        // 删除商品类型下面的商品属性
        db('attr')->where(array('type_id'=>$id))->delete();
    	if($del){
			$this->success('删除商品类型成功！','lst');
		}else{
			$this->error('删除商品类型失败！');
		}
    }



}