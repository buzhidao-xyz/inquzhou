<?php
/**
 * 通用接口模型
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Model;

class OrgModel extends CommonModel
{
	/**
	 * 初始化
	 */
	public function __construct()
	{
		parent::__construct();
	}

    /**
     * 生成短信验证码
     * @return 6位数字
     */
    public function GCsmsvcode($n=6)
    {
    	$numberstr = '0123456789';
    	$vcode = null;

    	$min = 0; $max = strlen($numberstr)-1;
    	for ($i=0; $i<$n; $i++) {
    		$vcode .= $numberstr{rand($min,$max)};
    	}
    	return $vcode;
    }
}