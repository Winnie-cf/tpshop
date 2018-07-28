<?php
namespace app\member\controller;
use ChuanglanSmsHelper\ChuanglanSmsApi;
use PHPMailer\PHPMailer\PHPMailer;
use app\index\controller\Base;
class Account extends Base
{
    public function reg(){
        if(request()->isPost()){
            $data=input('post.');
            $validate = validate('User');
            if(!$validate->check($data)){
                 $this->error($validate->getError());
            }
            $userData=array();
            $userData['username']=trim($data['username']);
            $userData['password']=md5($data['password']);
            $userData['email']=$data['email'];
            $userData['mobile_phone']=$data['mobile_phone'];
            $userData['register_type']=$data['register_type'];
            $userData['register_time']=time();
            $add=db('user')->insert($userData);
            if($add){
                $loginRes=$this->login(1);
                if($loginRes['error'] == 0){
                    $this->success('注册成功！正在为您跳转...','member/User/index');
                }else{
                    $this->success('注册成功！正在为您跳转...','member/Account/login');
                }
            }else{
                $this->error('注册失败！');
            }
        }
        return view();
    }

    // type=0说明需要为客户端返回json对象，type=1说明要为服务端返回普通数组类型
    public function login($type=0){
        if(request()->isPost()){
            $data=input('post.');
            if(isset($data['back_act'])){
                $backAct = $data['back_act'];
            }else{
                $backAct = '';
            }
            return model('user')->login($data,$type,$backAct);
        }
        return view();
    }

    public function checkLogin(){
        $uid=session('uid');
        if($uid){
            $arr['error']=0;
            $arr['uid']=$uid;
            $arr['username']=session('username');
            return json($arr);
        }else{
            if(cookie('username') && cookie('password')){
                $data['username']=encryption(cookie('username'),1);
                $data['password']=encryption(cookie('password'),1);
                $loginRes=model('user')->login($data,1);
                if($loginRes['error'] == 0){
                    $arr['error']=0;
                    $arr['uid']=$uid;
                    $arr['username']=session('username');
                    return json($arr);
                }
            }
            $arr=array();
            $arr['error']=1;
            return json($arr);
        }
    }
    //type:0 代表注册场景  1：代表手机找回密码场景  2:向用户发送密码
    public function sendMsg($type=0,$password=0){
        $clapi  = new ChuanglanSmsApi();
        $code = mt_rand(100000,999999);
        $tipMsg='';
        if($password == 0){
            $tipMsg='您好，您的验证码是'. $code;
        }else{
            $tipMsg='您好，您的新密码是'. $password.' 请妥善保管。';
        }
        if($password == 0){
            $phoneNum=trim(input('phoneNum'));
        }else{
            $phoneNum=session('getPasswordPhoneNum');
        }
        $result = $clapi->sendSMS($phoneNum, $tipMsg);
         
        if(!is_null(json_decode($result))){
             
            $output=json_decode($result,true);
            if(isset($output['code'])  && $output['code']=='0'){
                if($type == 0){
                    session('mobileCode',$code);
                }else{
                    session('getPasswordCode',$code);
                    session('getPasswordPhoneNum',$phoneNum);
                }
                $msg=['status'=>0,'msg'=>'发送成功'];
                return json($msg);
            }else{
                $msg=['status'=>1,'msg'=>$output['errorMsg']];
                return json($msg);
            }
        }else{
                $msg=['status'=>2,'msg'=>'内部错误'];
                return json($msg);
        }
    }

    public function sendmail($email='',$password=''){
        if($email){
            $to=$email;
        }else{
            $to=input('email');
        }
        $title='PHP商城验证码';
        $code = mt_rand(100000,999999);
        $content='';
        if($password){
            $content='您的新密码是：'.$password.' ,请妥善保管。';
        }else{
            $content='您的验证码是：'.$code;
        }
        $mail = new PHPMailer();
        // 设置为要发邮件
        $mail->IsSMTP();
        // 是否允许发送HTML代码做为邮件的内容
        $mail->IsHTML(TRUE);
        $mail->CharSet='UTF-8';
        // 是否需要身份验证
        $mail->SMTPAuth=TRUE;
        /*  邮件服务器上的账号是什么 -> 到163注册一个账号即可 */
        $mail->From="tongpan0@163.com";
        $mail->FromName="PHP商城";
        $mail->Host="smtp.163.com";  //发送邮件的服务协议地址
        $mail->Username="tongpan0";
        $mail->Password="0";
        // 发邮件端口号默认25
        $mail->Port = 25;
        // 收件人
        $mail->AddAddress($to);
        // 邮件标题
        $mail->Subject=$title;
        // 邮件内容
        $mail->Body=$content;
        $sendRes=$mail->Send();
        if($sendRes){
            //记录邮件验证码
            session('emailCode',$code);
            $msg=['status'=>0,'msg'=>'发送成功'];
            return json($msg);
        }else{
            $msg=['status'=>1,'msg'=>'发送失败'];
            return json($msg);
        }
    }

    //验证用户名是否可以注册
    public function isRegistered(){
        if(request()->isAjax()){
            $username=input('username');
            $userInfo=db('user')->where(array('username'=>$username))->find();
            if($userInfo){
                return false;
            }else{
                return true;
            }
        }else{
            $this->error('非法请求！');
        }
    }

    //验证手机号是否可以注册
    public function checkPhone(){
        if(request()->isAjax()){
            $mobilePhone=input('mobile_phone');
            $userInfo=db('user')->where(array('mobile_phone'=>$mobilePhone))->find();
            if($userInfo){
                return false;
            }else{
                return true;
            }
        }else{
            $this->error('非法请求！');
        }
    }

    //验证邮箱是否可以注册
    public function checkEmail(){
        if(request()->isAjax()){
            $email=input('email');
            $userInfo=db('user')->where(array('email'=>$email))->find();
            if($userInfo){
                return false;
            }else{
                return true;
            }
        }else{
            $this->error('非法请求！');
        }
    }

    //异步验证邮箱验证码
    public function checkdEmailSendCode(){
        if(request()->isAjax()){
            $emailCode=input('send_code');
            if($emailCode == session('emailCode')){
                return true;
            }else{
                return false;
            }
        }else{
            $this->error('非法请求！');
        }
    }

    //异步验证手机短信
    public function codeNotice(){
        if(request()->isAjax()){
            $mobileCode=input('mobile_code');
            if($mobileCode == session('mobileCode')){
                return true;
            }else{
                return false;
            }
        }else{
            $this->error('非法请求！');
        }
    }

    //找回密码
    public function getPassword(){
        return view();
    }

    //验证手机号并发送短信
    public function checkSendMsg(){
        $data=input('post.');
        $phoneNum=trim($data['phoneNum']);
        if($phoneNum){
            $users=db('user')->where(array('mobile_phone'=>$phoneNum))->find();
            if($users){
                return $this->sendMsg(1);
            }else{
                $arr['msg']='用户不存在！';
                $arr['status']=1;
                return json($arr); 
            }
        }else{
            $arr['msg']='请填写手机号！';
            $arr['status']=1;
            return json($arr);
        }
    }

    //找回密码时候验证手机验证码是否正确
    public function checkPhoneCode(){
        $data=input('post.');
        $mobileCode=trim($data['mobile_code']);
        $sCode=session('getPasswordCode');
        $mobilePhone=session('getPasswordPhoneNum');
        if($sCode == $mobileCode){
            $password=mt_rand(100000,999999);
            $_password=md5($password);
            $update=db('user')->where(array('mobile_phone'=>$mobilePhone))->update(['password'=>$_password]);
            if($update){
                $this->sendMsg(2,$password);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    //通过用户名和邮箱找回密码
    public function sendPwdEmail(){
        $data=input('post.');
        $userData['username']=trim($data['user_name']);
        $userData['email']=trim($data['email']);
        //信息比对
        $users=db('user')->where(array('username'=>$userData['username']))->find();
        if($users){
            if($users['email'] == $userData['email']){
                $password=mt_rand(100000,999999);
                $_password=md5($password);
                $update=db('user')->where(array('username'=>$userData['username']))->update(['password'=>$_password]);
                if($update){
                    $_msg=$this->sendmail($userData['email'],$password);
                    $msg['status']=0;
                    $msg['msg']='修改密码成功！';
                }else{
                    $msg['status']=3;
                    $msg['msg']='修改密码失败！';
                }
            }else{
               $msg['status']=2;
               $msg['msg']='您填写的电子邮件地址错误，请重新输入！';
            }
        }else{
            $msg['status']=1;
            $msg['msg']='您填写的用户名不存在，请重新输入！';
        }
        $this->assign([
            'show_right'=>1,
            'status'=>$msg['status'],
            'msg'=>$msg['msg']
            ]);
        return view('index@common/tip_info');
    }

}
