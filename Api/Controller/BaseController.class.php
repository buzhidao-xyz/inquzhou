<?php
/**
 * Base类
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Controller;

use Think\Controller;
use Org\Util\Log;

class BaseController extends Controller
{
	//接口返回标识
	private $errorflag = array(0,1);

	//初始化
	public function __construct()
	{
		parent::__construct();

		//获取php://input数据
        $this->_getPhpinput();

		//配置文件输出到模板
		$this->_assignConfig();

		//加载语言包
		$this->_loadLang();

        //记录请求日志
        $this->_accessLog();
	}

    //获取php://input数据
    private function _getPhpinput()
    {
        $phpinput = file_get_contents("php://input");
        $phpinputdata = json_decode($phpinput, true);
        !is_array($phpinputdata) ? $phpinputdata = array() : null;
        
        $_REQUEST = array_merge($_REQUEST, $phpinputdata, array('phpinput'=>$phpinput));
    }

	/**
	 * 配置文件输出到模板
	 */
	private function _assignConfig()
	{
        $SERVER = array();

        //服务器HOST
        $HOST = C('HOST');
        $SERVER['HOST'] = $HOST;
        $this->assign('SERVER', $SERVER);

        //系统初始化默认管理员
        $SYSTEM_MANAGER = C('SYSTEM_MANAGER');
        $this->assign('system_manager', $SYSTEM_MANAGER);
	}

    /**
     * 记录请求日志
     */
    private function _accessLog()
    {
        Log::record('access',array(
            'ModuleName'  => MODULE_NAME,
            'ServerIp'    => $_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'],
            'ClientIp'    => get_client_ip(),
            'DateTime'    => date('Y-m-d H:i:s', TIMESTAMP),
            'TimeZone'    => 'UTC'.date('O',TIMESTAMP),
            'Method'      => $_SERVER['REQUEST_METHOD'],
            'URL'         => $_SERVER['REQUEST_URI'],
            'Protocol'    => $_SERVER['SERVER_PROTOCOL'],
            'RequestData' => $_REQUEST,
        ));
    }

    /**
     * 加载语言包
     */
    private function _loadLang()
    {
        $lang_type = C('DEFAULT_LANG');

        //加载公共语言包
        include(LANG_PATH.$lang_type.'.php');
        L($lang);
        //加载控制器语言包
        include(APP_PATH.MODULE_NAME.'/Common/Lang/'.$lang_type.'/'.CONTROLLER_NAME.'.php');
        L($lang);
    }

	/**
	 * 检查请求类型 是否get/post
	 * @param string $quest 请求类型 get/post/put/delete
	 */
	protected function CKQuest($quest=null)
	{
		if (!$quest) return false;

		$flag = true;
		switch ($quest) {
			case 'get':
				if (!IS_GET) $flag = false;
				break;
			case 'post':
				if (!IS_POST) $flag = false;
				break;
			case 'put':
				if (!IS_PUT) $flag = false;
				break;
			case 'delete':
				if (!IS_DELETE) $flag = false;
				break;
			default:
				break;
		}
		if (!$flag) $this->apiReturn(1,L('quest_error'));

		return true;
	}

	/**
	 * API返回数据
	 * @param int $error 是否产生错误信息 0没有错误信息 1有错误信息
	 * @param string $msg 如果有错 msg为错误信息
	 * @param array $data 返回的数据 多维数组
	 * @return json 统一返回json数据
	 */
	public function apiReturn($error=0,$msg=null,$data=array())
	{
		if (!in_array($error,$this->errorflag)) {
			$error = 1;
			!$msg ? $msg = L('apireturn_error_errorflag') : null;
			$data = array();
		}

		if ($error && !$msg) {
			$error = 1;
			$msg = L('apireturn_error_msg');
			$data = array();
		}

		if (!$error && !is_array($data)) {
			$error = 1;
			$msg = L('apireturn_error_data');
			$data = array();
		}

		//APP返回
		$return = array(
			'error' => $error,
			'msg' => $msg,
			'data' => $data
		);

		$type = 'json';
		switch ($type) {
			case 'json':
				$return = json_encode($return);
				break;
			default:
				$return = json_encode($return);
				break;
		}

		header('Content-Type: application/json');
		echo $return;
		exit;
	}

    /**
     * http请求处理
     * @param string $way get/post
     */
    protected function HttpClient($way='get',$api=null,$vars=array(),$header=array(),$timeout=5,$timeoutflag=1,$options=array())
    {
    	if (!$api || !is_array($header) || !is_array($options)) return false;

    	import('Org.Net.Http');

    	//初始化httpcurl客户端
    	$HttpClient = \Org\Net\Http::Init($api,1);

    	switch ($way) {
    		case 'get':
    			$result = $HttpClient->get(null,$vars,$header,'',$timeout,$options);
    			break;
    		case 'post':
    			$result = $HttpClient->post(null,$vars,$header,'',$timeout,$options);
    			break;
    		default:
    			$result = $HttpClient->get(null,$vars,$header,'',$timeout,$options);
    			break;
    	}

        //如果有错误 返回错误
        if ($timeoutflag&&$result['error']) $this->apiReturn(1,L($result['error']));

        return $result['result'];
    }
}