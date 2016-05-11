<?php
/**
 * 收藏模型
 * wbq@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Model;

class FavModel extends CommonModel
{
	//初始化
	public function __construct()
	{
		parent::__construct();
	}

	//新增收藏地点-保存
	public function savefavplace($data=array())
	{
		if (!is_array($data) || empty($data)) return false;

		$favid = M('favplace')->add($data);

		return $favid;
	}

	//新增收藏路线-保存
	public function savefavline($data=array())
	{
		if (!is_array($data) || empty($data)) return false;

		$favid = M('favline')->add($data);

		return $favid;
	}

	//获取我的收藏-地点
	public function getFavplace($placeid=null, $userid=null, $start=0, $length=9999)
	{
		$where = array('isdelete'=>0);
		if ($placeid) $where['placeid'] = $placeid;
		if ($userid) $where['userid'] = $userid;

		$total = M('favplace')->where($where)->count();
		$data = M('favplace')->where($where)->order('favtime desc')->limit($start,$length)->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取我的收藏-路线
	public function getFavline($lineid=null, $userid=null, $start=0, $length=9999)
	{
		$where = array('isdelete'=>0);
		if ($lineid) $where['lineid'] = $lineid;
		if ($userid) $where['userid'] = $userid;

		$total = M('favline')->where($where)->count();
		$data = M('favline')->where($where)->order('favtime desc')->limit($start,$length)->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}
}