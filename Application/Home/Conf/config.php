<?php
return array(
//*************************************附加设置***********************************
    'TMPL_ACTION_ERROR'      => THINK_PATH . 'Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'    => THINK_PATH . 'Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
	'URL_HTML_SUFFIX'        => 'html',  // URL伪静态后缀设置

	'LANG_SWITCH_ON'  => true,    //开启多语言支持开关
	'DEFAULT_LANG'    => 'zh-cn',  // 默认语言
	'LANG_LIST'    => 'zh-cn,en-us', // 允许切换的语言列表 用逗号分隔
	'LANG_AUTO_DETECT'  => true,  // 自动侦测语言
	'VAR_LANGUAGE'	=>	'l',	//默认语言切换变量
	
	'LAYOUT_ON'=>true,
	'LAYOUT_NAME'=>'layout'
);
