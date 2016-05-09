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
	protected function _getLon()
	{
		$lon = mRequest('lon');
		if (!$lon) $this->apiReturn(1, '未知参数lon！');

		return $lon;
	}

	//获取lat
	protected function _getLat()
	{
		$lat = mRequest('lat');
		if (!$lat) $this->apiReturn(1, '未知参数lat！');

		return $lat;
	}

	//获取key
	private function _getKey()
	{
		$key = mRequest('key');
		if (!$key) $this->apiReturn(1, '未知参数Key！');

		return $key;
	}

	//获取pageindex
	private function _getpageIndex()
	{
		$pageIndex = mRequest('pageIndex');
		if (!$pageIndex) $pageIndex = 1;

		return $pageIndex;
	}

	//获取pagestep
	private function _getpageStep()
	{
		$pageStep = mRequest('pageStep');
		if (!$pageStep) $pageStep = 5;

		return $pageStep;
	}

	//获取curX
	private function _getcurX()
	{
		$curX = mRequest('curX');
		if (!$curX) $this->apiReturn(1, '未知参数curX！');

		return $curX;
	}

	//获取curY
	private function _getcurY()
	{
		$curY = mRequest('curY');
		if (!$curY) $this->apiReturn(1, '未知参数curY！');

		return $curY;
	}

	//获取distance
	private function _getDistance()
	{
		$distance = mRequest('distance');
		if (!$distance) $this->apiReturn(1, '未知参数distance！');

		return $distance;
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
			$result = json_decode($result['result'], true, 10, JSON_BIGINT_AS_STRING);

			return is_array($result)&&!empty($result) ? $result : '';
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

	//POI搜索
	public function poisearch()
	{
		$this->CKQuest('get');

		$key = $this->_getKey();
		$pageIndex = $this->_getpageIndex();
		$pageStep = $this->_getpageStep();

		$paramStr = 'key='.$key.'&pac=&type=&flds=&pageIndex='.$pageIndex.'&pageStep='.$pageStep.'&taghead=&tagtail=';
		$geocodeapi = $this->_mapconfig['poisearch_api'].'?'.$paramStr;

		$Http = Http::Init($geocodeapi, 1);
		$result = $Http->get();

		$apijson = $this->_parseResult($result);
		$this->apiReturn(0, '', array(
			'apijson' => $apijson
		));
	}

	//nearsearch
	public function nearsearch()
	{
		$this->CKQuest('get');

		$curX = $this->_getcurX();
		$curY = $this->_getcurY();
		$distance = $this->_getDistance();
		$key = $this->_getKey();
		$pageIndex = $this->_getpageIndex();
		$pageStep = $this->_getpageStep();

		$paramStr = 'key='.$key.'&curX='.$curX.'&curY='.$curY.'&distance='.$distance.'&type=&flds=&pageIndex='.$pageIndex.'&pageStep='.$pageStep.'&taghead=&tagtail=';
		$geocodeapi = $this->_mapconfig['nearsearch_api'].'?'.$paramStr;

		$Http = Http::Init($geocodeapi, 1);
		$result = $Http->get();

		$apijson = $this->_parseResult($result);
		$this->apiReturn(0, '', array(
			'apijson' => $apijson
		));
	}

	//离线地图检测接口
	public function offline()
	{
		$version = mRequest('version');
		if (!$version) $this->apiReturn(1, '未知版本信息！');

		$mapinfo = D('Map')->getMap();
		$new = version_compare($version, $mapinfo['version'], '<') ? true : false;

		$this->apiReturn(0,'',array(
			'new'     => $new,
			'version' => $mapinfo['version'],
			'title'   => $mapinfo['title'],
			'dllink'  => $mapinfo['path'],
			'size'    => format_bytes($mapinfo['size']),
		));
	}

	//图层接口
	public function layer()
	{
		$data = D('Map')->getLayer();

		$layerlist = array();
		foreach ($data as $layer) {
			$layerlist[] = array(
				'layerid'  => (int)$layer['layerid'],
				'title'    => $layer['title'],
				'icon'     => ImageURL($layer['icon']),
				'ashow'    => (int)$layer['ashow'],
				'layerapi' => array(
					'url'           => (string)$layer['url'],
					'service'       => (string)$layer['service'],
					'request'       => (string)$layer['request'],
					'version'       => (string)$layer['version'],
					'layer'         => (string)$layer['layer'],
					'style'         => (string)$layer['style'],
					'tilematrixset' => (string)$layer['tilematrixset'],
					'format'        => (string)$layer['format'],
				)
			);
		}

		$this->apiReturn(0, '', array('layerlist'=>$layerlist));
	}
}