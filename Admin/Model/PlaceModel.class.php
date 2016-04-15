<?php
/**
 * 地点模型
 * wbq@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Model;

class PlaceModel extends CommonModel
{
	public function __construct()
	{
		parent::__construct();
	}

	//获取标注点
	public function getMarkplace($userid=null, $keywords=null, $start=0, $length=9999, $orderway='desc')
	{
		$where = array();
		if ($userid) $where['a.userid'] = is_array($userid) ? array('in', $userid) : $userid;
		if ($keywords) $where['_complex'] = array(
			'_logic'  => 'or',
			'a.title'   => array('like', '%'.$keywords.'%'),
			'a.address' => array('like', '%'.$keywords.'%'),
		);

		$total = M('markplace')->alias('a')->where($where)->count();
		$data = M('markplace')->alias('a')
							  ->field('a.*, b.username')
							  ->join(' __USER__ b on a.userid=b.userid ')
							  ->where($where)
							  ->order('marktime '.$orderway)
							  ->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取新增地点
	public function getPtplace($userid=null, $keywords=null, $start=0, $length=9999)
	{
		$where = array();
		if ($userid) $where['a.userid'] = is_array($userid) ? array('in', $userid) : $userid;
		if ($keywords) $where['_complex'] = array(
			'_logic'  => 'or',
			'a.title'   => array('like', '%'.$keywords.'%'),
			'a.address' => array('like', '%'.$keywords.'%'),
		);

		$total = M('ptplace')->alias('a')->where($where)->count();
		$data = M('ptplace')->alias('a')
							->field('a.*, b.username')
							->join(' __USER__ b on a.userid=b.userid ')
							->where($where)
							->order('pttime desc')
							->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取纠错地点
	public function getPmplace($userid=null, $keywords=null, $start=0, $length=9999)
	{
		$where = array();
		if ($userid) $where['a.userid'] = is_array($userid) ? array('in', $userid) : $userid;
		if ($keywords) $where['_complex'] = array(
			'_logic'  => 'or',
			'a.title'   => array('like', '%'.$keywords.'%'),
			'a.address' => array('like', '%'.$keywords.'%'),
		);

		$total = M('pmplace')->alias('a')->where($where)->count();
		$data = M('pmplace')->alias('a')
							->field('a.*, b.username')
							->join(' __USER__ b on a.userid=b.userid ')
							->where($where)
							->order('pmtime desc')
							->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}
}