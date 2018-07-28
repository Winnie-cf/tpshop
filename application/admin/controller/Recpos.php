<?php
namespace app\admin\controller;
use think\Controller;
class Recpos extends Controller
{
    public function lst()
    {
    	$recposRes=db('recpos')->paginate(6);
    	$this->assign([
    		'recposRes'=>$recposRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		//验证
    		$validate = validate('recpos');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$add=db('recpos')->insert($data);
    		if($add){
    			$this->success('添加推荐位成功！','lst');
    		}else{
    			$this->error('添加推荐位失败！');
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
    		$validate = validate('recpos');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$save=db('recpos')->update($data);
    		if($save !== false){
    			$this->success('修改推荐位成功！','lst');
    		}else{
    			$this->error('修改推荐位失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$recpos=db('recpos')->find($id);
    	$this->assign([
    		'recpos'=>$recpos,
    		]);
        return view();
    }

    public function del($id)
    {
    	$del=db('recpos')->delete($id);
    	if($del){
			$this->success('删除推荐位成功！','lst');
		}else{
			$this->error('删除推荐位失败！');
		}
    }




}