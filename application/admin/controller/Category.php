<?php
namespace app\admin\controller;
use think\Controller;
use Catetree\Catetree;
class Category extends Controller
{
    public function lst()
    {
        $Category=new Catetree();
        $CategoryObj=db('Category');
        if(request()->isPost()){
            $data=input('post.');
            $Category->cateSort($data['sort'],$CategoryObj);
            $this->success('排序成功！',url('lst'));
        }
    	$CategoryRes=$CategoryObj->order('sort DESC')->select();
        $CategoryRes=$Category->Catetree($CategoryRes);
        $this->assign([
            'CategoryRes'=>$CategoryRes,
            ]);
        return view('list');
    }

    public function add()
    {
        $Category=new Catetree();
        $CategoryObj=model('Category');
    	if(request()->isPost()){
    		$data=input('post.');
            //处理图片上传
            if($_FILES['cate_img']['tmp_name']){
                $data['cate_img']=$this->upload();
            }
    		//验证
   //  		$validate = validate('Category');
   //  		if(!$validate->check($data)){
			//     $this->error($validate->getError());
			// }
    		$add=$CategoryObj->save($data);
    		if($add){
    			$this->success('添加分类成功！','lst');
    		}else{
    			$this->error('添加分类失败！');
    		}
    		return;
    	}
        //商品分类推荐位
        $categoryRecposRes=db('recpos')->where('rec_type','=',2)->select(); 
        $CategoryRes=$CategoryObj->order('sort DESC')->select();
        $CategoryRes=$Category->Catetree($CategoryRes);
        $this->assign([
            'CategoryRes'=>$CategoryRes,
            'categoryRecposRes'=>$categoryRecposRes,
            ]);
        return view();
    }

    public function edit()
    {
        $Category=new Catetree();
        $CategoryObj=model('Category');
    	if(request()->isPost()){
    		$data=input('post.');
             //处理图片上传
            if($_FILES['cate_img']['tmp_name']){
                $data['cate_img']=$this->upload();
                $categorys=$CategoryObj->field('cate_img')->find($data['id']);
                if($categorys['cate_img']){
                    $imgSrc=IMG_UPLOADS.$categorys['cate_img'];
                    if(file_exists($imgSrc)){
                        @unlink($imgSrc);
                    }
                }
            }
    		//验证
   //  		$validate = validate('Category');
   //  		if(!$validate->check($data)){
			//     $this->error($validate->getError());
			// }
    		$save=$CategoryObj->update($data);
    		if($save !== false){
    			$this->success('修改分类成功！','lst');
    		}else{
    			$this->error('修改分类失败！');
    		}
    		return;
    	}
        //商品分类推荐位
        $categoryRecposRes=db('recpos')->where('rec_type','=',2)->select(); 
        // 当前商品分类相关推荐位
        $_myCategoryRecposRes=db('rec_item')->where(array('value_type'=>2,'value_id'=>input('id')))->select();
        //改写二维数组为一维数组
        $myCategoryRecposRes=array();
        foreach ($_myCategoryRecposRes as $k => $v) {
            $myCategoryRecposRes[]=$v['recpos_id'];
        }
        $Categorys=$CategoryObj->find(input('id'));
    	$CategoryRes=$CategoryObj->order('sort DESC')->select();
        $CategoryRes=$Category->Catetree($CategoryRes);
        $this->assign([
            'CategoryRes'=>$CategoryRes,
            'Categorys'=>$Categorys,
            'categoryRecposRes'=>$categoryRecposRes,
            'myCategoryRecposRes'=>$myCategoryRecposRes,
            ]);
        return view();
    }

    public function del($id)
    {
        $Category=db('Category');
        $Catetree=new Catetree();
        $sonids=$Catetree->childrenids($id,$Category);
        $sonids[]=intval($id);
        //删除分类前判断该分类下的文章和文章相关缩略图
        // $article=db('article');
        // foreach ($sonids as $k => $v) {
        //     $artRes=$article->field('id,thumb')->where(array('Category_id'=>$v))->select();
        //     foreach ($artRes as $k1 => $v1) {
        //         $thumbSrc=IMG_UPLOADS.$v1['thumb'];
        //         if(file_exists($thumbSrc)){
        //             @unlink($thumbSrc);
        //         }
        //         $article->delete($v1['id']);
        //     }
        // }
        //删除栏目前，检查并删除当前栏目的推荐信息
        $recItem=db('recItem');
        foreach ($sonids as $k => $v) {
            $recItem->where(array('value_id'=>$v,'value_type'=>2))->delete();
        }
    	$del=$Category->delete($sonids);
    	if($del){
			$this->success('删除分类成功！','lst');
		}else{
			$this->error('删除分类失败！');
		}
    }

    //上传图片
    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('cate_img');
        
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