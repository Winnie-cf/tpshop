<?php
namespace app\admin\controller;
use think\Controller;
class AlternateImg extends Controller
{
    public function lst()
    {
        $alternateImg=db('alternateImg');
        if(request()->isPost()){
            $data=input('post.');
            foreach ($data['sort'] as $k => $v) {
                $alternateImg->where('id','=',$k)->update(['sort'=>$v]);
            }
            $this->success('排序成功！');
        }
    	$alternateImgRes=$alternateImg->order('sort DESC')->paginate(6);
    	$this->assign([
    		'alternateImgRes'=>$alternateImgRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		// $data['link_url'];  http://
    		if($data['link_url'] && stripos($data['link_url'],'http://') === false){
    			$data['link_url']='http://'.$data['link_url'];
    		}
    		//处理图片上传
    		if($_FILES['img_src']['tmp_name']){
    			$data['img_src']=$this->upload();
    		}
    		//验证
   //  		$validate = validate('alternateImg');
   //  		if(!$validate->check($data)){
			//     $this->error($validate->getError());
			// }
    		$add=db('alternateImg')->insert($data);
    		if($add){
    			$this->success('添加轮播图成功！','lst');
    		}else{
    			$this->error('添加轮播图失败！');
    		}
    		return;
    	}
        return view();
    }

    public function edit()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		// $data['link_url'];  http://
    		if($data['link_url'] && stripos($data['link_url'],'http://') === false){
    			$data['link_url']='http://'.$data['link_url'];
    		}
    		//处理图片上传
    		if($_FILES['img_src']['tmp_name']){
    			$oldalternateImgs=db('alternateImg')->field('img_src')->find($data['id']);
    			$oldalternateImgImg=IMG_UPLOADS.$oldalternateImgs['img_src'];
    			if(file_exists($oldalternateImgImg)){
    				@unlink($oldalternateImgImg);
    			}
    			$data['img_src']=$this->upload();
    		}
    		//验证
   //  		$validate = validate('alternateImg');
   //  		if(!$validate->check($data)){
			//     $this->error($validate->getError());
			// }
    		$save=db('alternateImg')->update($data);
    		if($save !== false){
    			$this->success('修改轮播图成功！','lst');
    		}else{
    			$this->error('修改轮播图失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$alternateImgs=db('alternateImg')->find($id);
    	$this->assign([
    		'alternateImgs'=>$alternateImgs,
    		]);
        return view();
    }

    public function del($id)
    {
        $alterImg=db('alternateImg');
        $alterImgs=$alterImg->field('img_src')->find($id);
        $imgSrc=IMG_UPLOADS.$alterImgs['img_src'];
        if(file_exists($imgSrc)){
            @unlink($imgSrc);
        }
    	$del=$alterImg->delete($id);
    	if($del){
			$this->success('删除轮播图成功！','lst');
		}else{
			$this->error('删除轮播图失败！');
		}
    }

    //上传图片
    public function upload(){
    // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('img_src');
    
    // 移动到框架应用根目录/public/uploads/ 目录下
    if($file){
        $info = $file->move(ROOT_PATH . 'public' . DS . 'static'. DS .'uploads');
        if($info){
            $imgSrc=$info->getSaveName();
            $imgSrc=str_replace('\\', '/', $imgSrc);
            return $imgSrc;
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
            die;
        }
    }
}


}