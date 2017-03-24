<?php
namespace Api\Controller;
use Common\Controller\PublicBaseController;
/**
 * 合同管理Controller
 */
class ContractController extends PublicBaseController{
	/**
	 * 首页
	 */
	public function index(){
		
		$this->search();
	}

	
	public function search()
	{
		$contractModel = D('Contract');
		
		$data = I('get.');
		
		
		//判断显示局部数据还是全部
		if(empty($_SESSION['user']['data_range']))
		{
			$where['create_id'] = $_SESSION['user']['id'];
		}
		
		
		
		//商家名称
		if(!empty($data['b_name']) ){
			$where['b_name'] = array('LIKE','%'.$data['b_name'].'%');			
		}
		
		
		
		
		
		$list = get_page_data($contractModel,$where,'create_time desc');
		
		//获取销售部下的所有员工姓名组
		$userList = D('Users')->getUidsBySectorId(3);
			
		
		foreach ($list['data'] as $key=>$row)
		{
			$list['data'][$key]['nikename'] = $userList[$row['create_id']];
			$list['data'][$key]['create_time'] = $row['create_time'] > 0 ? date('Y-m-d',$row['create_time']) : '';
		}
		
		$this->assign('list',$list);
		$this->display('Contract/index');
	}
    
	
	/**
	 * 添加
	 */
	public function add(){
		
		
		if(IS_GET)
		{
			//合同类型
			$type = I('get.type');
			
			switch ($type)
			{
				case 1:
					$this->display('Contract/add_1');
					break;
				case 2:
					$this->display('Contract/add_2');
					break;
				case 3:
					$this->display('Contract/add_3');
					break;
				case 4:
					$this->display('Contract/add_4');
					break;
				case 5:
					$this->display('Contract/add_5');
					break;
				default:
					$this->display('Contract/add_list');
					break;
			}
			
		}
		
		
		if(IS_POST){
			$data=I('post.');
			
			//合同类型
			$type = $data['type'];
				
			//预充值金额对应赠送金额（抽点合同）
			$pre_charge_credit_amount = array(
					'500'	=> 0,
					'1000'	=> 100,
					'2000'	=> 300,
					'3000'	=> 800,
					'4000'	=> 1500,
					'5000'	=> 2500
			);
				
			//根据不同合同类型附加不同数据项
			switch ($type)
			{
				//抽点新
				case 1:
						
					//保证金=终端机数*2000
					$data['bail'] = $data['terminals_num']*2000;
						
					//赠送金额
					$data['credit_amount'] = $pre_charge_credit_amount[$data['service_pre_charge']];
						
					//总额=预充值金额+保证金
					$data['total'] = $data['service_pre_charge']+$data['bail'];
						
					//商家类别对应抽点
					$data['b_category_point'] = C('MERCHANT_CATEGORIES_CONFIG')[$data['b_category']]['point'];
						
						
					break;
					//抽点老
				case 2:
						
					//该合同类型终端机数默认为1
					$data['terminals_num'] = 1;
						
					//保证金=终端机数*2000
					$data['bail'] = $data['terminals_num']*2000;
			
					//赠送金额
					$data['credit_amount'] = $pre_charge_credit_amount[$data['service_pre_charge']];
			
					//总额=预充值金额+保证金
					$data['total'] = $data['service_pre_charge']+$data['bail'];
			
					//商家类别对应抽点
					$data['b_category_point'] = C('MERCHANT_CATEGORIES_CONFIG')[$data['b_category']]['point'];
						
					break;
						
					//套餐新
				case 3:
						
					//保证金=终端机数*2000
					$data['bail'] = $data['terminals_num']*2000;
						
					//套餐收费：1、全年套餐4888　2、半年套餐2688
					$package_charges=array(1=>4888,2=>2688);
					
					$data['package_charges'] = $package_charges[$data['service_items']];
						
					//总额 = 服务费
					$data['total'] = $data['service_charge'];
						
						
						
						
						
					break;
			
					//套餐老
				case 4:
					//该合同类型终端机数默认为1
					$data['terminals_num'] = 1;
			
					//保证金=终端机数*2000
					$data['bail'] = $data['terminals_num']*2000;
						
					//套餐收费：1、全年套餐4888　2、半年套餐2688
					$package_charges=array(1=>4888,2=>2688);
					$data['package_charges'] = $package_charges[$data['service_items']];
			
					//总额 = 服务费
					$data['total'] = $data['service_charge'];
						
						
					break;
						
						
					//品牌合作
				case 5:
					//保证金=终端机数*2000
					$data['bail'] = $data['terminals_num']*2000;
						
					//总额 = 品牌入驻费+广告费用
					$data['total'] = $data['entry_fee']+$data['ads_fee'];
						
						
					break;
						
				default:
					$this->error("合同类型错误",U('Api/Contract/index'));
					break;
			}
				
			
			//获取签名数据流赋值给一个变量
			$signature_pic = $data['signature_pic'];
			unset($data['signature_pic']);
			
			
			$contractModel = D('Contract');
				
			//事务开启
			$contractModel->startTrans();
			
			
			
			//添加成功时，返回添加id
			$result = $contractModel->addData($data);
				
				
			if($result){
			
				//根据合同类型对应协议编号前缀
				$protocol_number_Prefix = array(
						1 => 'Z-0754-BZJCD17021.0-',	//抽点新
						2 => 'Z-0754-BZJCD17011.0-',	//抽点老
						3 => 'Z-0754-BZJTC17021.0-',	//套餐新
						4 => 'Z-0754-BZJTC17011.0-',	//套餐老
						5 => ''		//品牌合作
				);
			
			
				//更新协议编号
				$updateData['protocol_number'] = $protocol_number_Prefix[$type].sprintf("%04d", $result);
			
				//获取图片数据流生成图片，并更新图片签名字段
				$updateData['signature_pic'] = base64_to_pic_upload($signature_pic,$result,"/Upload/contract/signature/".date('Y-m-d',time())."/");
				if($updateData['signature_pic'] === false)
				{
					//事务回滚
					$contractModel->rollback();
					$this->error('签名图片生成失败',U('Api/Contract/index'));
					return;
				}
			
			
				$rs = $contractModel->editData(array('id'=>$result),$updateData);
			
				if($rs === false)
				{
					//事务回滚
					$contractModel->rollback();
					$this->error('提交失败',U('Api/Contract/index'));
					return;
				}
			
			
				//事务提交
				$contractModel->commit();
			
				// 操作成功
				$this->success('提交成功',U('Api/Contract/index'));
			}else{
			
				//事务回滚
				$contractModel->rollback();
				$error_word=$contractModel->getError();
				// 操作失败
				$this->error($error_word,U('Api/Contract/index'));
			}
			
			
			
		}
	}
	
	
	/**
	 * 查看合同
	 */
	public function view_contract()
	{
		
		$id = I('get.id',0,'intval');
		$assign = M('Contract')->find($id);
		
		//print_r($assign);
		
		$this->assign('assign',$assign);
					
		//合同类型
		$type = $assign['type'];
		
		switch ($type)
		{
			case 1:
				$this->display('Contract/contract_review_1');
				break;
			case 2:
				$this->display('Contract/contract_review_2');
				break;
			case 3:
				$this->display('Contract/contract_review_3');
				break;
			case 4:
				$this->display('Contract/contract_review_4');
				break;
			case 5:
				$this->display('Contract/contract_review_5');
				break;
			default:
				$this->display('Contract/add_list');
				break;
		}
	}

}