<?php
/**
 * Common公共类 普通控制逻辑类继承该类
 * 一些特殊类可不继承该类 例：Login登录类、Public公共类等
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Controller;

class CommonController extends BaseController
{
    //分页配置
    protected $_page = 1;
    protected $_pagesize = 8;

    //用户登录信息 session存储
    protected $userinfo;

	//初始化
	public function __construct()
	{
		parent::__construct();

        $this->userinfo = $this->GSUserInfo();

        $this->_CKSign();
	}

    /**
     * 存取用户信息session
     * @param int $isrefresh 是否刷新session 0:不刷新 1:刷新 默认1
     */
    protected function GSUserInfo($userinfo=array(),$isrefresh=1)
    {
        if (!is_array($userinfo)) return false;

        $suserinfo = session('userinfo');
        if (!empty($userinfo)) {
            isset($userinfo['userid']) ? $suserinfo['userid'] = $userinfo['userid'] : null;
            isset($userinfo['phone']) ? $suserinfo['phone'] = $userinfo['phone'] : null;
            isset($userinfo['nickname']) ? $suserinfo['nickname'] = $userinfo['nickname'] : null;

            session('userinfo',$suserinfo);
        }
        
        return is_array($suserinfo)&&!empty($suserinfo) ? $suserinfo : array();
    }

    /**
     * 注销登录session
     */
    protected function USUserInfo()
    {
        session('userinfo', null);
    }

    /**
     * 判断用户是否已登录
     */
    protected function CKUserLogon($appreturnflag=0)
    {
        if (!$this->userinfo || !is_array($this->userinfo)) {
            return $appreturnflag ? $this->appReturn(1,L('user_logon_error')) : false;
        }

        return true;
    }

    //获取页码
    protected function _getPage()
    {
    	$_page = $this->_page;
        $page = mGet('page');

        is_numeric($page)&&$page>0 ? $_page = $page : null;

        return $_page;
    }

    //获取每页记录数
    protected function _getPagesize()
    {
    	$_pagesize = $this->_pagesize;
        $pagesize = mGet('pagesize');

        is_numeric($pagesize)&&$pagesize>0 ? $_pagesize = $pagesize : null;

        return $_pagesize;
    }

    /**
     * 分页预处理
     * @access private
     * @param void
     * @return void
     */
    protected function mkPage($total=null,$flag=0)
    {
        $page = $this->_getPage();
        $pagesize = $this->_getPagesize();

        //计算开始行号
        $start = ($page-1)*$pagesize;
        
        if ($total === null) return array($start,$pagesize);

    	if (!is_numeric($total)) return false;

        //计算总页数
        $pagecount = ceil($total/$pagesize);

        //返回
        return $flag ? array($start,$pagesize,$pagecount) : array($start,$pagesize);
    }

    //检查校验Sign
    private function _CKSign()
    {
        return true;
    }
}