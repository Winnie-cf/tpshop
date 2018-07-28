<?php
namespace app\admin\controller;
use think\Controller;
class Nav extends Controller
{
    public function lst()
    {
        $nav=db('nav');
        if(request()->isPost()){
            $data=input('post.');
            foreach ($data['sort'] as $k => $v) {
                $nav->where('id','=',$k)->update(['sort'=>$v]);
            }
            $this->success('排序成功！');
        }
    	$navRes=$nav->order('sort DESC')->paginate(6);
    	$this->assign([
    		'navRes'=>$navRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		if($data['nav_url'] && stripos($data['nav_url'],'http://') === false){
    			$data['nav_url']='http://'.$data['nav_url'];
    		}
    		//验证
    		$validate = validate('Nav');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$add=db('nav')->insert($data);
    		if($add){
    			$this->success('添加导航成功！','lst');
    		}else{
    			$this->error('添加导航失败！');
    		}
    		return;
    	}
        return view();
    }

    public function edit()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		if($data['nav_url'] && stripos($data['nav_url'],'http://') === false){
    			$data['nav_url']='http://'.$data['nav_url'];
    		}
    		//验证
    		$validate = validate('nav');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$save=db('nav')->update($data);
    		if($save !== false){
    			$this->success('修改导航成功！','lst');
    		}else{
    			$this->error('修改导航失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$navs=db('nav')->find($id);
    	$this->assign([
    		'navs'=>$navs,
    		]);
        return view();
    }

    public function del($id)
    {
    	$del=db('nav')->delete($id);
    	if($del){
			$this->success('删除导航成功！','lst');
		}else{
			$this->error('删除导航失败！');
		}
    }

    //上传图片
    public function upload(){
    // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('nav_img');
    
    // 移动到框架应用根目录/public/uploads/ 目录下
    if($file){
        $info = $file->move(ROOT_PATH . 'public' . DS . 'static'. DS .'uploads');
        if($info){
            return $info->getSaveName();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
            die;
        }
    }
}


}