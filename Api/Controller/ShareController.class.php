<?php
/**
 * 分享 控制器
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Controller;

class ShareController extends CommonController
{
	//分享配置信息
	private $_share_config;
    private $_platform;
    private $_object;
    private $_object_params;
    private $_object_type;
	
    //初始化
    public function __construct()
    {
        parent::__construct();

        //API请求校验signature
        $this->CKSignature();

        //加载配置文件
        $this->_getConfig();
    }

    public function index() {}

    /**
     * 读取config文件
     */
    private function _getConfig()
    {
    	$configfile = APP_PATH.'APP/Conf/share.config.php';
    	if (!file_exists($configfile)) $this->apiReturn(1,'分享配置出错！');

    	$this->_share_config = include($configfile);
        $this->_platform       = $this->_share_config['platform'];
        $this->_object         = $this->_share_config['object'];
        $this->_object_params  = $this->_share_config['object_params'];
        $this->_object_type    = $this->_share_config['object_type'];
    	$this->_object_content = $this->_share_config['object_content'];
    	$this->_object_link    = $this->_share_config['object_link'];
    }

    /**
     * 分享对象
     */
    private function _getObject()
    {
    	$object = mRequest('object');
    	if (!in_array($object,$this->_object)) $this->apiReturn(1,'未知分享对象！');

    	return $object;
    }

    /**
     * 分享平台
     */
    private function _getPlatform()
    {
    	$platform = mRequest('platform');
    	if (!in_array($platform,$this->_platform)) $this->apiReturn(1,'未知分享平台！');

    	return $platform;
    }

    /**
     * 获取参数
     */
    private function _getParams($object=null)
    {
    	if (!$object) return false;
    	$params = mRequest('params',0);
    	$params = json_decode(html_entity_decode($params),true);

    	//需要的参数
    	$objectparams = $this->_object_params;
    	$objectparams = isset($objectparams[$object]) ? $objectparams[$object] : array();
    	//参数匹配
    	foreach ($objectparams as $d) {
    		if (!isset($params[$d])) $this->apiReturn(1,'缺少分享对象参数！');
    	}

    	return $params;
    }

    /**
     * 获取分享内容 APP应用
     */
    private function _getAppsinfo()
    {
        //分享模板
        $sharetpl = D('Share')->getShareInfo('app');
        
        //APP链接
        $applink = isset($sharetpl['applink']) ? $sharetpl['applink'] : $this->_object_link['applink'];
        //分享文案信息
        $content = isset($sharetpl['content']) ? $sharetpl['content'] : $this->_object_content['app'];

        //文字内容文案
        $search = array('[T003]');
        $replace = array(
            $applink,
        );
        $word = str_replace($search, $replace, $content);

        $sinfo = array(
            'stype' => $this->_object_type['app'],
            'title' => '魔力网移动客户端',
            'word'  => $word,
            'image' => C('HTTP_HOST').__ROOT__.'/Public/img/app/app_icon_default.png',
            'link'  => $applink,
        );
        return $sinfo;
    }

    /**
     * 获取分享内容
     */
    public function sinfo()
    {
        $this->CKQuest('get');
        //用户信息
        $userinfo = $this->userinfo;
        //分享对象
        $object = $this->_getObject();
        //分享平台
        $platform = $this->_getPlatform();
        //分享对象所需参数
        $params = $this->_getParams($object);

        //分享内容
        switch ($object) {
        	case 'goods':
        		$sinfo = $this->_getGoodssinfo($params['goodsid']);
        		break;
            case 'estore':
                $sinfo = $this->_getEstoresinfo($params['estoreid']);
                break;
            case 'fstore':
                $sinfo = $this->_getFstoresinfo($params['fstoreid']);
                break;
            case 'app':
                $sinfo = $this->_getAppsinfo();
                break;
        	default:
        		$sinfo = array();
        		break;
        }

        $this->apiReturn(0,'',$sinfo);
    }

    /**
     * 获取设备OS
     */
    private function _getDeviceos()
    {
        $deviceos = mRequest('deviceos');
        return $deviceos;
    }

    /**
     * 获取设备OS版本号
     */
    private function _getDeviceosversion()
    {
        $deviceosversion = mRequest('deviceosversion');
        return $deviceosversion;
    }


    /**
     * 获取App版本号
     */
    private function _getAppversion()
    {
        $appversion = mRequest('version');
        return $appversion;
    }

    /**
     * 获取设备类型
     */
    private function _getDevicetype()
    {
        $devicetype = mRequest('devicetype');
        return $devicetype;
    }

    /**
     * 获取设备码/设备id号
     */
    private function _getDeviceid()
    {
        $deviceid = mRequest('deviceid');
        return $deviceid;
    }

    /**
     * 获取设备IP
     */
    private function _getIp()
    {
        $ip = mRequest('ip');
        return $ip;
    }

    /**
     * 获取设备GPS - 经度
     */
    private function _getLng()
    {
        $lng = mRequest('lng');
        return $lng;
    }

    /**
     * 获取设备GPS - 维度
     */
    private function _getLat()
    {
        $lat = mRequest('lat');
        return $lat;
    }

    /**
     * 分享结果回调-是否成功
     */
    public function scallback()
    {
        $this->CKQuest('post');
        //用户信息
        $userinfo = $this->userinfo;
        //分享平台
        $platform = $this->_getPlatform();
        //分享对象
        $object = $this->_getObject();
        //分享对象所需参数
        $params = $this->_getParams($object);
        //设备系统类型
        $deviceos = $this->_getDeviceos();
        //设备系统版本号
        $deviceosversion = $this->_getDeviceosversion();
        $devicetype      = $this->_getDevicetype();
        $deviceid        = $this->_getDeviceid();
        $ip              = $this->_getIp();
        $lng             = $this->_getLng();
        $lat             = $this->_getLat();
        $appversion      = $this->_getAppversion();
        //是否成功
        $success = mRequest('success');
        $success = $success ? 1 : 0;

        //分享结果log
        $data = array(
            'userid'     => $userinfo['userid'],
            'username'   => $userinfo['nickname'],
            'platform'   => $platform,
            'sobject'    => $object,
            'params'     => $params,
            'deviceos'        => $deviceos,
            'deviceosversion' => (string)$deviceosversion,
            'devicetype' => $devicetype,
            'deviceid'   => $deviceid,
            'ip'         => new \MongoInt64(iptolong($ip)),
            'loc'        => array(
                'type'   => 'Point',
                'coordinates' => array($lng,$lat)
            ),
            'appversion' => (string)$appversion,
            'logtime'    => new \MongoDate(TIMESTAMP)
        );
        $return = D('Share')->shareLogSave($data);

        $this->apiReturn(0,'',array(
            'success' => 1
        ));
    }
}