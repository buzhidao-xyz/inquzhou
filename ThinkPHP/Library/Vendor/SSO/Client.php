<?php
/**
 * 魔力网SSO PHPClient
 * 2014-09-09
 * wang baoqing
 * client默认为ssl方式与SSO Server通信 如果不用https 请先调用setNoSSL()方法
 */
class Client
{
	//默认编码
	private $_encoding = 'UTF-8';

	//session过期时间
	protected $_session_expiretime;
	//session加密字符串
	protected $_session_encrypt = 'uf7we12a34saf';

	//需要过滤的数据类型
	protected $_qs_type = array('string','integer','double');

	//配置
	private $_sso_host;
	private $_sso_port;
	private $_sso_login;
	private $_sso_logout;
	private $_sso_logout_key;
	private $_sso_validate;
	private $_sso_cert_file;
	private $_sso_update;
	private $_sso_token;

	//统一时间戳
	private $_time;

	//SSL
	private $_ssl;
	private $_http;

	//验证ticket
	private $_ticket;
	//是否session保存ticket 默认为true
	private $_session_ticket = true;

	/**
	 * 初始化 SSO Client
	 * @param string $configfile sso配置文件路径 如果不指定 默认加载当面目录config.php
	 * 如果改变了sso的config.php文件位置 client初始化的时候 需要传进来
	 */
	public function __construct($configfile=null)
	{
		$this->_time = time();

		//开启session
		$this->_sessionStart();
		//加载配置文件
		$this->_loadConfig($configfile);
	}

	/**
	 * 开启session
	 */
	private function _sessionStart()
	{
		$sessionid = session_id();

		if (!$sessionid) session_start();
	}

	//加载配置文件
	private function _loadConfig($configfile=null)
	{
		// !$configfile ? $configfile = 'config.php' : null;
		if (!$configfile) {
			$sso_path = str_replace('\\','/',dirname(__FILE__));
			$configfile = $sso_path.'/config.php';
		}
		
		if (!file_exists($configfile)) {
			echo 'no config file found!';exit;
		}
		include($configfile);

		$this->_sso_host = $sso_host;
		$this->_sso_port = $sso_port;
		$this->_sso_login = $sso_login;
		$this->_sso_logout = $sso_logout;
		$this->_sso_logout_key = $sso_logout_key;
		$this->_sso_validate = $sso_validate;
		$this->_sso_cert_file = $sso_cert_file;
		$this->_sso_update = $sso_update;
		$this->_sso_token = $sso_token;
		$this->_session_expiretime = $_session_expiretime;

		//SSL判断
		$this->_ssl  = $sso_ssl;
		$this->_http = $this->_ssl ? 'https' : 'http';
	}

	/**
	 * 获取GET/POST参数
	 */
	private function qs($name=null)
	{
		if (!$name || !is_string($name)) return null;

		$value = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
    
	    $type = gettype($value);
	    if (in_array($type, $this->_qs_type)) {
	        $value = htmlentities(trim($value),ENT_QUOTES,$this->_encoding);
	    }

	    return $value;
	}

	/**
	 * session读写操作
	 * @param $sessionname string session名
	 * @param $sessionvalue mixed session值 默认为'' 值为null则删除该session
	 * @param $expiretime int session过期时间 单位秒 默认30分钟 1800秒
	 */
	protected function session($sessionname=null,$sessionvalue='',$expiretime=null)
	{
		$return = true;
		$expiretime = $expiretime ? $expiretime : $this->_session_expiretime;
		$sessionname = $this->_session_encrypt."_".$sessionname;
		$expiretimename = $this->_session_encrypt."_expiretime";
		$session = &$_SESSION;

	    //清除session
	    if ($sessionvalue === null) {
	        if (isset($session[$sessionname])) {
	            unset($session[$sessionname]);
	        }
	    } else if (!$sessionvalue) {
	        //获取session
	        $sessionvalue = isset($session[$sessionname]) ? $session[$sessionname] : null;
	        if ($sessionvalue) {
	            if ($this->_time <= $session[$expiretimename]) {
	                $return = $sessionvalue;
	            } else {
	                if (isset($session[$sessionname])) {
	                    unset($session[$sessionname]);
	                }
	                $return = null;
	            }
	        } else {
	            $return = null;
	        }
	    } else {
	        //设置session值
        	$session[$sessionname] = $sessionvalue;
        	//设置session过期时间
        	$session[$expiretimename] = $this->_time + $expiretime;
	    }

	    return $return;
	}

	/**
	 * cookie
	 */
	protected function cookie($name=null,$value='',$expiretime=1800)
	{
	    $return = true;
	    $expiretime = $this->_time + $expiretime;

	    if ($value === null) {
	        setcookie($name,$value,-1);
	        unset($_COOKIE[$name]);
	    } else if (!$value) {
	        $return = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	    } else {
	        setcookie($name,$value,$expiretime);
	        $_COOKIE[$name] = $value;
	    }

	    return $return;
	}

	/**
	 * 判断是否有ticket
	 */
	private function _hasTicket()
	{
		$flag = isset($_GET['ticket'])&&$_GET['ticket'] ? true : false;

		return $flag;
	}

	/**
	 * 获取ticket
	 */
	private function _getTicket()
	{
		$ticket = $this->qs('ticket');
		$this->_ticket = $ticket;

		return $ticket;
	}

	/**
	 * 设置ticket
	 */
	private function _setTicket($ticket=null)
	{
		if (!$ticket) return false;

		$phpsso = $this->session('PHPSSO');
		empty($phpsso) ? $phpsso = array() : null;
		$phpsso['ticket'] = $ticket;
		$this->session('PHPSSO',$phpsso);

		return true;
	}

	/**
	 * 设置user
	 */
	private function _setUser($user=null)
	{
		if (!$user) return false;

		$this->_user = $user;

		$phpsso = $this->session('PHPSSO');
		empty($phpsso) ? $phpsso = array() : null;
		$phpsso['user'] = $user;
		$this->session('PHPSSO',$phpsso);

		return true;
	}

	/**
	 * 设置user Attributes
	 */
	private function _setUserAttributes($attributes=null)
	{
		if (!is_array($attributes) || empty($attributes)) return false;

		$phpsso = $this->session('PHPSSO');
		empty($phpsso) ? $phpsso = array() : null;
		$phpsso['attributes'] = $attributes;
		$this->session('PHPSSO',$phpsso);

		return true;
	}

	/**
	 * 获取user
	 */
	public function getUser()
	{
		return $this->_user;
	}

	/**
	 * 获取request uri
	 */
	private function _getRequestURI()
	{
	    if (isset($_SERVER['REQUEST_URI'])) {
	        $uri = $_SERVER['REQUEST_URI'];
	    } else {
	        if (isset($_SERVER['argv'])) {
	            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
	        } else {
	            if (isset($_SERVER['QUERY_STRING'])) {
	                $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
	            } else {
	                $uri = $_SERVER['PHP_SELF'];
	            }
	        }
	    }
	    
	    return $uri;
	}

	/**
	 * 获取serviceURL
	 * 暂定为http 后面做调整
	 */
	private function _getURL()
	{
		return 'http://'.$_SERVER['HTTP_HOST'].$this->_getRequestURI();
	}

	/**
	 * clear URL ticket
	 */
	private function _clearTicketFromURL($url=null)
	{
		if (!$url) return null;

		$url = preg_replace('/(&ticket=[A-Za-z0-9-_]+)/', '', $url);

		return $url;
	}

	/**
	 * 获取SSO Server登录地址
	 */
	private function _getSSOServerLoginURL()
	{
		$sso_port = $this->_sso_port ? ':'.$this->_sso_port : $this->_sso_port;
		$service = $this->_getURL();
		$service = $this->_clearTicketFromURL($service);

		return $this->_http.'://'.$this->_sso_host.$sso_port.$this->_sso_login.'?service='.urlencode($service);
	}

	/**
	 * 获取SSO Server验证地址
	 */
	private function _getSSOServerValidateURL()
	{
		$sso_port = $this->_sso_port ? ':'.$this->_sso_port : $this->_sso_port;
		$service = $this->_getURL();
		$service = $this->_clearTicketFromURL($service);

		return $this->_http.'://'.$this->_sso_host.$sso_port.$this->_sso_validate.'?service='.urlencode($service).'&ticket=';
	}

	/**
	 * 获取SSO Server更新地址
	 */
	private function _getSSOServerUpdateURL()
	{
		$sso_port = $this->_sso_port ? ':'.$this->_sso_port : $this->_sso_port;

		$phpsso = $this->session('PHPSSO');
		$ticket = $phpsso['ticket'];
		return $this->_http.'://'.$this->_sso_host.$sso_port.$this->_sso_update.'?token='.$this->_sso_token.'&ticket='.$ticket;
	}

	/**
	 * 获取SSO Server退出地址
	 */
	private function _getSSOServerLogoutURL()
	{
		$sso_port = $this->_sso_port ? ':'.$this->_sso_port : $this->_sso_port;
		// $service = $this->_getURL();

		return $this->_http.'://'.$this->_sso_host.$sso_port.$this->_sso_logout;
	}

	/**
	 * 跳转到SSO Server
	 */
	private function _locationSSOServer($url=null)
	{
		header('location:'.$url);
		exit;
	}

	/**
	 * session判断是否已通过登录验证
	 */
	private function _isSessionAuthenticated()
	{
		$phpsso = $this->session('PHPSSO');

		return !empty($phpsso)&&isset($phpsso['user'])&&$phpsso['user'] ? true : false;
	}

	/**
	 * 验证成功、失败、错误等信息 打印
	 * @param bool $flag 
	 */
	private function _authFailed($flag=false,$ssoserver=null,$code=null,$msg=null)
	{
		if ($flag == true) {
			//成功
			echo 'SSO Authenticate Success!';
		} else if ($flag == false) {
			//失败
			echo 'SSO Authenticate False!';
		}
		
		exit();
	}

	/**
	 * 程序运行日志跟踪开始
	 */
	private function _traceLogStart()
	{

	}

	/**
	 * 程序运行日志跟踪结束
	 */
	private function _traceLogEnd()
	{

	}

	/**
	 * curl远程访问
	 * @param int $method 1:get 2:post
	 */
	private function _curlURL($url=null,$header=null,$body=null,$method=1)
	{
		if (!$url) return false;

		//初始化CURL
		$ch = curl_init();
		//设置url
		curl_setopt($ch, CURLOPT_URL, $url);
		//设置请求方式 默认GET
		switch ($method) {
			case 1:
				curl_setopt($ch, CURLOPT_HTTPGET, 1);
				break;
			case 2:
				curl_setopt($ch, CURLOPT_POST, 2);
				break;
			default:
				curl_setopt($ch, CURLOPT_HTTPGET, 1);
				break;
		}
		//直接返回文件流 不输出到浏览器
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//header流
		$headers = array(
			'cache-control: no-cache',
			'pragma: no-cache',
			'accept: text/xml',
			'connection: keep-alive',
			'content-type: text/xml',
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		//是否启用SSL
		$this->_ssl ? curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true) : curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		//执行curl
		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	/**
	 * DOM 解析xml
	 */
	private function _parseResult($xml=null)
	{
		if (!$xml) return null;

		$dom = new DOMDocument();
		$dom->preserveWhiteSpace = false;
		$dom->encoding = $this->_encoding;
		$return = $dom->loadXML($xml);

		if ($return) {
			$xmlResponse = $dom;
		} else {
			$xmlResponse = false;
		}

		return $xmlResponse;
	}

	/**
	 * 验证ticket 并获取user
	 */
	private function _validateSSOTicket()
	{
		$ticket = $this->_ticket;
		if (!$ticket) return false;

		//获取SSO Server验证URL
		$validateURL = $this->_getSSOServerValidateURL();
		$validateURL .= $ticket;

		//GET请求验证
		$result = $this->_curlURL($validateURL);
		if ($result) {
			$xmlResponse = $this->_parseResult($result);
			if ($xmlResponse) {
				//是否登录成功或失败
				if ($xmlResponse->getElementsByTagName('authenticationSuccess')->length != 0) {
					//登录成功
					$domElements = $xmlResponse->getElementsByTagName('authenticationSuccess');

					//登录用户
					$user = $xmlResponse->getElementsByTagName('user')->item(0)->nodeValue;
					// 

					//属性 循环读取
					$userAttributes = array();
					$attributes = $xmlResponse->getElementsByTagName('attributes')->item(0)->childNodes;
					if ($attributes->length != 0) {
						for ($i=0; $i<$attributes->length; $i++) {
							$key = str_replace('sso:','',$attributes->item($i)->nodeName);
							$userAttributes[$key] = $attributes->item($i)->nodeValue;
						}
					}

					//返回
					$return = array(true,array('user'=>$user,'attributes'=>$userAttributes));
				} else {
					//登录失败
					$domElements = $xmlResponse->getElementsByTagName('authenticationFailure');

					//失败日志
					$code = $domElements->item(0)->attributes->getNamedItem('code');
					$errormsg = $domElements->item(0)->nodeValue;

					//返回
					$return = array(false,'code'=>$code,'errormsg'=>$errormsg);
				}
			} else {
				$return = false;
			}
		} else {
			$return = false;
		}
		return $return;
	}
	
	/**
	 * 是否已通过验证
	 */
	public function isAuthenticated()
	{
		$return = false;

		//是否已经有session验证记录 未过期
		$sflag = $this->_isSessionAuthenticated();

		if ($sflag) {
			//重新设置user
			$phpsso = $this->session('PHPSSO');
			$this->_setUser($phpsso['user']);

			$return = true;

			//是否有ticket
			$tflag = $this->_hasTicket();
			if ($tflag) {
				$ticket = $this->_getTicket();
				//如果有flag 并且需要保存flag 更新session中flag值
				if ($this->_session_ticket) {
					$phpsso = $this->session('PHPSSO');
					$phpsso['ticket'] = $ticket;
					$this->session('PHPSSO',$phpsso);
				}

				//clear URL ticket 并 跳转
				$url = $this->_getURL();
				$url = $this->_clearTicketFromURL($url);
				header('location:'.$url);
				exit;
			}
		} else {
			//是否有ticket
			$tflag = $this->_hasTicket();
			if (!$tflag) {
				//没有session登录并且没有ticket
				$return = false;
			} else {
				//获取ticket
				$ticket = $this->_getTicket();
				if (!$ticket) {
					$this->_authFailed();

					$return = false;
				} else {
					//curl到SSO Server验证ticket
					$this->_setTicket($ticket);

					$resArray = $this->_validateSSOTicket();
					if (is_array($resArray) && !empty($resArray)) {
						list($loginFlag,$loginInfo) = $resArray;
						
						if ($loginFlag == true) {
							//登录成功
							$this->_setUser($loginInfo['user']);
							$this->_setUserAttributes($loginInfo['attributes']);

							$return = true;
						} else {
							//登录失败
							$this->_authFailed(false,null,$loginInfo['code'],$loginInfo['errormsg']);
							$return = false;
						}
					} else {
						$this->_authFailed();

						$return = false;
					}
				}
			}
		}

		return $return;
	}

	/**
	 * 验证登录 统一入口
	 */
	public function forceAuthentication()
	{
		if ($this->isAuthenticated()) {
			$return = true;
		} else {
			$ssoLoginURL = $this->_getSSOServerLoginURL();
			$this->_locationSSOServer($ssoLoginURL);

			//跳转 无返回
			$return = false;
		}

		return $return;
	}

	/**
	 * 退出登录
	 */
	public function logout($redirecturl=null)
	{
		//清空sso client session
		$this->session('PHPSSO',null);

		$logouturl = $this->_getSSOServerLogoutURL();
		$redirecturl ? $logouturl .= '?service='.urlencode($redirecturl) : null;

		$this->_locationSSOServer($logouturl);
	}

	/**
	 * SSO Server请求退出登录 监听地址client方法
	 * 管理系统监听地址 需要首先调用此方法
	 * 此方法需要用到配置中sso_logout_key
	 * 管理系统拿到sso server传过来的key之后 传给该方法 比较两个key是否一致
	 * 一致 合法请求 允许退出
	 * 不一致 非法请求 拒绝退出
	 * 作用：清空sso client session
	 * @param string $key SSO Server传过来的key
	 * @return bool true:key验证并退出成功 false:key验证失败
	 */
	public function logoutlisten($key=null)
	{
		if (!$key || !$this->_sso_logout_key || $key!=$this->_sso_logout_key) return false;

		//key验证成功 清空sso client session
		$this->session('PHPSSO',null);

		return true;
	}

	/**
	 * 验证登录
	 */
	public function CKAuthentication()
	{
		$sflag = $this->_isSessionAuthenticated();
		if (!$sflag) {
			$this->forceAuthentication();
		}

		return true;
	}

	/**
	 * 更新服务器session有效期
	 */
	public function serviceUpdate()
	{
		$updateurl = $this->_getSSOServerUpdateURL();

		$result = $this->_curlURL($updateurl);
		$result = json_decode($result);
		$return = $result->result=='true' ? true : false;

		//如果更新成功 重新设置session有效时间
		if ($return) {
			$phpsso = $this->session('PHPSSO');
			empty($phpsso) ? $phpsso = array() : null;
			$this->session('PHPSSO',$phpsso);
		}

		return $return;
	}
}