<?php
namespace app\member\controller;
use app\index\controller\Base;
class User extends Base
{
	
    public function index(){
    	$this->assign([
    		'show_right'=>1,
    		]);
        return view();
    }

    public function logout(){
    	model('User')->logout();
    	$this->success('退出成功！',url('member/Account/login'));
    }
}
