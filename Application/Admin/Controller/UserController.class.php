<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台首页控制器
 */
class UserController extends AdminBaseController{

	/**
	 * 用户列表
	 */
	public function index(){
		$word=I('get.word','');
		if (empty($word)) {
			$map=array();
		}else{
			$map=array(
				'username'=>$word
				);
		}
		$assign=D('Users')->getAdminPage($map,'register_time desc');
		$this->assign($assign);
		$this->display();
	}

	/**
	 * 修改密码
	 */
	public function edit_pass()
	{

		if(IS_POST){
			$data=I('post.');
				
			//旧密码	
			$old_password = D('Users')->field('password')->where(array('id'=>$_SESSION['user']['id']))->select();
			$old_password = $old_password[0]['password'];
				
			//原密码
			$old_pass = md5($data['old_pass']);
			//新密码
			$new_pass = $data['new_pass'];
			//确认密码
			$password = $data['password'];
									
			if($old_pass != $old_password)
			{
				$this->error('原密码输入错误');
			} 
			
			if($new_pass != $password)
			{
				$this->error('两次输入的密码不一致');
			}
			
			if(D('Users')->editData(array('id'=>$_SESSION['user']['id']),array('password'=>$password)) === false)
			{
				$this->error('修改失败');
			}
			
			session('user',null);
			$this->success('修改成功,请手动刷新界面',U('Admin/Index/index'));
	
		}else{
				
			$this->display();
		}
	
	
	
	}


}
