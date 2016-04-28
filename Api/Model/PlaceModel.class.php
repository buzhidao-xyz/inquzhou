<?php
/**
 * 地点模型
 * wbq@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Model;

class PlaceModel extends CommonModel
{
	//初始化
	public function __construct()
	{
		parent::__construct();
	}

	//新增标注地点-保存
	public function savemarkplace($data=array())
	{
		if (!is_array($data) || empty($data)) return false;

		$result = M('markplace')->add($data);

		return $result ? true : false;
	}

	//新增地点-保存
	public function saveptplace($data=array())
	{
		if (!is_array($data) || empty($data)) return false;

		$result = M('ptplace')->add($data);

		return $result ? true : false;
	}

	//新增纠错-保存
	public function savepmplace($data=array())
	{
		if (!is_array($data) || empty($data)) return false;

		$result = M('pmplace')->add($data);

		return $result ? true : false;
	}

	//获取标注地点
	public function getMarkplace($markplaceid=null, $userid=null, $start=0, $length=9999)
	{
		$where = array('isdelete'=>0);
		if ($markplaceid) $where['markplaceid'] = $markplaceid;
		if ($userid) $where['userid'] = $userid;

		$total = M('markplace')->where($where)->count();
		$data = M('markplace')->where($where)->order('marktime desc')->limit($start,$length)->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取新增地点
	public function getPtplace($ptplaceid=null, $userid=null, $start=0, $length=9999)
	{
		$where = array('isdelete'=>0);
		if ($ptplaceid) $where['ptplaceid'] = $ptplaceid;
		if ($userid) $where['userid'] = $userid;

		$total = M('ptplace')->where($where)->count();
		$data = M('ptplace')->where($where)->order('pttime desc')->limit($start,$length)->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取纠错地点
	public function getPmplace($pmplaceid=null, $userid=null, $pmtype=null, $start=0, $length=9999)
	{
		$where = array('isdelete'=>0);
		if ($pmplaceid) $where['pmplaceid'] = $pmplaceid;
		if ($userid) $where['userid'] = $userid;
		if ($pmtype!==null) $where['pmtype'] = $pmtype;

		$total = M('pmplace')->where($where)->count();
		$data = M('pmplace')->where($where)->order('pmtime desc')->limit($start,$length)->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}
}