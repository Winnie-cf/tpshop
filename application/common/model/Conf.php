<?php
namespace app\common\model;
use think\Model;
class Conf extends Model
{
	//获取所有配置项
   public function getConfs(){
      $_confRes=$this->field('ename,value')->select();
      $confRes=array();
      foreach ($_confRes as $k => $v) {
         $confRes[$v['ename']]=$v['value'];
      }
      return $confRes;
   }










}
