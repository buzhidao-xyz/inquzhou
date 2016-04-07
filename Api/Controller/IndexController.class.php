<?php
/**
 * 主入口 控制器
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Controller;

class IndexController extends CommonController
{
	//初始化
	public function __construct()
	{
		parent::__construct();
	}
	
    public function index()
    {
        echo 'inquzhou';
    }
}