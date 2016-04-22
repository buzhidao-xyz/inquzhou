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
}