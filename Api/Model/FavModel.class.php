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

		$result = M('favplace')->add($data);

		return $result ? true : false;
	}

	//新增收藏路线-保存
	public function savefavline($data=array())
	{
		if (!is_array($data) || empty($data)) return false;

		$result = M('favline')->add($data);

		return $result ? true : false;
	}
}