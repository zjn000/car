<?php
namespace Api\Controller;
use Common\Controller\PublicBaseController;
/**
 * 拜访管理Controller
 */
class VisitsController extends PublicBaseController{
	/**
	 * 首页
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
    	}
    	
    	
    	
    	//商家名称
    	if(!empty($data['name'])){
    		$whereStr .= " and b.name like '%{$data['name']}%'";
    		$where_parameter['name'] = $data['name'];
    	}
    	
    	
    	$count = $visitsModel->field('v.id,v.status,v.visit_results,v.business_id,v.type,v.create_time,v.modify_time,b.name,b.address,b.principal')->alias('v')->join('__BUSINESS__ b ON v.business_id=b.id'.$whereStr)->count();
    	 
    	$page = new_page($count,10,$where_parameter);
    	
    	
    	
    	//获取列表数据
    	$list = $visitsModel->field('v.id,v.status,v.create_id,v.visit_results,v.business_id,v.type,v.create_time,v.modify_time,b.name,b.address,b.principal')->alias('v')->join('__BUSINESS__ b ON v.business_id=b.id'.$whereStr)->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
    	
    	//分页链接
    	$show = $page->show();
    	
    	
    	//当前时间
    	$day = time();
    	
    	$visitsTypeList = C('VISITS_TYPE_CONFIG');
    	//获取销售部下的所有员工姓名组
    	$userList = D('Users')->getUidsBySectorId(3);
    	foreach ($list as $key=>$row)
    	{    		
    		$list[$key]['create_time'] = $row['create_time'] > 0 ? date('Y-m-d H:i:s',$row['create_time']) : '';
    		$list[$key]['modify_time'] = $row['modify_time'] > 0 ? date('Y-m-d H:i:s',$row['modify_time']) : '';
    		$list[$key]['type'] = $visitsTypeList[$row['type']];
    		$list[$key]['nikename'] = $userList[$row['create_id']];
    		//判断完成拜访按钮显示：1显示；2不显示
    		$list[$key]['is_confirm'] = strtotime(date('Y-m-d',$row['create_time']).' 23:59:59') < $day ? 2 : 1;
    	} 
    	    	
    	
    	$this->assign('page',$show);// 赋值分页输出
    	$this->assign('list',$list);// 赋值数据集
    	$this->display('Visits/index');
    }
    
    
    /**
     * webuploader 上传文件
     */
    public function ajax_upload(){
    	// 根据自己的业务调整上传路径、允许的格式、文件大小
    	ajax_upload('/Upload/image/');
    }
    
    
    /**
     * 添加
     */
    public function add(){
    	if(IS_POST){
    		$data=I('post.');
    		
    		//empty($data['picture']) && $this->error('图片未成功上传',U('Api/Business/index'));
    		
    		$result = D('Visits')->addData($data);
    		
    		if($result){
    			// 操作成功
    			$this->success('添加成功',U('Api/Visits/index'));
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
    			$this->success('编辑成功',U('Api/Visits/index'));
    		}else{
    			$error_word=D('Visits')->getError();
    			// 操作失败
    			$this->error($error_word);
    		}
    		
    	}
    }
	
}