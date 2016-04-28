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

	//获取placeid
	private function _getPlaceid($ck=false)
	{
		$placeid = mRequest('placeid');

		$ck&&!$placeid ? $this->apiReturn(1, '未知地点！') : null;

		return $placeid;
	}

	public function index(){}

	//新增标注地点
	public function newmarkplace()
	{
		$userid = $this->userinfo['userid'];

		$title = $this->_getTitle(true);
		$address = $this->_getAddress(true);
		$desc = $this->_getDesc();
		$lat = $this->_getLat(true);
		$lng = $this->_getLng(true);

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
		$userid = $this->userinfo['userid'];

		$title = $this->_getTitle(true);
		$address = $this->_getAddress(true);
		$desc = $this->_getDesc();
		$lat = $this->_getLat(true);
		$lng = $this->_getLng(true);

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
		$userid = $this->userinfo['userid'];

		$pmtype = $this->_getPmtype(true);
		$address = $this->_getAddress(true);
		$desc = $this->_getDesc();
		$lat = $this->_getLat(true);
		$lng = $this->_getLng(true);

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

	//我的标注地点
	public function markplace()
	{
		$userid = $this->userinfo['userid'];

		list($start, $length) = $this->mkPage();
		$result = D('Place')->getMarkplace(null, $userid, $start, $length);
		
		$data = array();
		foreach ($result['data'] as $d) {
			$data[] = array(
				'placeid'  => (int)$d['markplaceid'],
				'title'    => $d['title'],
				'address'  => $d['address'],
				'desc'     => $d['desc'],
				'lat'      => $d['lat'],
				'lng'      => $d['lng'],
				'marktime' => date('Y-m-d H:i:s', $d['marktime']),
			);
		}

		$this->apiReturn(0,'',array(
			'total' => (int)$result['total'],
			'data' => $data
		));
	}

	//我的新增地点
	public function ptplace()
	{
		$userid = $this->userinfo['userid'];

		list($start, $length) = $this->mkPage();
		$result = D('Place')->getPtplace(null, $userid, $start, $length);
		
		$data = array();
		foreach ($result['data'] as $d) {
			$data[] = array(
				'placeid'  => (int)$d['ptplaceid'],
				'title'    => $d['title'],
				'address'  => $d['address'],
				'desc'     => $d['desc'],
				'lat'      => $d['lat'],
				'lng'      => $d['lng'],
				'pttime'   => date('Y-m-d H:i:s', $d['pttime']),
			);
		}

		$this->apiReturn(0,'',array(
			'total' => (int)$result['total'],
			'data' => $data
		));
	}

	//我的纠错地点
	public function pmplace()
	{
		$userid = $this->userinfo['userid'];

		list($start, $length) = $this->mkPage();
		$result = D('Place')->getPmplace(null, $userid, null, $start, $length);
		
		$data = array();
		foreach ($result['data'] as $d) {
			$data[] = array(
				'placeid'  => (int)$d['pmplaceid'],
				'pmtype'   => (int)$d['pmtype'],
				'address'  => $d['address'],
				'desc'     => $d['desc'],
				'lat'      => $d['lat'],
				'lng'      => $d['lng'],
				'pmtime'   => date('Y-m-d H:i:s', $d['pmtime']),
			);
		}

		$this->apiReturn(0,'',array(
			'total' => (int)$result['total'],
			'data' => $data
		));
	}

	//删除地点信息
	public function delplace()
	{
		$userid = $this->userinfo['userid'];

		$placeid = $this->_getPlaceid(true);

		$placetype = mRequest('placetype');
		$table = null;
		$placeidfield = null;
		switch ($placetype) {
			case 'markplace':
				$table = 'markplace';
				$placeidfield = 'markplaceid';
			break;
			case 'pmplace':
				$table = 'pmplace';
				$placeidfield = 'pmplaceid';
			break;
			case 'ptplace':
				$table = 'ptplace';
				$placeidfield = 'ptplaceid';
			break;
			default:
			break;
		}
		if (!$table) $this->apiReturn(1, '未知地点类型！');

		$result = M($table)->where(array($placeidfield=>$placeid, 'userid'=>$userid))->save(array('isdelete'=>1));
		if ($result) {
			$this->apiReturn(0, '删除成功！', array(
				'result' => 1
			));
		} else {
			$this->apiReturn(0, '删除失败！', array(
				'result' => 0
			));
		}
	}
}