<?php
/**
 * 收藏模型
 * wbq@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Model;

class FavModel extends CommonModel
{
	public function __construct()
	{
		parent::__construct();
	}

	//获取收藏点
	public function getFavplace($userid=null, $keywords=null, $start=0, $length=9999)
	{
		$where = array();
		if ($userid) $where['a.userid'] = is_array($userid) ? array('in', $userid) : $userid;
		if ($keywords) $where['_complex'] = array(
			'_logic'  => 'or',
			'a.title'   => array('like', '%'.$keywords.'%'),
			'a.address' => array('like', '%'.$keywords.'%'),
		);

		$total = M('favplace')->alias('a')->field('a.*, b.username')->join(' __USER__ b on a.userid=b.userid ')->where($where)->count();
		$data = M('favplace')->alias('a')
							 ->field('a.*, b.username')
							 ->join(' __USER__ b on a.userid=b.userid ')
							 ->where($where)
							 ->order('favtime asc')
							 ->limit($start,$length)
							 ->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取收藏路线
	public function getFavline($userid=null, $keywords=null, $start=0, $length=9999)
	{
		$where = array();
		if ($userid) $where['a.userid'] = is_array($userid) ? array('in', $userid) : $userid;
		if ($keywords) $where['_complex'] = array(
			'_logic' => 'or',
			'a.sour' => array('like', '%'.$keywords.'%'),
			'a.dest' => array('like', '%'.$keywords.'%'),
		);

		$total = M('favline')->alias('a')->field('a.*, b.username')->join(' __USER__ b on a.userid=b.userid ')->where($where)->count();
		$data = M('favline')->alias('a')
							->field('a.*, b.username')
							->join(' __USER__ b on a.userid=b.userid ')
							->where($where)
							->order('favtime asc')
							->limit($start,$length)
							->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}
}