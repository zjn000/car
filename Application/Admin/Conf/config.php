<?php
return array(
//*************************************附加设置***********************************
    'TMPL_ACTION_ERROR'      => THINK_PATH . 'Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'    => THINK_PATH . 'Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
    //过滤权限节点
	'FILTER_PERMISSIONS'	=> array(
		'Admin/Index/index',	//后台首页
		'Admin/Index/welcome',	//后台欢迎页
		'Admin/Index/login',	//登录		
		'Admin/Index/logout'	//退出		
    ),
	//***********************************SESSION设置**********************************
	'SESSION_OPTIONS'        => array(
			'name'               => 'ZJNADMIN',//设置session名
			'expire'             => 24*3600*15, //SESSION保存15天
			'use_trans_sid'      => 1,//跨页传递
			'use_only_cookies'   => 0,//是否只开启基于cookies的session的会话方式
	),
		
		
		
		
);
