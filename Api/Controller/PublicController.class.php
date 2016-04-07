<?php
/**
 * 公共逻辑
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Controller;

use Think\Controller;

use Org\Net\BaiduMap;

class PublicController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
	}

	//minify
	public function minify()
	{
		//清空缓冲区并关闭输出缓冲
		ob_end_clean();

		//Minify加载js、css等静态文件
		Vendor('Minify.index');
	}
}