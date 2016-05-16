<?php
/**
 * 地图模型
 * wbq@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Model;

class MapModel extends CommonModel
{
	//初始化
	public function __construct()
	{
		parent::__construct();
	}

	//获取最新离线地图包版本
	public function getMap()
	{
		$map = M('map')->order('mapid desc')->find();

		return is_array($map) ? $map : array();
	}

	//获取图层信息
	public function getLayer($layerid=null)
	{
		$where = array(
			'status' => 1,
		);
		if ($layerid) $where['layerid'] = $layerid;

		$data = M('layer')->where($where)->select();

		return is_array($data) ? $data : array();
	}
}