<?php
/**
 * 用户信息配置文件
 * wangbaoqing@imooly.com
 * 2014-11-25
 */

return array(
	//人群角色列表
	'USERGROUP'   => array(
		1 => '学生帮',
		2 => '妈妈咪',
		3 => '职场达人',
		4 => '其他',
	),

	//用户角色 普通用户 代码
	'USERROLE_P1' => 'U000001',
	//用户角色 会员用户 代码
	'USERROLE_P2' => 'U000002',

	//如果不是魔力会员 电子会员卡提示信息
	'VIPCARD_SHOWMSG' => '您还不是魔力会员！',
	
	//会员费用配置 年/月
	'VIPFEE'      => array(
		'year'  => array(
			'type'   => 'year',
			'status' => 1,
			'name'   => '按年付费',
			'fee'    => 0.02,
		),
		'month' => array(
			'type'   => 'month',
			'status' => 0,
			'name'   => '按月付费',
			'fee'    => 0.01,
		)
	),

	//赠送会员活动
	'VIPACTIVITY' => array(
		'status' => 1, //是否启用 0禁用 1启用
		'type'   => 'month', //赠送类型 year:年 month:月
		'times'  => 3, //赠送时长 N年(月)
	),
);