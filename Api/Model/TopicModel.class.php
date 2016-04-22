<?php
/**
 * 用户模型
 * wbq@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Model;

class TopicModel extends CommonModel
{
	//专题map
	private $_topic_map = array(
		1 => array(
			'id'     => 1,
			'name'   => 'bikestation',
			'itemfields' => array(
				'itemid'  => 'itemid',
				'name'    => 'stationcap',
				'address' => 'address',
				'lat'     => 'point_y',
				'lng'     => 'point_x',
			),
			'fields' => array(
				
			),
		),
		2 => array(
			'id'     => 2,
			'name'   => 'park',
			'itemfields' => array(
				'itemid'  => 'itemid',
				'name'    => 'caption',
				'address' => 'address',
				'lat'     => 'point_y',
				'lng'     => 'point_x',
			),
		),
		3 => array(
			'id'     => 3,
			'name'   => 'gasstation',
			'itemfields' => array(
				'itemid'  => 'itemid',
				'name'    => 'name',
				'address' => 'address',
				'lat'     => 'point_y',
				'lng'     => 'point_x',
			),
		),
	);

	//初始化
	public function __construct()
	{
		parent::__construct();
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
	public function getTopicitem($topicid=null, $lat=null, $lng=null, $distance=null, $start=0, $length=9999)
	{
		if (!$topicid) return false;

		$topicmap = $this->_topic_map[$topicid];
		if (!is_array($topicmap)||empty($topicmap)) return array('total'=>0, 'data'=>array());

		$table = 'qz_topic_'.$topicmap['name'];
		$ttable = 'SELECT *, ACOS(SIN(('.$lat.' * 3.1415) / 180) * SIN((point_y * 3.1415) / 180) + COS(('.$lat.' * 3.1415) / 180) * COS((point_y * 3.1415) / 180) * COS(('.$lng.' * 3.1415) / 180 - (point_x * 3.1415) / 180)) * 6371 as distance FROM `'.$table.'`';

		$sql = 'SELECT COUNT(*) AS TC FROM ('.$ttable.') TTABLE WHERE TTABLE.distance<'.$distance.' LIMIT 1';
		$total = M($table)->query($sql);
		$total = $total[0]['TC'];

		$sql = 'SELECT * FROM ('.$ttable.') TTABLE WHERE TTABLE.distance<'.$distance.' ORDER BY TTABLE.distance ASC LIMIT '.$start.','.$length;
		$datas = M($table)->query($sql);

		$data = array();
		if (is_array($datas)&&!empty($datas)) {
			foreach ($datas as $d) {
				$data[] = array(
					'itemid'  => $d[$topicmap['itemfields']['itemid']],
					'name'    => $d[$topicmap['itemfields']['name']],
					'address' => $d[$topicmap['itemfields']['address']],
					'lat'     => $d[$topicmap['itemfields']['lat']],
					'lng'     => $d[$topicmap['itemfields']['lng']],
				);
			}
		}

		return array('total'=>$total, 'data'=>$data);
	}
}