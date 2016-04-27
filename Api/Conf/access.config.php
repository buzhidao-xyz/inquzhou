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
	),
);