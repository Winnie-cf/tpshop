<?php
namespace app\admin\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        return view();
    }


    //清空缓存
    public function clearCache(){
        if(cache(NUll)){
            $this->success('缓存清除成功！');
        }else{
            $this->error('缓存清除失败！');
        }
    }

    public function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }
        $url='http://www.san315.com/securitycode/check';
        $param=array();
        $param=[
        	'code'=>'111',
        	'ip'=>'127.0.0.1'
        ];
        
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        
        // return $data;
        dump($data);
    }

}
