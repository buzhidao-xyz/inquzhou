<?php
/**
 * 分享模型
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Model;

class ShareModel extends CommonModel
{
	/**
	 * 初始化
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 保存分享结果日志
	 */
	public function shareLogSave($data=array())
	{
		if (!is_array($data)||empty($data)) return false;

		$return = Mongo('sharelog',null,$this->_app_config)->insert($data);

		return $return['ok'];
	}

	/**
	 * 获取分享内容
	 */
	public function getShareInfo($object=null)
	{
		if (!$object) return false;

		$result = Mongo('sharetmp',null,$this->_app_config)->findOne(array(
			'type' => $object
		));

		$result = array_merge($result,array(
			'docid' => $result['_id']->{'$id'}
		));
		return $result;
	}
}