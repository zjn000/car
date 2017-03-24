<?php
namespace Api\Controller;
use Common\Controller\PublicBaseController;
/**
 * 首页Controller
 */
class IndexController extends PublicBaseController{
	/**
	 * 首页
	 */
	public function index(){

		$this->display();		
	}

    /**
     * 退出
     */
    public function logout(){
        session('user',null);
        $this->success('退出成功、前往登录页面',U('Home/Index/index'));
    }
    

}