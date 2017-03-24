<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台首页控制器
 */
class IndexController extends AdminBaseController{
	/**
	 * 首页
	 */
	public function index(){
		
		//判断是否已登录
		if(check_login())
		{
			// 分配菜单数据
			$nav_data = D('AdminNav')->getTreeData('level','order_number,id');
			$this->assign('data',$nav_data);
			$this->display();
		}
		else
		{
			$this->login();
		}

	}

	public function login()
	{
		if(IS_POST){
			// 做一个简单的登录 组合where数组条件
			$map=I('post.');
			$map['password']=md5($map['password']);
			$map['status'] = 1;
			$data=M('Users')->where($map)->find();
		
			if (empty($data)) {
				$this->error('账号或密码错误');
			}else{
				unset($data['password']);
				
				$_SESSION['user']=$data;
					
				if(is_mobile()){
					$this->success('登录成功',U('Api/Index/index'));
				}
				else
				{
					$this->success('登录成功、前往管理后台',U('index'));
				}
			}
		}else
		{
			//登录页
			$this->display('Index/login');
		}
	}
	
	/**
	 * 退出
	 */
	public function logout(){
		session('user',null);
		$this->success('退出成功、前往登录页面',U('Admin/Index/index'));
	}
	
}
