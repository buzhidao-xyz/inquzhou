<?php
/**
 * 专题模型
 * wbq@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Model;

class TopicModel extends CommonModel
{
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

	//编辑专题信息
	public function savetopic($topicid=null, $data=array())
	{
		if (!$topicid || !is_array($data)||empty($data)) return false;

		$result = M('topic')->where(array('topicid'=>$topicid))->save($data);

		return $result ? true : false;
	}

	//获取专题点
	public function getTopicitem($topicid=null, $itemid=null, $keywords=null, $start=0, $length=9999)
	{
		if (!isset($this->topicmap[$topicid])) return false;

		$topicmapinfo = $this->topicmap[$topicid];

		//解析fields
		$searchfields = array();
		foreach ($topicmapinfo['fields'] as $field) {
			if ($field['search']) $searchfields[] = $field['field'];
		}

		$where = array();
		if ($itemid) $where['itemid'] = is_array($itemid) ? array('in', $itemid) : $itemid;
		if ($keywords) {
			$where['_complex'] = array(
				'_logic'     => 'or',
			);
			foreach ($searchfields as $field) {
				$where['_complex'][$field] = array('like', '%'.$keywords.'%');
			}
		}

		$total = M('topic_'.$topicmapinfo['table'])->where($where)->count();
		$data = M('topic_'.$topicmapinfo['table'])->where($where)->order('itemid asc')->limit($start,$length)->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//保存专题点
	public function saveTopicitem($topicid=null, $itemid=null, $data=array())
	{
		if (!isset($this->topicmap[$topicid]) || !is_array($data) || empty($data)) return false;

		$topicmapinfo = $this->topicmap[$topicid];

		if ($itemid) {
			$result = M('topic_'.$topicmapinfo['table'])->where(array('itemid'=>$itemid))->save($data);
		} else {
			$result = M('topic_'.$topicmapinfo['table'])->add($data);
		}

		return $result ? true : false;
	}

	//获取专题点-图集
	public function getTopicitempics($topicid=null, $itemid=null)
	{
		if (!$topicid || !$itemid) return false;

		$data = M('topic_pics')->where(array('topicid'=>$topicid, 'itemid'=>$itemid))->select();

		return is_array($data) ? $data : array();
	}

	//保存专题点 - 图集
	public function saveTopicitempics($topicid=null, $itemid=null, $data=array())
	{
		if (!isset($this->topicmap[$topicid]) || !is_array($data) || empty($data)) return false;

		$topicmapinfo = $this->topicmap[$topicid];

		M('topic_pics')->where(array('topicid'=>$topicid, 'itemid'=>$itemid))->delete();

		$result = M('topic_pics')->addAll($data);

		return $result;
	}
}