<?php
namespace app\admin\controller;
use think\Controller;
class CategoryAd extends Controller
{
    public function lst()
    {
    	$caRes=db('CategoryAd')->field('ca.*,c.cate_name')->alias('ca')->join('category c',"ca.category_id = c.id")->order('ca.id DESC')->paginate(6);
    	$this->assign([
    		'caRes'=>$caRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
            if($data['position']=='B' || $data['position']=='C'){
                $cas=db('CategoryAd')->where(array('position'=>$data['position'],'category_id'=>$data['category_id']))->select();
                if($cas){
                    $this->error('当前位置只能添加一条记录！');
                }
            }
    		// $data['link_url'];  http://
    		if($data['link_url'] && stripos($data['link_url'],'http://') === false){
    			$data['link_url']='http://'.$data['link_url'];
    		}
    		//处理图片上传
    		if($_FILES['img_src']['tmp_name']){
    			$data['img_src']=$this->upload();
    		}else{
                $this->error('请上传图片！');
            }
    		//验证
   //  		$validate = validate('link');
   //  		if(!$validate->check($data)){
			//     $this->error($validate->getError());
			// }
    		$add=db('CategoryAd')->insert($data);
    		if($add){
    			$this->success('添加关联图片成功！','lst');
    		}else{
    			$this->error('添加关联图片失败！');
    		}
    		return;
    	}
        //获取所有的顶级分类
        $cateRes=model('Category')->where(array('pid'=>0))->select();
        $this->assign([
            'cateRes'=>$cateRes,
            ]);
        return view();
    }

    
    public function edit()
    {
        //当前记录信息
        $categoryAds=db('categoryAd')->find(input('id'));
        if(request()->isPost()){
            $data=input('post.');
            if($data['position']=='B' || $data['position']=='C'){
                $cas=db('CategoryAd')->where(array('position'=>$data['position'],'category_id'=>$data['category_id']))->select();
                if($cas){
                    $this->error('当前位置只能添加一条记录！');
                }
            }
            // $data['link_url'];  http://
            if($data['link_url'] && stripos($data['link_url'],'http://') === false){
                $data['link_url']='http://'.$data['link_url'];
            }
            //处理图片上传
            if($_FILES['img_src']['tmp_name']){
                //如果有原图请删除
                if($categoryAds['img_src']){
                    $caImg=IMG_UPLOADS.$categoryAds['img_src'];
                    if(file_exists($caImg)){
                        @unlink($caImg);
                    }
                }
                $data['img_src']=$this->upload();
            }
            //验证
   //       $validate = validate('link');
   //       if(!$validate->check($data)){
            //     $this->error($validate->getError());
            // }
            $add=db('CategoryAd')->update($data);
            if($add){
                $this->success('修改关联图片成功！','lst');
            }else{
                $this->error('修改关联图片失败！');
            }
            return;
        }
        //获取所有的顶级分类
        $cateRes=model('Category')->where(array('pid'=>0))->select();
        $this->assign([
            'cateRes'=>$cateRes,
            'categoryAds'=>$categoryAds,
            ]);
        return view();
    }

    public function del($id)
    {
        $caObj=db('CategoryAd');
        $cas=$caObj->field('img_src')->find($id);
        if($cas['img_src']){
            $caImg=IMG_UPLOADS.$cas['img_src'];
            if(file_exists($caImg)){
                @unlink($caImg);
            }
        }
    	$del=$caObj->delete($id);
    	if($del){
			$this->success('删除关联图片成功！','lst');
		}else{
			$this->error('删除关联图片失败！');
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
            return $info->getSaveName();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
            die;
        }
    }
}


}