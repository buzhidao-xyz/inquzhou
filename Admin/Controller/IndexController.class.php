<?php
/**
 * Admin Module Main Enter
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Admin\Controller;

class IndexController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    //系统主框架页面
    public function index()
    {
        $this->display();
    }

    //系统主界面-控制面板
    public function dashboard()
    {
        $this->display();
    }
}