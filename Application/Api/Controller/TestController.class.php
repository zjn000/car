<?php
namespace Api\Controller;
use Think\Controller;
/**
 * 首页Controller
 */
class TestController extends Controller{
	/**
	 * 首页
	 */
	public function index(){

		//$this->display();
		$businessModel = D('Business');
		
		
		$data = I('get.');
		
		$page = isset( $data['page'] ) ? intval($data['page']) : 1;
		$where=array();
		
		//商家名称
		if(!empty($data['name']) ){
			$where['name'] = array('LIKE','%'.$data['name'].'%');
		}
		
		$recordCount = $businessModel->where($where)->order('create_time desc')->count();
		$list = $businessModel->where($where)->order('create_time desc')->select();
		
		
		$rs = $this->arrSort($list,$recordCount, $page, 5);
		
		$result = array(
			'success'   => true,
			'result'    => $rs,
			'error_msg' => "获取成功！"
		);
	
		echo json_encode($result);
		exit;
	}

	
	/*
	 *
	 * 功能：将数组进行分页
	 * 参数：$arr			数组
	 * 		$recordCount	数组元素总数
	 * 		$page			当前页数
	 * 		$pageSize		显示条数
	 * 返回值：
	 * 		当页数大于总页数时，返回null,否则返回数组
	 *
	 */
	function arrSort( $arr, $recordCount, $page = 1, $pageSize = 5)
	{
		$start 		= ($page - 1) * $pageSize;			//起始元素指针
		$end 		= $start + $pageSize;				//结束元素指针
		$pageCount 	= ceil($recordCount/$pageSize);		//总页数
		$row 		= array();
		if($page > $pageCount)	//当页数大于总页数时，返回null
		{
			return null;
		}
	
		for($i = $start; $i<$end; $i++)
		{
			if(isset($arr[$i]))
			{
				$row[]= $arr[$i];
			}
		}
	
		return $row;
	}
	
	
	
	
    /**
     * 退出
     */
    public function logout(){
        session('user',null);
        $this->success('退出成功、前往登录页面',U('Home/Index/index'));
    }
    

}