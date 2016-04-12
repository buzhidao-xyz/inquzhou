<?php
/**
 * 地图API
 * wbq@xlh-tech.com
 * 2016-04-12
 */
namespace Api\Controller;

use Org\Net\Http;

class MapController extends CommonController
{
	//MapConfig
	private $_mapconfig = array();

	public function __construct()
	{
		parent::__construct();

		$this->_mapconfig = require(APP_PATH.MODULE_NAME.'/Conf/map.config.php');
	}

	//获取lon
	private function _getLon()
	{
		$lon = mRequest('lon');
		if (!$lon) $this->apiReturn(1, '未知参数lon！');

		return $lon;
	}

	//获取lat
	private function _getLat()
	{
		$lat = mRequest('lat');
		if (!$lat) $this->apiReturn(1, '未知参数lat！');

		return $lat;
	}

	//解析返回result
	private function _parseResult($result=array())
	{
		$jsonbody = '';
		if ($error !== null) {
			switch ($error) {
			case 'curl_timeout':
				$msg = '网络错误 请求超时！';
				break;
			default:
				$msg = '网络错误 请求超时！';
				break;
			}
			$this->apiReturn(1, $msg);
		} else {
			return $result['result'];
		}
	}

	//逆地址编码API
	public function geocode()
	{
		$this->CKQuest('get');

		$lon = $this->_getLon();
		$lat = $this->_getLat();

		$postStr = '{lon:'.$lon.',lat:'.$lat.',appkey:'.$this->_mapconfig['appkey'].',ver:1}';
		$geocodeapi = $this->_mapconfig['geocode_api'].'?postStr='.$postStr.'&type=geocode';

		$Http = Http::Init($geocodeapi, 1);
		$result = $Http->get();

		$apijson = $this->_parseResult($result);
		$this->apiReturn(0, '', array(
			'apijson' => $apijson
		));
	}
}