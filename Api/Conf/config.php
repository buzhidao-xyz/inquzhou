<?php
/**
 * 模块配置文件
 * wangbaoqing@imooly.com
 * 2014-07-15
 */

return array(
	//模板引擎启用Smarty
	'TMPL_ENGINE_TYPE'   => 'Smarty',
	//模板引擎要自动替换的字符串，必须是数组形式
	'TMPL_PARSE_STRING'  => array(
		'__UPLOAD__'     =>  __ROOT__.'/Upload/',
	),
	'TMPL_ENGINE_CONFIG' => array(
		'plugins_dir'    => './Application/Smarty/Plugins/',
	),

	//服务器域名
	'HTTP_HOST'          => 'http://'.$_SERVER['HTTP_HOST'],
	//系统 JS静态文件服务器
	'JS_FILE_SERVER'     => null,
	//系统 css静态文件服务器
	'CSS_FILE_SERVER'    => null,
	//系统 .svg .eot .ttf .woff字体文件服务器
	'FONT_FILE_SERVER'   => null,
	//系统 图片文件服务器
	'IMAGE_SERVER'       => null,

	//appid统一前缀
	'APPID_PREFIX'       => 'ml',

	//开启多语言切换
	'LANG_SWITCH_ON'     => true,
	//默认语言
	'LANG_DEFAULT'       => 'zh-cn',

	//SESSION配置信息
	'SESSION_TYPE'       => '',
	'SESSION_PREFIX'     => 'inquzhou',
	'VAR_SESSION_ID'     => 'sessionid',
	'SESSION_OPTIONS'    => array(
		'name'   => 'inquzhou',
		'expire' => 7200 //session默认过期时间 2小时=7200秒
	),
	//ticket过期时间 3600秒
	'SESSION_TICKET_EXPIRE' => 3600,

	//加载扩展配置文件 引用方式C('x.x')
	'LOAD_EXT_CONFIG' => array(
		//访问信息配置文件
		'ACCESS'  => 'access.config',
	),

	//图片地址
	'IMAGE_URL' => 'http://192.168.10.44:8070',
);