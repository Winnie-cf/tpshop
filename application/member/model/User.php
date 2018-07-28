<?php
namespace app\member\model;
use think\Model;
class User extends Model
{
    // protected $field=true;
    public function login($data,$type=0,$backAct='#'){
        $userData=array();
        $userData['username']=trim($data['username']);
        $userData['password']=md5($data['password']);
        //验证用户名或邮箱或手机号是否存在
        $users=db('user')->where(array('username'=>$userData['username']))->whereOr(array('email'=>$userData['username']))->whereOr(array('mobile_phone'=>$userData['username']))->find();
        // dump($users); die;
        if($users){
            if($users['password'] == $userData['password']){
                session('uid',$users['id']);
                session('username',$users['username']);
                //写入会员等级及折扣率
                $points=$users['points'];
                $memberLevel=db('member_level')->where('bom_point','<=',$points)->where('top_point','>=',$points)->find();
                session('level_id',$memberLevel['id']);//等级id
                session('level_rate',$memberLevel['rate']);//等级折扣率
                //写入cookie
                if(isset($data['remember'])){
                    $aMonth=30*24*60*60;
                    $username=encryption($users['username'],0);
                    $password=encryption($data['password'],0);
                    cookie('username', $username, $aMonth, '/');
                    cookie('password', $password, $aMonth, '/');
                }
                $arr=[
                    'error'=>0,
                    'message'=>"",
                    'url'=>$backAct
                ];
                if($type == 1){
                    return $arr;
                }else{
                    return json($arr);
                }
            }else{
               $arr=[
                'error'=>1,
                'message'=>"<i class='iconfont icon-minus-sign'></i>用户名或者密码错误",
                'url'=>'',
                ];
                if($type == 1){
                    return $arr;
                }else{
                    return json($arr);
                }
            }
        }else{
            $arr=[
            'error'=>1,
            'message'=>"<i class='iconfont icon-minus-sign'></i>用户名或者密码错误",
            'url'=>'',
            ];
            if($type == 1){
                return $arr;
            }else{
                return json($arr);
            }
        }
    }

    public function logout(){
        session(NUll);
        cookie('username',null);
        cookie('password',null);
    }


}