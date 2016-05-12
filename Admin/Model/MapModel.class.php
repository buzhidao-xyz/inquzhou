<?php
/**
 * 地图模型
 * wbq@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Model;

class MapModel extends CommonModel
{
	public function __construct()
	{
		parent::__construct();
	}

	//获取用户设备信息
	public function getMap($mapid=null, $keywords=null, $start=0, $length=9999)
	{
		$where = array();
		if ($mapid) $where['mapid'] = $mapid;
		if ($keywords) $where['title'] = array('like', '%'.$keywords.'%');

		$total = M('map')->where($where)->count();
		$data = M('map')->where($where)->order('uploadtime desc')->limit($start,$length)->select();

		return array('total'=>$total, 'data'=>$data);
	}

	//获取地图信息 BYID
	public function getMapByID($mapid=null)
	{
		if (!$mapid) return false;

		$mapinfo = M('map')->where(array('mapid'=>$mapid))->find();

		return is_array($mapinfo) ? $mapinfo : array();
	}
}