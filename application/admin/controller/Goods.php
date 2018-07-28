<?php
namespace app\admin\controller;
use think\Controller;
use Catetree\Catetree;
class Goods extends Controller
{
    public function lst()
    {
        $join = [
            ['category c','g.category_id=c.id'],
            ['brand b','g.brand_id=b.id','LEFT'],
            ['type t','g.type_id=t.id','LEFT'],
            ['product p','g.id=p.goods_id','LEFT'],
        ];
    	$goodsRes=db('goods')->alias('g')->field('g.*,c.cate_name,b.brand_name,t.type_name,SUM(p.goods_number) gn')->join($join)->group('g.id')->order('g.id DESC')->paginate(6);
    	$this->assign([
    		'goodsRes'=>$goodsRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
            // dump($data); die;
    		//验证
            // dump($_FILES);die;
    		$validate = validate('goods');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$add=model('goods')->save($data);
    		if($add){
    			$this->success('添加商品成功！','lst');
    		}else{
    			$this->error('添加商品失败！');
    		}
    		return;
    	}
        // 会员级别数据
        $mlRes=db('memberLevel')->field('id,level_name')->select();
        // 获取类型
        $typeRes=db('type')->select();
        // 品牌分类
        $brandRes=db('brand')->field('id,brand_name')->select();
        //商品推荐位
        $goodsRecposRes=db('recpos')->where('rec_type','=',1)->select();
        // 商品分类
        $Category=new Catetree();
        $CategoryObj=db('Category');
        $CategoryRes=$CategoryObj->order('sort DESC')->select();
        $CategoryRes=$Category->Catetree($CategoryRes);
        $this->assign([
            'mlRes'=>$mlRes,
            'typeRes'=>$typeRes,
            'brandRes'=>$brandRes,
            'CategoryRes'=>$CategoryRes,
            'goodsRecposRes'=>$goodsRecposRes,
            ]);
        return view();
    }

    public function edit()
    {
        if(request()->isPost()){
            $data=input('post.');
            // dump($data); die;
            //验证
            // dump($_FILES);die;
            // $validate = validate('goods');
            // if(!$validate->check($data)){
            //     $this->error($validate->getError());
            // }
            $update=model('goods')->update($data);
            if($update){
                $this->success('修改商品成功！','lst');
            }else{
                $this->error('修改商品失败！');
            }
            return;
        }
        // 商品id
        $goodsId=input('id');
        // 会员级别数据
        $mlRes=db('memberLevel')->field('id,level_name')->select();
        // 获取类型
        $typeRes=db('type')->select();
        // 品牌分类
        $brandRes=db('brand')->field('id,brand_name')->select();
        // 会员价格
        $_mpRes=db('memberPrice')->where('goods_id','=',$goodsId)->select();
        $mpRes=array();
        foreach ($_mpRes as $k => $v) {
            $mpRes[$v['mlevel_id']]=$v;
        }
        // 商品相册
        $gphotoRes=db('goods_photo')->where('goods_id','=',$goodsId)->select();
        // 商品分类
        $Category=new Catetree();
        // 查询当前商品基本信息
        $goods=db('goods')->find($goodsId);
        // 查询当前商品类型所有属性信息
        $attrRes=db('attr')->where('type_id','=',$goods['type_id'])->select();
        // 查询当前商品拥有的商品属性goods_attr
        $_gattrRes=db('goods_attr')->where('goods_id','=',$goodsId)->select();
        //商品推荐位
        $goodsRecposRes=db('recpos')->where('rec_type','=',1)->select();
        // 当前商品相关推荐位
        $_myGoodsRecposRes=db('rec_item')->where(array('value_type'=>1,'value_id'=>$goodsId))->select();
        //改写二维数组为一维数组
        $myGoodsRecposRes=array();
        foreach ($_myGoodsRecposRes as $k => $v) {
            $myGoodsRecposRes[]=$v['recpos_id'];
        }
        // 更改二维数组结构为三维数组
        $gattrRes=array();
        foreach ($_gattrRes as $k => $v) {
            $gattrRes[$v['attr_id']][]=$v;
        }
        // dump($gattrRes); die;
        // 获取无限级分类信息
        $CategoryObj=db('Category');
        $CategoryRes=$CategoryObj->order('sort DESC')->select();
        $CategoryRes=$Category->Catetree($CategoryRes);
        $this->assign([
            'mlRes'=>$mlRes,
            'typeRes'=>$typeRes,
            'brandRes'=>$brandRes,
            'CategoryRes'=>$CategoryRes,
            'goods'=>$goods,
            'mpRes'=>$mpRes,
            'gphotoRes'=>$gphotoRes,
            'attrRes'=>$attrRes,
            'gattrRes'=>$gattrRes,
            'goodsRecposRes'=>$goodsRecposRes,
            'myGoodsRecposRes'=>$myGoodsRecposRes,
            ]);
        return view();
    }

    public function del($id)
    {
    	$del=model('goods')->destroy($id);
    	if($del){
			$this->success('删除商品成功！','lst');
		}else{
			$this->error('删除商品失败！');
		}
    }

    // 库存
    public function product($id){
        if(request()->isPost()){
            db('product')->where('goods_id','=',$id)->delete();
            $data=input('post.');
            $goodsAttr=$data['goods_attr'];
            $goodsNum=$data['goods_num'];
            $product=db('product');
            foreach ($goodsNum as $k => $v) {
                $strArr=array();
                foreach ($goodsAttr as $k1 => $v1) {
                    if(intval($v1[$k])<=0){
                        continue 2;
                    }
                    $strArr[]=$v1[$k];
                }
                sort($strArr);
                $strArr=implode(',', $strArr);
                $product->insert([
                    'goods_id'=>$id,
                    'goods_number'=>$v,
                    'goods_attr'=>$strArr
                    ]);
            }
            $this->success('添加库存成功！');
            return;
        }
        $_radioAttrRes=db('goods_attr')->alias('g')->field('g.id,g.attr_id,g.attr_value,a.attr_name')->join("attr a",'g.attr_id=a.id')->where(array('g.goods_id'=>$id,'a.attr_type'=>1))->select();
        $radioAttrRes=array();
        // 数组格式重组
        foreach ($_radioAttrRes as $k => $v) {
            $radioAttrRes[$v['attr_name']][]=$v;
        }
        // 获取商品的库存信息
        $goodsProRes=db('product')->where('goods_id','=',$id)->select();
        $this->assign([
            'radioAttrRes'=>$radioAttrRes,
            'goodsProRes'=>$goodsProRes,
            ]);
        return view();
    }

    // 异步删除商品相册图片

    public function ajaxdelpic($id){
        $gphoto=db('goods_photo');
        $gphotos=$gphoto->find($id);
        $ogPhoto=IMG_UPLOADS.$gphotos['og_photo'];
        $bigPhoto=IMG_UPLOADS.$gphotos['big_photo'];
        $midPhoto=IMG_UPLOADS.$gphotos['mid_photo'];
        $smPhoto=IMG_UPLOADS.$gphotos['sm_photo'];
        @unlink($ogPhoto);
        @unlink($bigPhoto);
        @unlink($midPhoto);
        @unlink($smPhoto);
        $del=$gphoto->delete($id);
        if($del){
            echo 1;
        }else{
            echo 2;
        }
    }





}