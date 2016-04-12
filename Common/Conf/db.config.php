<?php
/**
 * 数据库配置文件
 * 2014-12-24
 * wangbaoqing@imooly.com
 */

//默认数据库配置 mysql
$dbconfig = array(
	//默认配置
	'DEFAULT_CONFIG' => array(
		// 数据库类型
		'DB_TYPE'            => 'mysql',
		// 数据库HOST
		'DB_HOST'            => '192.168.10.6',
		// 数据库端口
		'DB_PORT'            => 3306,
		// 数据库名
		'DB_NAME'            => 'quzhou',
		// 用户名
		'DB_USER'            => 'dbuser',
		// 密码
		'DB_PWD'             => 'dbpass',
		// 表前缀
		'DB_PREFIX'          => 'qz_',
		// 字符集
		'DB_CHARSET'         => 'utf8',
		// 字段名小写
		'DB_CASE_LOWER'      => true,
	),
	// //第二个数据库配置
	// 'MD_CONFIG' => array(
	// 	// 数据库类型
	// 	'DB_TYPE'            => 'oracle',
	// 	// 数据库HOST
	// 	'DB_HOST'            => '192.168.10.82',
	// 	// 数据库端口
	// 	'DB_PORT'            => 1521,
	// 	// 数据库名
	// 	'DB_NAME'            => 'md',
	// 	// 用户名
	// 	'DB_USER'            => 'mb_md_user',
	// 	// 密码
	// 	'DB_PWD'             => 'mb_md_user',
	// 	// 表前缀
	// 	'DB_PREFIX'          => 'mb_',
	// 	// 字符集
	// 	'DB_CHARSET'         => 'utf8',
	// 	'DB_CASE_LOWER'      => true,
	// ),
);

//mongodb配置
$mongo = array(
	// //md库
	// 'DEFAULT_CONFIG' => array(
	// 	'username' => 'root',
	// 	'password' => '123456',
	// 	'hostname' => '192.168.10.55', //服务器地址 例：host1,host2,host3
	// 	'hostport' => '27017', //服务器端口 例：27017,27017,27017
	// 	'database' => 'md',
	// 	'options'   => array(
	// 		'replicaSet' => '', //如果是复本集模式，此处填写复本集名称
	// 	)
	// ),
	// //app库
	// 'APP_CONFIG' => array(
	// 	'username' => 'root',
	// 	'password' => '123456',
	// 	'hostname' => '192.168.10.55', //服务器地址 例：host1,host2,host3
	// 	'hostport' => '27017', //服务器端口 例：27017,27017,27017
	// 	'database' => 'app',
	// 	'options'   => array(
	// 		'replicaSet' => '', //如果是复本集模式，此处填写复本集名称
	// 	)
	// ),
);