<?php
/**
 * APP模块API 控制逻辑
 * wangbaoqing@imooly.com
 * 2016-04-07
 */
namespace Api\Controller;

class AppiController extends CommonController
{
    //初始化
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){}

    //获取APP最新版本
    public function newversion()
    {
        if (!IS_GET) $this->apiReturn(1,'Http请求方式错误！');

        $this->apiReturn(0, '', array());
    }
}