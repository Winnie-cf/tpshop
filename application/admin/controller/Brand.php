<?php
namespace app\admin\controller;
use think\Controller;
class Brand extends Controller
{
    public function lst()
    {
    	$brandRes=db('brand')->order('id DESC')->paginate(2);
    	$this->assign([
    		'brandRes'=>$brandRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		// $data['brand_url'];  http://
    		if($data['brand_url'] && stripos($data['brand_url'],'http://') === false){
    			$data['brand_url']='http://'.$data['brand_url'];
    		}
    		//处理图片上传
    		if($_FILES['brand_img']['tmp_name']){
    			$data['brand_img']=$this->upload();
    		}
    		//验证
    		$validate = validate('Brand');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$add=db('brand')->insert($data);
    		if($add){
    			$this->success('添加品牌成功！','lst');
    		}else{
    			$this->error('添加品牌失败！');
    		}
    		return;
    	}
        return view();
    }

    public function edit()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		// $data['brand_url'];  http://
    		if($data['brand_url'] && stripos($data['brand_url'],'http://') === false){
    			$data['brand_url']='http://'.$data['brand_url'];
    		}
    		//处理图片上传
    		if($_FILES['brand_img']['tmp_name']){
    			$oldBrands=db('brand')->field('brand_img')->find($data['id']);
    			$oldBrandImg=IMG_UPLOADS.$oldBrands['brand_img'];
    			if(file_exists($oldBrandImg)){
    				@unlink($oldBrandImg);
    			}
    			$data['brand_img']=$this->upload();
    		}
    		//验证
    		$validate = validate('Brand');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$save=db('brand')->update($data);
    		if($save !== false){
    			$this->success('修改品牌成功！','lst');
    		}else{
    			$this->error('修改品牌失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$brands=db('brand')->find($id);
    	$this->assign([
    		'brands'=>$brands,
    		]);
        return view();
    }

    public function del($id)
    {
        $brand=db('brand');
    	$Brands=$brand->field('brand_img')->find($id);
        $BrandImg=IMG_UPLOADS.$Brands['brand_img'];
        if(file_exists($BrandImg)){
            @unlink($BrandImg);
        }
        $del=$brand->delete($id);
    	if($del){
			$this->success('删除品牌成功！','lst');
		}else{
			$this->error('删除品牌失败！');
		}
    }

    //上传图片
    public function upload(){
    // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('brand_img');
    
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