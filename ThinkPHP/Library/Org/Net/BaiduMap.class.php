<?php
/**
 * 百度地图 webapi接口类
 * wangbaoqing@imooly.com
 * 2014-11-19 16:18:10
 */
namespace Org\Net;

class BaiduMap
{
	//开发者密钥
	private $_ak = 'AB4e296cfbde1f8a5de6741752112616';

	//IP定位API
	private $_api_iplocation = 'http://api.map.baidu.com/location/ip';

	//经纬度定位API
	private $_api_geocoder = 'http://api.map.baidu.com/geocoder/v2/';

	//经纬度坐标类型 默认为百度经纬度坐标
	private $_coor = 'bd09ll';
	
	/**
	 * 类实例初始化 逻辑
	 */
	public function __custruct()
	{

	}

	/**
	 * IP定位位置信息
	 * @param string $ip ip地址
	 * @return array 位置信息 array(province,city,area)
	 */
	public function ipLocation($ip=null)
	{
		$return = array();

        if (!\Think\Filter::CKIp($ip)) return $return;

        $ak = $this->_ak;
        $coor = $this->_coor;
		$api = $this->_api_iplocation.'?ak='.$ak.'&ip='.$ip.'&coor'.$coor;

        //初始化httpcurl客户端
        $HttpClient = Http::Init($api,1);
        $response = $HttpClient->get();
        $response = json_decode($response['result']);

        if (isset($response->content->address_detail->city_code)) {
        	$return = array(
        		'province' => $response->content->address_detail->province,
        		'town'     => $response->content->address_detail->city,
        		'area' => $response->content->address_detail->district
        	);
        }
        return $return;
	}

	/**
	 * 经纬度定位位置信息
	 * @param string $lng 经度
	 * @param string $lat 维度
	 * @return array 位置信息 array(province,town,area)
	 */
	public function geoLocation($lng=null,$lat=null)
	{
		$return = array();

		if (!$lng || !$lat) return $return;

		$ak = $this->_ak;
		$api = $this->_api_geocoder.'?ak='.$ak.'&callback=&location='.$lat.','.$lng.'&output=json&pois=0';

        //初始化httpcurl客户端
        $HttpClient = Http::Init($api,1);
        $response = $HttpClient->get();
        $response = json_decode($response['result']);

        if ($response->status == 0) {
        	$return = array(
        		'province' => $response->result->addressComponent->province,
        		'town'     => $response->result->addressComponent->city,
        		'area' => $response->result->addressComponent->district
        	);
        }
        return $return;
	}

	/**
	 * IP、经纬度定位位置信息
	 * @param string $ip ip地址
	 * @param string $lng 经度
	 * @param string $lat 维度
	 * @return array 位置信息 array(province,town,area)
	 */
	public function ipgeoLocation($ip=null,$lng=null,$lat=null)
	{
		$return = array();

		if ($ip) $return = $this->ipLocation($ip);

		if ((!is_array($return)||empty($return)) && $lng&&$lat) $return = $this->geoLocation($lng,$lat);

		return $return;
	}
}