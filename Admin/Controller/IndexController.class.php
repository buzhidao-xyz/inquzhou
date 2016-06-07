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

    //计算环比时间段
    private function _calcdaterangehb($begindate=null, $enddate=null)
    {
        if (!$begindate || !$enddate) return false;

        $days = floor(($enddate-$begindate)/(24*3600));
        $begindate_hb = strtotime(date('Y-m-d', $begindate)." -".$days.' day');
        $days++;
        $enddate_hb = strtotime(date('Y-m-d', $enddate)." -".$days.' day');

        return array($begindate_hb, $enddate_hb);
    }

    //系统主界面-控制面板
    public function dashboard()
    {
        //解析时间段
        list($begindate, $enddate) = $this->_mkdaterange();
        list($begindate_hb, $enddate_hb) = $this->_calcdaterangehb($begindate,$enddate);

        //用户增长数
        $usernum = M('user')->where(array('registtime'=>array('between', array($begindate, $enddate))))->count();
        $this->assign('usernum', $usernum);
        //环比增长数
        $usernum_hb = M('user')->where(array('registtime'=>array('between', array($begindate_hb, $enddate_hb))))->count();
        $usernum = (int)$usernum;
        $usernum_hb = (int)$usernum_hb;
        $usernum_inc = $usernum_hb>0 ? number_format(($usernum-$usernum_hb)/$usernum_hb*100, 2, '.', '').'%' : '100%';
        if ($usernum == 0) $usernum_inc = '0%';
        $this->assign('usernum_inc', $usernum_inc);

        //设备增长数
        $devicenum = M('devicelog')->where(array('createtime'=>array('between', array($begindate, $enddate))))->count();
        $this->assign('devicenum', $devicenum);
        //环比增长数
        $devicenum_hb = M('devicelog')->where(array('createtime'=>array('between', array($begindate_hb, $enddate_hb))))->count();
        $devicenum = (int)$devicenum;
        $devicenum_hb = (int)$devicenum_hb;
        $devicenum_inc = $devicenum_hb>0 ? number_format(($devicenum-$devicenum_hb)/$devicenum_hb*100, 2, '.', '').'%' : '100%';
        if ($devicenum == 0) $devicenum_inc = '0%';
        $this->assign('devicenum_inc', $devicenum_inc);

        //数据纠错
        $pmplacenum = M('pmplace')->alias('a')->field('a.*, b.phone')->join(' __USER__ b on a.userid=b.userid ')->where(array('pmtime'=>array('between', array($begindate, $enddate))))->count();
        $this->assign('pmplacenum', $pmplacenum);
        //环比增长数
        $pmplacenum_hb = M('pmplace')->alias('a')->field('a.*, b.phone')->join(' __USER__ b on a.userid=b.userid ')->where(array('pmtime'=>array('between', array($begindate_hb, $enddate_hb))))->count();
        $pmplacenum = (int)$pmplacenum;
        $pmplacenum_hb = (int)$pmplacenum_hb;
        $pmplacenum_inc = $pmplacenum_hb>0 ? number_format(($pmplacenum-$pmplacenum_hb)/$pmplacenum_hb*100, 2, '.', '').'%' : '100%';
        if ($pmplacenum == 0) $pmplacenum_inc = '0%';
        $this->assign('pmplacenum_inc', $pmplacenum_inc);
        
        //意见反馈
        $lvwordnum = M('lvword')->alias('a')->field('a.*, b.phone')->join(' __USER__ b on a.userid=b.userid ')->where(array('createtime'=>array('between', array($begindate, $enddate))))->count();
        $this->assign('lvwordnum', $lvwordnum);
        //环比增长数
        $lvwordnum_hb = M('lvword')->alias('a')->field('a.*, b.phone')->join(' __USER__ b on a.userid=b.userid ')->where(array('createtime'=>array('between', array($begindate_hb, $enddate_hb))))->count();
        $lvwordnum = (int)$lvwordnum;
        $lvwordnum_hb = (int)$lvwordnum_hb;
        $lvwordnum_inc = $lvwordnum_hb>0 ? number_format(($lvwordnum-$lvwordnum_hb)/$lvwordnum_hb*100, 2, '.', '').'%' : '100%';
        if ($lvwordnum == 0) $lvwordnum_inc = '0%';
        $this->assign('lvwordnum_inc', $lvwordnum_inc);

        $this->display();
    }
}