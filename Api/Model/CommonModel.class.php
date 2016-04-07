<?php
/**
 * 公共模型 File、ORG等不需要操作数据库的Model可不继承此类
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Model;

class CommonModel extends BaseModel
{
	public function __construct()
	{
		parent::__construct();
	}
}