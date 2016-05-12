<?php
/**
 * 访问信息配置文件
 * wbq@xlh-tech.com
 * 2016-04-19
 */

return array(
	//API前缀
	'APIPRE'  => '/',

	//API列表
	'APILIST' => array(
		//用户注册
		'/user/regist' => array(
			'api'    => 'user/regist',
			'method' => 'post',
			'logon'  => false,
		),
		//用户登录
		'/user/login' => array(
			'api'    => 'user/login',
			'method' => 'post',
			'logon'  => false,
		),
		//用户退出
		'/user/logout' => array(
			'api'    => 'user/logout',
			'method' => 'post',
			'logon'  => false,
		),
		//修改用户资料
		'/user/setuserinfo' => array(
			'api'    => 'user/setuserinfo',
			'method' => 'post',
			'logon'  => true,
		),
		//修改密码
		'/user/setpasswd' => array(
			'api'    => 'user/setpasswd',
			'method' => 'post',
			'logon'  => true,
		),
		//获取用户信息
		'/user/userinfo' => array(
			'api'    => 'user/userinfo',
			'method' => 'get',
			'logon'  => true,
		),
		//找回密码
		'/user/rebackpasswd' => array(
			'api'    => 'user/rebackpasswd',
			'method' => 'post',
			'logon'  => false
		),
		//用户设备
		'/user/device' => array(
			'api'    => 'user/device',
			'method' => 'post',
			'logon'  => false
		),
		//意见反馈
		'/user/lvword' => array(
			'api'    => 'user/lvword',
			'method' => 'post',
			'logon'  => true
		),

		//收藏地点
		'/fav/newfavplace' => array(
			'api'    => 'fav/newfavplace',
			'method' => 'post',
			'logon'  => true,
		),
		//收藏路线
		'/fav/newfavline' => array(
			'api'    => 'fav/newfavline',
			'method' => 'post',
			'logon'  => true,
		),
		//我的收藏地点
		'/fav/favplace' => array(
			'api'    => 'fav/favplace',
			'method' => 'get',
			'logon'  => true,
		),
		//我的收藏路线
		'/fav/favline' => array(
			'api'    => 'fav/favline',
			'method' => 'get',
			'logon'  => true,
		),
		//删除收藏
		'/fav/delfav' => array(
			'api'    => 'fav/delfav',
			'method' => 'post',
			'logon'  => true,
		),
		
		//新增标注地点
		'/place/newmarkplace' => array(
			'api'    => 'place/newmarkplace',
			'method' => 'post',
			'logon'  => true,
		),
		//新增地点
		'/place/newptplace' => array(
			'api'    => 'place/newptplace',
			'method' => 'post',
			'logon'  => true,
		),
		//新增纠错地点
		'/place/newpmplace' => array(
			'api'    => 'place/newpmplace',
			'method' => 'post',
			'logon'  => true,
		),
		//我的标注地点
		'/place/markplace' => array(
			'api'    => 'place/markplace',
			'method' => 'get',
			'logon'  => true,
		),
		//我的新增地点
		'/place/ptplace' => array(
			'api'    => 'place/ptplace',
			'method' => 'get',
			'logon'  => true,
		),
		//我的纠错地点
		'/place/pmplace' => array(
			'api'    => 'place/pmplace',
			'method' => 'get',
			'logon'  => true,
		),
		//删除地点
		'/place/delplace' => array(
			'api'    => 'place/delplace',
			'method' => 'post',
			'logon'  => true,
		),

		//专题列表
		'/topic/topiclist' => array(
			'api'    => 'topic/topiclist',
			'method' => 'get',
			'logon'  => false,
		),
		//专题点列表
		'/topic/topicitemlist' => array(
			'api'    => 'topic/topicitemlist',
			'method' => 'get',
			'logon'  => false,
		),
		//专题点详情
		'/topic/topicitemprofile' => array(
			'api'    => 'topic/topicitemprofile',
			'method' => 'get',
			'logon'  => false,
		),

		//离线地图检测接口
		'/map/offline' => array(
			'api'    => 'map/offline',
			'method' => 'get',
			'logon'  => false,
		),
		//图层接口
		'/map/layer' => array(
			'api'    => 'map/layer',
			'method' => 'get',
			'logon'  => false,
		),

		//发送短信验证码
		'/user/sendvcode' => array(
			'api'    => 'user/sendvcode',
			'method' => 'post',
			'logon'  => false,
		),
		//验证短信验证码
		'/user/checkvcode' => array(
			'api'    => 'user/checkvcode',
			'method' => 'post',
			'logon'  => false,
		),

		//检测新版本
		'/appi/ckversion' => array(
			'api'    => 'appi/ckversion',
			'method' => 'get',
			'logon'  => false,
		),
	),
);