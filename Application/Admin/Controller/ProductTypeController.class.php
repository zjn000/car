<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 商品类型管理
 */
class ProductTypeController extends AdminBaseController{
	/**
	 * 列表
	 */
	public function index(){
		
		$productTypeModel = D('ProductType');
		
		$data = get_page_data($productTypeModel,'','create_time desc',15);
		
		foreach ($data['data'] as $key=>$row)
		{
			$data['data'][$key]['status_name'] = $row['status'] == 1 ? '显示':'隐藏';
		}
		
		
		$this->assign('assign',$data);
		$this->display();
	}

	/**
	 * 添加
	 */
	public function add(){
		
		if(!D('ProductType')->addData($data))
		{
			$this->error('添加失败',U('Admin/ProductType/index'));
			return;
		}
		$this->success('添加成功',U('Admin/ProductType/index'));
	}

	/**
	 * 修改
	 */
	public function edit(){
		$data=I('post.');		
		if(!D('ProductType')->editData(array('id'=>$data['id']),$data))
		{
			$this->success('修改失败',U('Admin/ProductType/index'));
			return;
		}
		$this->success('修改成功',U('Admin/ProductType/index'));
	}

	/**
	 * 删除
	 */
	public function delete(){
		$result=D('ProductType')->deleteData(array('id'=>I('get.id')));
		if($result){
			$this->success('删除成功',U('Admin/ProductType/index'));
		}else{
			$this->error('删除失败',U('Admin/ProductType/index'));
		}
	}
}