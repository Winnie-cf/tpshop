<?php
namespace app\admin\controller;
use think\Controller;
class MemberLevel extends Controller
{
    public function lst()
    {
    	$mlRes=db('memberLevel')->order('id DESC')->paginate(6);
    	$this->assign([
    		'mlRes'=>$mlRes,
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
    		$add=db('memberLevel')->insert($data);
    		if($add){
    			$this->success('添加会员级别成功！','lst');
    		}else{
    			$this->error('添加会员级别失败！');
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
    		$save=db('memberLevel')->update($data);
    		if($save !== false){
    			$this->success('修改会员级别成功！','lst');
    		}else{
    			$this->error('修改会员级别失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$mls=db('memberLevel')->find($id);
    	$this->assign([
    		'mls'=>$mls,
    		]);
        return view();
    }

    public function del($id)
    {
    	$del=db('memberLevel')->delete($id);
    	if($del){
			$this->success('删除会员级别成功！','lst');
		}else{
			$this->error('删除会员级别失败！');
		}
    }



}