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
		//收藏地点
		'/fav/newfavplace' => array(
			'api'    => 'fav/newfavplace',
			'method' => 'post',
			'logon'  => false,
		),
		//收藏路线
		'/fav/newfavline' => array(
			'api'    => 'fav/newfavline',
			'method' => 'post',
			'logon'  => false,
		),
		//我的收藏地点
		'/fav/favplace' => array(
			'api'    => 'fav/favplace',
			'method' => 'get',
			'logon'  => false,
		),
		//我的收藏路线
		'/fav/favline' => array(
			'api'    => 'fav/favline',
			'method' => 'get',
			'logon'  => false,
		),
		//删除收藏
		'/fav/delfav' => array(
			'api'    => 'fav/delfav',
			'method' => 'post',
			'logon'  => false,
		),
		
		//新增标注地点
		'/place/newmarkplace' => array(
			'api'    => 'place/newmarkplace',
			'method' => 'post',
			'logon'  => false,
		),
		//新增地点
		'/place/newptplace' => array(
			'api'    => 'place/newptplace',
			'method' => 'post',
			'logon'  => false,
		),
		//新增纠错地点
		'/place/newpmplace' => array(
			'api'    => 'place/newpmplace',
			'method' => 'post',
			'logon'  => false,
		),
		//我的标注地点
		'/place/markplace' => array(
			'api'    => 'place/markplace',
			'method' => 'get',
			'logon'  => false,
		),
		//我的新增地点
		'/place/ptplace' => array(
			'api'    => 'place/ptplace',
			'method' => 'get',
			'logon'  => false,
		),
		//我的纠错地点
		'/place/pmplace' => array(
			'api'    => 'place/pmplace',
			'method' => 'get',
			'logon'  => false,
		),
		//删除地点
		'/place/delplace' => array(
			'api'    => 'place/delplace',
			'method' => 'post',
			'logon'  => false,
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

		//图层接口
		'/map/layer' => array(
			'api'    => 'map/layer',
			'method' => 'get',
			'logon'  => false,
		),
	),
);