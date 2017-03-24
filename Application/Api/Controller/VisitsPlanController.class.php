<?php
namespace Api\Controller;
use Common\Controller\PublicBaseController;
/**
 * 计划拜访Controller
 */
class VisitsPlanController extends PublicBaseController{
	/**
	 * 首页
	 */
	public function index(){
		
		$this->search();
	}

	
	public function search()
    {
    	   	   	
    	$data = I('get.');
    	 
    	$visitsPlanModel = D('VisitsPlan');
    	 
    	//查询字符串
    	$whereStr = '';
    	//分页跳转的时候保证查询条件
    	$where_parameter = array();
    	 
    	 
    	//判断显示局部数据还是全部
    	if(empty($_SESSION['user']['data_range']))
    	{
    		$whereStr .= " and vp.create_id=".$_SESSION['user']['id'];   		
    	}
    	 
    	 
    	//商家名称
    	if(!empty($data['name'])){
    		$whereStr .= " and b.name like '%{$data['name']}%'";
    		$where_parameter['name'] = $data['name'];
    	}
    	
    	 
    	$count = $visitsPlanModel->field('vp.id,vp.status,vp.create_id,vp.plan_time,vp.business_id,vp.type,vp.create_time,vp.modify_time,b.name,b.address,b.principal')->alias('vp')->join('__BUSINESS__ b ON vp.business_id=b.id'.$whereStr)->count();
    	
    	$page = new_page($count,10,$where_parameter);
    	 
    	//获取列表数据
    	$list = $visitsPlanModel->field('vp.id,vp.status,vp.create_id,vp.plan_time,vp.business_id,vp.type,vp.create_time,vp.modify_time,b.name,b.address,b.principal')->alias('vp')->join('__BUSINESS__ b ON vp.business_id=b.id'.$whereStr)->order('vp.create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
    	 
    	 
    	//分页链接
    	$show = $page->show();
    	 
    	//获取销售部下的所有员工姓名组
    	$userList = D('Users')->getUidsBySectorId(3);
    	//获取配置数据
    	$visitsTypeList = C('VISITS_TYPE_CONFIG');
    	 
    	foreach ($list as $key=>$row)
    	{
    		$list[$key]['plan_time'] = $row['plan_time'] > 0 ? date('Y-m-d H:i:s',$row['plan_time']) : '';
    		$list[$key]['modify_time'] = $row['modify_time'] > 0 ? date('Y-m-d H:i:s',$row['modify_time']) : '';
    		$list[$key]['type'] = $visitsTypeList[$row['type']];
    		$list[$key]['nikename'] = $userList[$row['create_id']];
    	}
    	 
    	$this->assign('empty',"<tr><td colspan='10'><span class='empty'>暂时没有数据</span></td></tr>"); //数据集为空时
    	
    	$this->assign('page',$show);// 赋值分页输出
    	$this->assign('list',$list);// 赋值数据集
    	$this->display('VisitsPlan/index');
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    }
   
    
    
    /**
     * 编辑
     */
    public function edit(){
    	
    	$data = I('get.');
    		
    	$map = array(
    		'id'=>$data['id']
    	);
    	
    	$status = D('VisitsPlan')->where($map)->getField('status');
    	
    	if(intval($status) > 1)
    	{
    		$this->error('已完成计划');
    		return;
    	}
    	
    	$data['status'] = 2;
    	
    	$result = D('VisitsPlan')->editData($map,$data);
    		
    	if($result){
    		// 操作成功
    		$this->success('计划完成',U('Api/VisitsPlan/index'));
    	}else{
    		$error_word=D('VisitsPlan')->getError();
    		// 操作失败
    		$this->error($error_word);
    	}
    }
	
}