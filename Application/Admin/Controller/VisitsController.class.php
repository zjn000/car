<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 拜访管理
 */
class VisitsController extends AdminBaseController{
    /**
     * 拜访列表
     */
    public function index(){
    	$this->search();   
    }
    
    public function search()
    {
    	$data = I('get.');
    	
    	$visitsModel = D('Visits');
    	
    	//查询字符串
    	$whereStr = '';
    	//分页跳转的时候保证查询条件
    	$where_parameter = array();
    	
    	
    	//判断显示局部数据还是全部
    	if(empty($_SESSION['user']['data_range']))
    	{
    		$whereStr .= " and v.create_id=".$_SESSION['user']['id'];
    		$where_parameter['create_id'] = $_SESSION['user']['id'];
    	}
    	else
    	{
    		if(!empty($data['create_id']))
    		{
    			$whereStr .= " and v.create_id=".$data['create_id'];
    			$where_parameter['create_id'] = $data['create_id'];
    		}
    	}
    	
    	
    	//商家名称
    	if(!empty($data['name'])){
    		$whereStr .= " and b.name like '%{$data['name']}%'";
    		$where_parameter['name'] = $data['name'];
    	}
    	//拜访内容
    	if(!empty($data['type'])){
    		$whereStr .= " and v.type={$data['type']}";
    		$where_parameter['type'] = $data['type'];
    	}
    	//拜访状态
    	if(!empty($data['status'])){
    		$whereStr .= " and v.status={$data['status']}";
    		$where_parameter['status'] = $data['status'];
    	}
    	
    	 
    	//只有开始日期
    	if(!empty($data['start_date']) && empty($data['end_date'])){
    		
    		$whereStr .= " and v.create_time>=".strtotime($data['start_date']);
    		$where_parameter['start_date'] = $data['start_date'];
    	}
    	//只有结束日期
    	if( empty($data['start_date']) && !empty($data['end_date'])){
    		$whereStr .= " and v.create_time<=".strtotime($data['end_date']);
    		$where_parameter['end_date'] = $data['end_date'];
    	}
    	//开始日期-结束日期
    	if(!empty($data['start_date']) && !empty($data['end_date']))
    	{
    		$where_parameter['start_date'] = $data['start_date'];
    		$where_parameter['end_date'] = $data['end_date'];
    		$whereStr .= " and v.create_time between ".strtotime($data['start_date']).' and '.strtotime($data['end_date']);
    	}
    	 
    	
    	$count = $visitsModel->field('v.*,b.name,b.address,b.principal,b.phone')->alias('v')->join('__BUSINESS__ b ON v.business_id=b.id'.$whereStr)->count();
    	 
    	$page = new_page($count,10,$where_parameter);
    	
    	
    	
    	//获取列表数据
    	$list = $visitsModel->field('v.*,b.name,b.address,b.principal,b.phone')->alias('v')->join('__BUSINESS__ b ON v.business_id=b.id'.$whereStr)->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
    	
    	//分页链接
    	$show = $page->show();
    	
    	//获取销售部下的所有员工姓名组
    	$userList = D('Users')->getUidsBySectorId(3);
    	//获取配置数据
    	$visitsTypeList = C('VISITS_TYPE_CONFIG');
    	//当前时间
    	$day = time();
    	
    	
    	foreach ($list as $key=>$row)
    	{
    		$list[$key]['status_name'] = $row['status'] == 1 ? '未完成':'已完成';
    		$list[$key]['create_time'] = $row['create_time'] > 0 ? date('Y-m-d H:i:s',$row['create_time']) : '';
    		$list[$key]['modify_time'] = $row['modify_time'] > 0 ? date('Y-m-d H:i:s',$row['modify_time']) : '';
    		$list[$key]['type'] = $visitsTypeList[$row['type']];
    		$list[$key]['nikename'] = $userList[$row['create_id']];
    		$list[$key]['accompany_id'] = $userList[$row['accompany_id']];
    		
    		//判断完成拜访按钮显示：1显示；2不显示
    		$list[$key]['is_confirm'] = strtotime(date('Y-m-d',$row['create_time']).' 23:59:59') < $day ? 2 : 1;
    		
    	} 
    	
    	$this->assign('empty',"<tr><td colspan='15'><span class='empty'>暂时没有数据</span></td></tr>"); //数据集为空时
    	$this->assign('userList',$userList); //赋值活动负责人集合
    	$this->assign('page',$show);// 赋值分页输出
    	$this->assign('list',$list);// 赋值数据集
    	$this->display('Visits/index');
    }
    
    
    
    
    
    /**
     * 添加
     */
    public function add(){
    	if(IS_POST){
    		$data=I('post.');
    		
    		//上传图片
    		$img = post_upload('/Upload/image/');
    		$data['picture'] = $img['name'];   	
    		
    		$result = D('Visits')->addData($data);
    		
    		if($result){
    			// 操作成功
    			$this->success('添加成功',U('Admin/Business/index'));
    		}else{
    			$error_word=D('Visits')->getError();
    			// 操作失败
    			$this->error($error_word);
    		}
    	}else{
    		$this->display();
    	}
    }
    
    /**
     * 编辑
     */
    public function edit(){
    	
    	if(IS_POST){	
    		
    		$data=I('post.');
    		
    		$map = array(
    			'id'=>$data['id']
    		);
    		
    		$data['status'] = 2;
    		
    		//当前时间
    		$day = time();
    		
    		$ct = D('Visits')->where($map)->getField('create_time');
    		
    		if(strtotime(date('Y-m-d',$ct).' 23:59:59') < $day)
    		{
    			$this->error("超出当日限期，无法完成");
    			return;
    		}
    		
    		$data['modify_time']=$day;
    		
    		$result = D('Visits')->editData($map,$data);
    		
    		if($result){
    			
    			//添加拜访结果后更新商户表最近拜访时间
    			M('business')->where(array('id'=>$data['business_id']))->save(array('visitors_time'=>$day));
    			
    			// 操作成功
    			$this->success('编辑成功',U('Admin/Visits/index'));
    		}else{
    			$error_word=D('Visits')->getError();
    			// 操作失败
    			$this->error($error_word);
    		}
    		
    	}
    }
    
    
    /**
     * 导出数据
     */
    public function excel_out()
    {
    
    	$data = I('get.');
    
    	$visitsModel = D('Visits');
    	
    	//查询字符串
    	$whereStr = '';
    	
    	//判断显示局部数据还是全部
    	if(empty($_SESSION['user']['data_range']))
    	{
    		$whereStr .= " and v.create_id=".$_SESSION['user']['id'];
    	}
    	else
    	{
    		if(!empty($data['create_id']))
    		{
    			$whereStr .= " and v.create_id=".$data['create_id'];
    		}
    	}
    	
    	
    	//商家名称
    	if(!empty($data['name'])){
    		$whereStr .= " and b.name like '%{$data['name']}%'";
    	}
    	//拜访内容
    	if(!empty($data['type'])){
    		$whereStr .= " and v.type={$data['type']}";
    	}
    	//拜访状态
    	if(!empty($data['status'])){
    		$whereStr .= " and v.status={$data['status']}";
    	}
    	
    	 
    	//只有开始日期
    	if(!empty($data['start_date']) && empty($data['end_date'])){
    		
    		$whereStr .= " and v.create_time>=".strtotime($data['start_date']);
    	}
    	//只有结束日期
    	if( empty($data['start_date']) && !empty($data['end_date'])){
    		$whereStr .= " and v.create_time<=".strtotime($data['end_date']);
    	}
    	//开始日期-结束日期
    	if(!empty($data['start_date']) && !empty($data['end_date']))
    	{
    		$whereStr .= " and v.create_time between ".strtotime($data['start_date']).' and '.strtotime($data['end_date']);
    	}
    	 
    	
    	//获取列表数据
    	$list = $visitsModel->field('b.name,b.address,b.principal,b.phone,v.create_id,v.type,v.create_time,v.modify_time,v.status,v.gps_positioning,v.visit_results')->alias('v')->join('__BUSINESS__ b ON v.business_id=b.id'.$whereStr)->order('v.create_time desc')->select();
    	
    	
    	//获取销售部下的所有员工姓名组
    	$userList = D('Users')->getUidsBySectorId(3);
    	//获取配置数据
    	$visitsTypeList = C('VISITS_TYPE_CONFIG');
    	
    	
    	foreach ($list as $key=>$row)
    	{
    		$list[$key]['status'] = $row['status'] == 1 ? '未完成':'已完成';
    		$list[$key]['create_time'] = $row['create_time'] > 0 ? date('Y-m-d H:i:s',$row['create_time']) : '';
    		$list[$key]['modify_time'] = $row['modify_time'] > 0 ? date('Y-m-d H:i:s',$row['modify_time']) : '';
    		$list[$key]['type'] = $visitsTypeList[$row['type']];
    		$list[$key]['create_id'] = $userList[$row['create_id']];
    	} 
    	
    	 
    
    	$arrHead = array(
    			'name' => '商家名称',
    			'address' => '商家地址',
    			'principal' => '联系人',
    			'phone' => '联系电话',
    			'create_id' => '负责BD',
    			'type' => '拜访内容',
    			'create_time' => '拜访开始时间',
    			'modify_time' => '拜访结束时间',
    			'status' => '拜访状态',
    			'gps_positioning' => 'GPS定位',
    			'visit_results' => '拜访结果'
    	);
    
    
    	array_unshift($list,$arrHead);
    
    	create_xls($list);
    }
    
    
    
    /**
     * 添加陪访
     */
    public function accompany()
    {
    	
    		$data=I('get.');
    	
    		$map = array(
    			'id'=>$data['id']
    		);
    	
    		$data['accompany_id'] = get_uid();
    	
    		$result = D('Visits')->editData($map,$data);
    	
    		if($result){
    			// 操作成功
    			$this->success('添加成功',U('Admin/Visits/index'));
    		}else{
    			$error_word=D('Visits')->getError();
    			// 操作失败
    			$this->error($error_word);
    		}
    	
    	
    }
    
    
    /**
     * 评论
     */
    public function comment()
    {
    	if(IS_POST){
    	
    		$data=I('post.');
    	
    		$map = array(
    			'id'=>$data['id']
    		);
    	
    		$result = D('Visits')->editData($map,$data);
    	
    		if($result){
    			// 操作成功
    			$this->success('编辑成功',U('Admin/Visits/index'));
    		}else{
    			$error_word=D('Visits')->getError();
    			// 操作失败
    			$this->error($error_word);
    		}
    	
    	}
    }
    
    
    
    
    
    
    
    
    
    
    
}