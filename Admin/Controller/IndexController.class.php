<?php
/**
 * Admin Module Main Enter
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Admin\Controller;

class IndexController extends CommonController
{
    //默认选中组菜单id=1
    private $_default_groupid = 1;
    //默认选中节点菜单id=1(控制面板)
    private $_default_nodeid = 1;
    
    public function __construct()
    {
        parent::__construct();
    }

    //系统主框架页面
    public function index()
    {
        //节点菜单
        $nodemenu = isset($this->managerinfo['access'][$this->_default_groupid]) ? $this->managerinfo['access'][$this->_default_groupid]['nodelist'] : array();
        $this->assign('nodemenu', $nodemenu);

        $this->assign('groupid', $this->_default_groupid);
        //默认组菜单
        $this->assign('default_groupid', $this->_default_groupid);
        //默认节点菜单
        $this->assign('default_nodeid', $this->_default_nodeid);
        $this->display();
    }

    //解析时间段
    private function _mkdaterange()
    {
        $daterange = mRequest('daterange');

        $begindate = TIMESTAMP-30*24*3600;
        $enddate = TIMESTAMP;
        if ($daterange) {
            $dateranges = explode(' - ', $daterange);
            $begindate = strtotime($dateranges[0]);
            $enddate = strtotime($dateranges[1]);
        } else {
            $daterange = date('m/d/Y', $begindate) . ' - ' . date('m/d/Y', $enddate);
        }

        $this->assign('daterange', $daterange);
        return array($begindate, $enddate);
    }

    //系统主界面-控制面板
    public function dashboard()
    {
        //解析时间段
        list($begindate, $enddate) = $this->_mkdaterange();

        //用户增长数
        $usernum = M('user')->where(array('registtime'=>array('between', array($begindate, $enddate))))->count();
        $this->assign('usernum', $usernum);

        //设备增长数
        $devicenum = M('devicelog')->where(array('createtime'=>array('between', array($begindate, $enddate))))->count();
        $this->assign('devicenum', $devicenum);

        //数据纠错
        $pmplacenum = M('pmplace')->where(array('pmtime'=>array('between', array($begindate, $enddate))))->count();
        $this->assign('pmplacenum', $pmplacenum);
        
        //意见反馈
        $lvwordnum = M('lvword')->where(array('createtime'=>array('between', array($begindate, $enddate))))->count();
        $this->assign('lvwordnum', $lvwordnum);

        $this->display();
    }
}