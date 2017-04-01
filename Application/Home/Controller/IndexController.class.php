<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;


/**
 * 商城首页Controller
 */
class IndexController extends HomeBaseController{
	/**
	 * 首页
	 */
	public function index(){
		
		
		
		$productModel = D('Users');
		$data = $productModel->getList();
		
		
		
		//p($this->getLang());
		
		
		$this->assign('data',$data);
         $this->display();
	}
   
}

