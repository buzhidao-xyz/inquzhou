<?php
/**
 * 地点API
 * wbq@xlh-tech.com
 * 2016-04-19
 */
namespace Api\Controller;

use Org\Net\Http;

class TopicController extends CommonController
{
    //分页配置
    protected $_page = 1;
    protected $_pagesize = 100000;

	//默认距离 5000千米
	private $_distance = 5000;

	public function __construct()
	{
		parent::__construct();
	}

	//获取topicid
	private function _getTopicid($ck=false)
	{
		$topicid = mRequest('topicid');

		$ck&&!$topicid ? $this->apiReturn(1, '未知专题信息！') : null;

		return (int)$topicid;
	}

	//获取distance
	private function _getDistance($ck=false)
	{
		$distance = mRequest('distance');
		!$distance ?$distance = $this->_distance : null;

		$ck&&!$distance ? $this->apiReturn(1, '距离信息为空！') : null;

		return (double)$distance;
	}

	public function index(){}

	//获取专题列表
	public function topiclist()
	{
		$topiclist = D('Topic')->getTopic();

		$data = array();
		foreach ($topiclist as $topic) {
			$data[] = array(
				'topicid' => (int)$topic['topicid'],
				'title'   => $topic['title'],
				'pic'     => ImageURL($topic['pic']),
				'author'  => $topic['author'],
				'desc'    => $topic['desc'],
			);
		}

		$this->apiReturn(0, '', array(
			'total' => count($topiclist),
			'data'  => $data
		));
	}

	//获取专题点列表
	public function topicitemlist()
	{
		$topicid = $this->_getTopicid(true);
		$lat = $this->_getLat(true);
		$lng = $this->_getLng(true);
		$distance = $this->_getDistance(true);

		//获取专题点数据
		list($start, $length) = $this->mkPage();
		$datas = D('Topic')->getTopicitem($topicid, $lat, $lng, $distance, $start, $length);
		$total = $datas['total'];
		$datalist = $datas['data'];

		$data = array();
		foreach ($datalist as $d) {
			$data[] = array(
				'topicid' => (int)$topicid,
				'itemid'  => (int)$d['itemid'],
				'name'    => (string)$d['name'],
				'address' => (string)$d['address'],
				'lat'     => (string)$d['lat'],
				'lng'     => (string)$d['lng'],
			);
		}

		$this->apiReturn(0, '', array(
			'total' => (int)$total,
			'data'  => $data
		));
	}
}