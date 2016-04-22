<?php
/**
 * 地点API
 * wbq@xlh-tech.com
 * 2016-04-19
 */
namespace Api\Controller;

use Org\Net\Http;

class PlaceController extends CommonController
{
	//纠错类型
	private $_pmtypelist = array(
		1 => array('id'=>1, 'name'=>'位置错误'),
		2 => array('id'=>2, 'name'=>'名称错误'),
		3 => array('id'=>3, 'name'=>'其它错误'),
	);

	public function __construct()
	{
		parent::__construct();
	}

	//获取名称
	private function _getTitle($ck=false)
	{
		$title = mRequest('title');

		$ck&&!$title ? $this->apiReturn(1, '未知名称！') : null;

		return $title;
	}

	//获取地址
	private function _getAddress($ck=false)
	{
		$address = mRequest('address');

		$ck&&!$address ? $this->apiReturn(1, '未知地址！') : null;

		return $address;
	}

	//获取描述说明
	private function _getDesc($ck=false)
	{
		$desc = mRequest('desc');

		$ck&&!$desc ? $this->apiReturn(1, '未知描述！') : null;

		return $desc;
	}

	//报告纠错
	private function _getPmtype($ck=false)
	{
		$pmtype = mRequest('pmtype');

		$ck&&(!$pmtype||!isset($this->_pmtypelist[$pmtype])) ? $this->apiReturn(1, '未知纠错类型！') : null;

		return (int)$pmtype;
	}

	public function index(){}

	//新增标注地点
	public function newmarkplace()
	{
		$title = $this->_getTitle(true);
		$address = $this->_getAddress(true);
		$desc = $this->_getDesc();
		$lat = $this->_getLat(true);
		$lng = $this->_getLng(true);

		$userid = $this->userinfo['userid'];

		$userid = 1;
		$data = array(
			'userid'   => $userid,
			'title'    => $title,
			'address'  => $address,
			'desc'     => $desc,
			'lat'      => $lat,
			'lng'      => $lng,
			'marktime' => TIMESTAMP,
		);
		$result = D('Place')->savemarkplace($data);

		if ($result) {
			$msg = '新增标注地点成功！';
			$result = array(
				'result' => 1,
			);
		} else {
			$msg = '新增标注地点失败！';
			$result = array(
				'result' => 0,
			);
		}
		$this->apiReturn(0, $msg, $result);
	}

	//新增地点
	public function newptplace()
	{
		$title = $this->_getTitle(true);
		$address = $this->_getAddress(true);
		$desc = $this->_getDesc();
		$lat = $this->_getLat(true);
		$lng = $this->_getLng(true);

		$userid = $this->userinfo['userid'];

		$userid = 1;
		$data = array(
			'userid'   => $userid,
			'title'    => $title,
			'address'  => $address,
			'desc'     => $desc,
			'lat'      => $lat,
			'lng'      => $lng,
			'pttime'   => TIMESTAMP,
		);
		$result = D('Place')->saveptplace($data);

		if ($result) {
			$msg = '新增地点成功！';
			$result = array(
				'result' => 1,
			);
		} else {
			$msg = '新增地点失败！';
			$result = array(
				'result' => 0,
			);
		}
		$this->apiReturn(0, $msg, $result);
	}

	//新增纠错
	public function newpmplace()
	{
		$pmtype = $this->_getPmtype(true);
		$address = $this->_getAddress(true);
		$desc = $this->_getDesc();
		$lat = $this->_getLat(true);
		$lng = $this->_getLng(true);

		$userid = $this->userinfo['userid'];

		$userid = 1;
		$data = array(
			'pmtype'   => $pmtype,
			'userid'   => $userid,
			'address'  => $address,
			'desc'     => $desc,
			'lat'      => $lat,
			'lng'      => $lng,
			'pmtime'   => TIMESTAMP,
		);
		$result = D('Place')->savepmplace($data);

		if ($result) {
			$msg = '新增纠错成功！';
			$result = array(
				'result' => 1,
			);
		} else {
			$msg = '新增纠错失败！';
			$result = array(
				'result' => 0,
			);
		}
		$this->apiReturn(0, $msg, $result);
	}
}