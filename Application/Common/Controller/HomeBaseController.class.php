<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * Home基类控制器
 */
class HomeBaseController extends BaseController{
	/**
	 * 初始化方法
	 */
	public function _initialize(){
		parent::_initialize();
		
		
		 
		
		
	}

	
	public function getLang()
	{
		switch ($_COOKIE['think_language'])
		{
			case "en-us":
				return false;
			case "zh-cn":
				return true;
			default:
				return true;
		}
	}
	



}