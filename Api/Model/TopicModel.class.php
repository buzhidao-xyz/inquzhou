<?php
/**
 * 用户模型
 * wbq@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Model;

class TopicModel extends CommonModel
{
	//初始化
	public function __construct()
	{
		parent::__construct();

		$this->topicmap = C('TOPIC');
	}

	//获取专题类型
	public function getTopic($topicid=null)
	{
		$where = array();
		if ($topicid) $where['topicid'] = $topicid;
		
		$data = M('topic')->where($where)->select();

		return is_array($data) ? $data : array();
	}

	//获取专题类型 通过topicid
	public function getTopicByID($topicid=null)
	{
		if (!$topicid) return false;

		$topicinfo = $this->getTopic($topicid);

		return !empty($topicinfo) ? current($topicinfo) : array();
	}

	//获取专题点列表
	public function getTopicitem($topicid=null, $lat=null, $lng=null, $distance=null, $start=0, $length=9999, $keyword=null)
	{
		if (!isset($this->topicmap[$topicid])) return false;

		$topicmapinfo = $this->topicmap[$topicid];

		$apifields = array();
		foreach ($topicmapinfo['fields'] as $field) {
			if (isset($field['apifield'])&&$field['apifield']) {
				$apifields[$field['apifield']] = $field['field'];
			}
		}

		$wherekeyword = '';
		if ($keyword) {
			$wherekeyword = ' AND (TTABLE.'.$apifields['name'].' LIKE \'%'.$keyword.'%\' OR TTABLE.'.$apifields['address'].' LIKE \'%'.$keyword.'%\') ';
		}

		$table = 'qz_topic_'.$topicmapinfo['table'];
		$ttable = 'SELECT *, ACOS(SIN(('.$lat.' * 3.1415) / 180) * SIN((point_y * 3.1415) / 180) + COS(('.$lat.' * 3.1415) / 180) * COS((point_y * 3.1415) / 180) * COS(('.$lng.' * 3.1415) / 180 - (point_x * 3.1415) / 180)) * 6371 as distance FROM `'.$table.'`';

		$sql = 'SELECT COUNT(*) AS TC FROM ('.$ttable.') TTABLE WHERE TTABLE.distance<'.$distance.' '.$wherekeyword.' LIMIT 1';
		$total = M($table)->query($sql);
		$total = $total[0]['TC'];

		$sql = 'SELECT * FROM ('.$ttable.') TTABLE WHERE TTABLE.distance<'.$distance.' '.$wherekeyword.' ORDER BY TTABLE.distance ASC LIMIT '.$start.','.$length;
		$data = M($table)->query($sql);

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取专题点信息 单条
	public function getTopicitemOne($topicid=null, $itemid=null)
	{
		if (!isset($this->topicmap[$topicid]) || !$itemid) return false;

		$topicmapinfo = $this->topicmap[$topicid];

		$topiciteminfo = M('topic_'.$topicmapinfo['table'])->where(array('itemid'=>$itemid))->find();

		return is_array($topiciteminfo) ? $topiciteminfo : array();
	}
}