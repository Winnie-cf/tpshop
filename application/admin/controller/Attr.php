<?php
namespace app\admin\controller;
use think\Controller;
class Attr extends Controller
{
    public function lst()
    {
        $typeId=input('type_id');
        if($typeId){
            $map['type_id']=['=',$typeId];
        }else{
            $map=1;
        }
    	$attrRes=db('attr')->alias('a')->field('a.*,t.type_name')->join('type t',"a.type_id = t.id")->where($map)->order('a.id DESC')->paginate(6);
    	$this->assign([
    		'attrRes'=>$attrRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
            $data['attr_values']=str_replace('，', ',', $data['attr_values']);
    		//验证
    		$validate = validate('attr');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$add=db('attr')->insert($data);
    		if($add){
    			$this->success('添加商品属性成功！','lst');
    		}else{
    			$this->error('添加商品属性失败！');
    		}
    		return;
    	}
        // 获取属性所属属性
        $typeRes=db('type')->select();
        $this->assign([
            'typeRes'=>$typeRes,
            ]);
        return view();
    }

    public function edit()
    {
    	if(request()->isPost()){
    		$data=input('post.');
            $data['attr_values']=str_replace('，', ',', $data['attr_values']);
            //验证
    		$validate = validate('attr');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$save=db('attr')->update($data);
    		if($save !== false){
    			$this->success('修改商品属性成功！','lst');
    		}else{
    			$this->error('修改商品属性失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$attrs=db('attr')->find($id);
        // 获取属性所属属性
        $typeRes=db('type')->select();
    	$this->assign([
    		'attrs'=>$attrs,
            'typeRes'=>$typeRes,
    		]);
        return view();
    }

    public function del($id)
    {
    	$del=db('attr')->delete($id);
    	if($del){
			$this->success('删除商品属性成功！','lst');
		}else{
			$this->error('删除商品属性失败！');
		}
    }

    // 异步获取指定属性下的属性
    public function ajaxGetAttr(){
        $typeId=input('type_id');
        $attrRes=db('attr')->where(array('type_id'=>$typeId))->select();
        echo json_encode($attrRes);
    }




}