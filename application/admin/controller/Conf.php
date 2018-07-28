<?php
namespace app\admin\controller;
use think\Controller;
class Conf extends Controller
{
    public function conflist(){
        $conf=db('conf');
        if(request()->isPost()){
            $data=input('post.');
            // 复选框空选问题
            $checkFields2D=$conf->field('ename')->where(array('form_type'=>'checkbox'))->select();
            // 改为一维数组
            $checkFields=array();
            if($checkFields2D){
                foreach ($checkFields2D as $k => $v) {
                   $checkFields[]=$v['ename'];
                }
            }
            // 所有发送的字段组成的数组
            $allFields=array();
            // 处理文字数据
            foreach ($data as $k => $v) {
                $allFields[]=$k;
                if(is_array($v)){
                    $value=implode(',', $v);
                    $conf->where(array('ename'=>$k))->update(['value'=>$value]);
                }else{
                    $conf->where(array('ename'=>$k))->update(['value'=>$v]);
                }
            }
            // 如果复选框未选中任何一个选项，则设置空
            foreach ($checkFields as $k => $v) {
                if(!in_array($v, $allFields)){
                    $conf->where(array('ename'=>$v))->update(['value'=>'']);
                }
            }
            // 处理图片数据
            // dump($_FILES);
            if($_FILES){
                foreach ($_FILES as $k => $v) {
                    if($v['tmp_name']){
                        $imgs=$conf->field('value')->where(array('ename'=>$k))->find();
                        if($imgs['value']){
                            $oimg=IMG_UPLOADS.$imgs['value'];
                            if(file_exists($oimg)){
                                @unlink($oimg);
                            }
                        }
                        $imgSrc=$this->upload($k);
                        $conf->where(array('ename'=>$k))->update(['value'=>$imgSrc]);
                    }
                }
            }
            // dump($data); die;
            $this->success('配置成功！');
        }
        $ShopConfRes=$conf->where(array('conf_type'=>1))->order('sort desc')->select();
        $GoodsConfRes=$conf->where(array('conf_type'=>2))->order('sort desc')->select();
        $this->assign([
            'ShopConfRes'=>$ShopConfRes,
            'GoodsConfRes'=>$GoodsConfRes,
            ]);
        return view();
    }

    public function lst()
    {
        $conf=db('conf');
        if(request()->isPost()){
            $data=input('post.');
            foreach ($data['sort'] as $k => $v) {
                $conf->where('id','=',$k)->update(['sort'=>$v]);
            }
            $this->success('排序成功！');
        }
    	$confRes=$conf->order('sort DESC')->paginate(6);
    	$this->assign([
    		'confRes'=>$confRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
            //如果是多选，替换中文“，”
            if($data['form_type']=='radio' || $data['form_type']=='select' || $data['form_type']=='checkbox'){
                $data['values']=str_replace('，', ',', $data['values']);
                $data['value']=str_replace('，', ',', $data['value']);
            }
    		//验证
    		$validate = validate('conf');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$add=db('conf')->insert($data);
    		if($add){
    			$this->success('添加配置成功！','lst');
    		}else{
    			$this->error('添加配置失败！');
    		}
    		return;
    	}
        return view();
    }

    public function edit()
    {
    	if(request()->isPost()){
    		$data=input('post.');
            //如果是多选，替换中文“，”
            if($data['form_type']=='radio' || $data['form_type']=='select' || $data['form_type']=='checkbox'){
                $data['values']=str_replace('，', ',', $data['values']);
                $data['value']=str_replace('，', ',', $data['value']);
            }
    		//验证
    		$validate = validate('conf');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$save=db('conf')->update($data);
    		if($save !== false){
    			$this->success('修改配置成功！','lst');
    		}else{
    			$this->error('修改配置失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$confs=db('conf')->find($id);
    	$this->assign([
    		'confs'=>$confs,
    		]);
        return view();
    }

    public function del($id)
    {
    	$del=db('conf')->delete($id);
    	if($del){
			$this->success('删除配置成功！','lst');
		}else{
			$this->error('删除配置失败！');
		}
    }

    //上传图片
    public function upload($imgName){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($imgName);
        
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