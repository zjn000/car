<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * 通用基类控制器
 */
class PublicBaseController extends BaseController{
	/**
	 * 初始化方法
	 */
	public function _initialize(){
		parent::_initialize();
		/*
		$auth=new \Think\Auth();
		$rule_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		
		check_login() === false && $this->error('您没有登录',U('Home/Index/index'));
					
		$result=$auth->check($rule_name,$_SESSION['user']['id']);
		if(!$result){
			$this->error('您没有权限访问');
		}*/
	}




}

