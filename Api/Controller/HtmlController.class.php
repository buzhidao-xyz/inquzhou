<?php
/**
 * html页面 API接口
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Controller;

use Think\Controller;

class HtmlController extends BaseController
{
    //分页配置
    protected $_page = 1;
    protected $_pagesize = 3;

	public function __construct()
	{
		parent::__construct();

		//JSONP跨域声明
		header('Access-Control-Allow-Origin: *');
	}
}