<?php
/**
 * 系统管理 业务
 * wangbaoqing@xlh-tech.com
 * 2016-04-18
 */
namespace Admin\Controller;

use Org\Util\Filter;

class SystemController extends CommonController
{
	//地图设置参数
	private $_mapconfig = array(
		'maplayer_road' => array('type'=>'int', 'key'=>'maplayer_road'),
	);

	public function __construct()
	{
		parent::__construct();
	}

	//获取configvalue
	private function _getConfigvalue($configtype=null, $configkey=null)
	{
		$configvalue = mRequest($configkey);
		$configvalue = D('System')->configvalueFormat($configtype, $configvalue);
		$this->assign($configkey, $configvalue);

		return $configvalue;
	}

	public function index(){}

	//图层设置
	public function layer()
	{
		//实时路况图层key
		$configkey = $this->_mapconfig['maplayer_road']['key'];

		$configinfo = D('System')->getSystemConfigByKey($configkey);

		$this->assign('configinfo', $configinfo);
		$this->display();
	}

	//图层设置-保存
	public function layersave()
	{
		//获取configvalue
		$configvalue = $this->_getConfigvalue($this->_mapconfig['maplayer_road']['type'], $this->_mapconfig['maplayer_road']['key']);
		
		$data = array(
			$this->_mapconfig['maplayer_road']['key'] => array(
				'configvalue' => $configvalue,
				'updatetime'  => TIMESTAMP,
			),
		);
		$result = D('System')->saveSystemConfig($data);
		if ($result) {
			$this->ajaxReturn(0, '保存成功！');
		} else {
			$this->ajaxReturn(1, '保存失败！');
		}
	}
}