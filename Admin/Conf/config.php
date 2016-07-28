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
	
	//主题模板 - Beyond
	'DEFAULT_THEME' => 'Beyond',

	//加载扩展配置文件 引用方式C('x.x')
	'LOAD_EXT_CONFIG' => array(
	),

	//天地图API-Key
	'zjditu_key' => '5a9fe7a5ba70382279328a16fe516c4f',
);