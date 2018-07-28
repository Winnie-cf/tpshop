<?php
namespace app\admin\controller;
use think\Controller;
use catetree\Catetree;
class Cate extends Controller
{
    public function lst()
    {
        $cate=new Catetree();
        $cateObj=db('cate');
        if(request()->isPost()){
            $data=input('post.');
            $cate->cateSort($data['sort'],$cateObj);
            $this->success('排序成功！',url('lst'));
        }
    	$cateRes=$cateObj->order('sort DESC')->select();
        $cateRes=$cate->catetree($cateRes);
        $this->assign([
            'cateRes'=>$cateRes,
            ]);
        return view('list');
    }

    public function add()
    {
        $cate=new Catetree();
        $cateObj=db('cate');
    	if(request()->isPost()){
    		$data=input('post.');
            //判断是否可以添加子分类
            if(in_array($data['pid'], ['1','3'])){
                $this->error('系统分类不能作为上级分类！');
            }
            //
            if($data['pid']==2){
                $data['cate_type']=3;
            }
            //
            $cateId=$cateObj->field('pid')->find($data['pid']);
            $cateId=$cateId['pid'];
            if($cateId==2){
                $this->error('此分类不能作为上级分类！');
            }
    		//验证
    		$validate = validate('Cate');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$add=$cateObj->insert($data);
    		if($add){
    			$this->success('添加分类成功！','lst');
    		}else{
    			$this->error('添加分类失败！');
    		}
    		return;
    	}
        $cateRes=$cateObj->order('sort DESC')->select();
        $cateRes=$cate->catetree($cateRes);
        $this->assign([
            'cateRes'=>$cateRes,
            ]);
        return view();
    }

    public function edit()
    {
        $cate=new Catetree();
        $cateObj=db('cate');
    	if(request()->isPost()){
    		$data=input('post.');
    		//验证
    		$validate = validate('Cate');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$save=$cateObj->update($data);
    		if($save !== false){
    			$this->success('修改分类成功！','lst');
    		}else{
    			$this->error('修改分类失败！');
    		}
    		return;
    	}
        $cates=$cateObj->find(input('id'));
    	$cateRes=$cateObj->order('sort DESC')->select();
        $cateRes=$cate->catetree($cateRes);
        $this->assign([
            'cateRes'=>$cateRes,
            'cates'=>$cates
            ]);
        return view();
    }

    public function del($id)
    {
        $cate=db('cate');
        $cateTree=new Catetree();
        $sonids=$cateTree->childrenids($id,$cate);
        $sonids[]=intval($id);
        $arrSys=[1,2,3];
        $arrRes=array_intersect($arrSys,$sonids);
        if($arrRes){
            $this->error('系统内置文章分类不允许删除！');
        }
        //删除分类前判断该分类下的文章和文章相关缩略图
        $article=db('article');
        foreach ($sonids as $k => $v) {
            $artRes=$article->field('id,thumb')->where(array('cate_id'=>$v))->select();
            foreach ($artRes as $k1 => $v1) {
                $thumbSrc=IMG_UPLOADS.$v1['thumb'];
                if(file_exists($thumbSrc)){
                    @unlink($thumbSrc);
                }
                $article->delete($v1['id']);
            }
        }
    	$del=$cate->delete($sonids);
    	if($del){
			$this->success('删除分类成功！','lst');
		}else{
			$this->error('删除分类失败！');
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