<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * admin 基类控制器
 */
class AdminBaseController extends BaseController{
	/**
	 * 初始化方法
	 */
	public function _initialize(){
		parent::_initialize();
		$auth=new \Think\Auth();
		$rule_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		
		//当权限节点为以下值时，不进行权限验证
		$filterPermissions = C('FILTER_PERMISSIONS');
		
		if(!in_array($rule_name,$filterPermissions))
		{
			check_login() === false && $this->error('您没有登录',U('Admin/Index/login'));
		
			if(!$_SESSION['user']['is_manager'])
			{
				$result=$auth->check($rule_name,$_SESSION['user']['id']);
				if(!$result){
					$this->error('您没有权限访问');
				}
			}
		}
		
	}

}