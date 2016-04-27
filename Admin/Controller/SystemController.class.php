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
		$this->display();
	}

	//图层设置-保存
	public function layersave()
	{
		
	}
}