<?php
/**
 * 模块配置文件
 * wangbaoqing@imooly.com
 * 2014-07-15
 */
require_once('db.config.php');
require_once('host.config.php');

return array(
	/**
	 * 数据库配置信息
	 * 支持多数据库配置
	 */
	'DB_CONFIG' => $dbconfig,

	//mongodb配置信息
	'MONGO'     => $mongo,

	//HOST
	'HOST' => $HOST,
);