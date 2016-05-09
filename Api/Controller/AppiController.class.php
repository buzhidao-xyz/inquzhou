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

    //检测APP最新版本
    public function ckversion()
    {
        $version = mRequest('version');
        if (!$version) $this->apiReturn(1, '未知版本号！');

        //获取最新版本信息
        $versioninfo = M('app_version')->order('versionid desc')->find();
        $new = version_compare($version, $versioninfo['versionno'], '<') ? true : false;

        $this->apiReturn(0, '', array(
            'new'     => $new,
            'version' => $versioninfo['versionno'],
            'desc'    => explode(';', $versioninfo['versiondesc']),
            'dllink'  => $versioninfo['appstorelink']
        ));
    }
}