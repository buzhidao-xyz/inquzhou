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
    protected $_pagesize = 10;

	//默认距离 5000千米
	private $_distance = 5000;

	public function __construct()
	{
		parent::__construct();

		$this->topicmap = C('TOPIC');
	}

	//获取topicid
	private function _getTopicid($ck=false)
	{
		$topicid = mRequest('topicid');

		$ck&&!$topicid ? $this->apiReturn(1, '未知专题信息！') : null;

		return (int)$topicid;
	}

	//获取itemid
	private function _getItemid($ck=false)
	{
		$itemid = mRequest('itemid');

		$ck&&!$itemid ? $this->apiReturn(1, '未知专题点信息！') : null;

		return (int)$itemid;
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
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->apiReturn(1, '未知专题！');

		$lat = $this->_getLat(true);
		$lng = $this->_getLng(true);
		$distance = $this->_getDistance(true);

		//关键字
		$keyword = mRequest('keyword');

		//专题信息
		$apifields = array();
		foreach ($topicmapinfo['fields'] as $field) {
			if (isset($field['apifield'])&&$field['apifield']) {
				$apifields[$field['apifield']] = $field['field'];
			}
		}

		//获取专题点数据
		list($start, $length) = $this->mkPage();
		$datas = D('Topic')->getTopicitem($topicid, $lat, $lng, $distance, $start, $length, $keyword);
		$total = $datas['total'];
		$datalist = $datas['data'];

		$data = array();
		foreach ($datalist as $d) {
			$c = array(
				'topicid' => (int)$topicid,
				'itemid'  => (int)$d['itemid'],
			);
			foreach ($apifields as $apifield=>$field) {
				$value = (string)$d[$field];
				if (strtoupper($value)=="NULL") $value = "";
				$c[$apifield] = $value;
			}

			$data[] = $c;
		}

		$this->apiReturn(0, '', array(
			'total' => (int)$total,
			'data'  => $data
		));
	}

	//专题点详情
	public function topicitemprofile()
	{
		$topicid = $this->_getTopicid(true);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->apiReturn(1, '未知专题！');

		$itemid = $this->_getItemid(true);

		//获取专题点信息
		$topiciteminfo = D('Topic')->getTopicitemOne($topicid, $itemid);
		if (!is_array($topiciteminfo)||empty($topiciteminfo)) $this->apiReturn(1, '未知专题点！');

		//专题信息
		$apifields = array();
		$aloneshowfields = array();
		foreach ($topicmapinfo['fields'] as $field) {
			if (isset($field['apifield'])&&$field['apifield']) {
				$apifields[$field['apifield']] = $field['field'];
			}
			if (isset($field['aloneshow'])&&$field['aloneshow']) {
				$aloneshowfields[$field['aloneshow']] = $field['field'];
			}
		}

		$tel = isset($aloneshowfields['tel']) ? (string)$topiciteminfo[$aloneshowfields['tel']] : '';
		if (strtoupper($tel)=="NULL") $tel = "";
		$data = array(
			'topicid' => $topicid,
			'itemid'  => $itemid,
			'name'    => $topiciteminfo[$apifields['name']],
			'address' => $topiciteminfo[$apifields['address']],
			'lat'     => (string)$topiciteminfo[$apifields['lat']],
			'lng'     => (string)$topiciteminfo[$apifields['lng']],
			'tel'     => $tel,
			'content' => array()
		);
		foreach ($topicmapinfo['fields'] as $field) {
			if (in_array($field['apifield'], array('name','address','lat','lng'))) continue;
			if (in_array($field['aloneshow'], array('tel'))) continue;

			$value = $topiciteminfo[$field['field']];
			if (strtoupper($value)=="NULL") $value = "";
			$data['content'][] = array(
				'title' => $field['name'],
				'value' => $value,
			);
		}

		//获取专题点图集
		$topicpics = M('topic_pics')->where(array('topicid'=>$topicid, 'itemid'=>$itemid))->select();
		$pics = array();
		foreach ($topicpics as $pic) {
			$pics[] = ImageURL($pic['pic']);
		}
		$data['pics'] = $pics;

		$this->apiReturn(0, '', $data);
	}
}