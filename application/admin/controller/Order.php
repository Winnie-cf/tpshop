<?php
namespace app\admin\controller;
use think\Controller;
class Order extends Controller
{
    public function lst()
    {
    	$orderRes=db('order')->alias('o')->join("user u","o.user_id = u.id")->field('o.id,o.out_trade_no,o.goods_total_price,o.pay_status,o.order_status,o.post_status,o.distribution,o.payment,o.name,o.phone,o.order_time,u.username')->order('o.id DESC')->paginate(10);
    	$this->assign([
    		'orderRes'=>$orderRes,
    		]);
        return view('list');
    }

    public function detail($id){
        $orderInfo = db('order')->alias('o')->join("user u","o.user_id = u.id")->field('o.*,u.username')->find($id);
        $this->assign('orderInfo',$orderInfo);
        return view('detail');
    }


    public function edit()
    {
    	if(request()->isPost()){
    		$data=input('post.');
            $userId = db('user')->where('username',$data['username'])->value('id');
            if($userId){
                $data['user_id'] = $userId;
            }
            $data['order_time'] = strtotime($data['order_time']);
            // dump($data); die;
    		//验证
    		$validate = validate('order');
    		if(!$validate->check($data)){
			    $this->error($validate->getError());
			}
    		$save=db('order')->strict(false)->update($data);
    		if($save !== false){
    			$this->success('修改订单成功！','lst');
    		}else{
    			$this->error('修改订单失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$orderInfo = db('order')->alias('o')->join("user u","o.user_id = u.id")->field('o.*,u.username')->find($id);
    	$this->assign([
    		'orderInfo'=>$orderInfo,
    		]);
        return view();
    }

    public function orderGoods($id){
        $orderGoodsRes = db('orderGoods')->where('order_id',$id)->paginate(10);
        $this->assign([
            'orderGoodsRes' => $orderGoodsRes
            ]);
        return view();
    }

    public function orderGoodsEdit(){
        if(request()->isPost()){
            $data = input('post.');
            $save = db('order_goods')->update($data);
            if($save !== false){
                $this->success('修改订单商品成功！');
            }else{
                $this->error('修改订单商品失败！');
            }
        }
        $orderGoodsId = input('id');
        $orderGoodsInfo = db('orderGoods')->find($orderGoodsId);
        $this->assign([
            'orderGoodsInfo'=>$orderGoodsInfo,
            ]);
        return view();
    }

    public function orderGoodsDel($id){
        $res = db('orderGoods')->delete($id);
        $this->success('删除订单商品成功！');
    }

    public function del($id)
    {
        $order=db('order');
    	$orders=$order->field('order_img')->find($id);
        $orderImg=IMG_UPLOADS.$orders['order_img'];
        if(file_exists($orderImg)){
            @unlink($orderImg);
        }
        $del=$order->delete($id);
    	if($del){
			$this->success('删除订单成功！','lst');
		}else{
			$this->error('删除订单失败！');
		}
    }

    //上传图片
    public function upload(){
    // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('order_img');
    
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