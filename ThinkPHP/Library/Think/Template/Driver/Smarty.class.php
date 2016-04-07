<?php
/**
 * Smarty模板引擎驱动 实现fetch方法
 * wangbaoqing@imooly.com
 * 2014-07-14
 */

//定义命名空间
namespace Think\Template\Driver;

class Smarty
{
	public function __construct()
	{

	}

	/**
	 * 渲染摸板输出
	 * @access public
	 * @param string $templateFile 模板文件名
	 * @param array $var 模板变量
	 * @return void
	 */
	public function fetch($templateFile=null,$var=null)
	{
		$templateFile = substr($templateFile,strlen(THEME_PATH));

		vendor('Smarty.Smarty#class');

		$smarty = new \Smarty();

		$smarty->caching     = C('TMPL_CACHE_ON');
		$smarty->template_dir= THEME_PATH;
		$smarty->compile_dir = CACHE_PATH;
		$smarty->cache_dir   = TEMP_PATH;

		if (C('TMPL_ENGINE_CONFIG')) {
			$config = C('TMPL_ENGINE_CONFIG');

			foreach ($config as $key=>$val) {
				$tpl->{$key} = $val;
			}
		}

		$smarty->assign($var);

		// $smarty->display($templateFile);

		//获取模板内容
		$output = $smarty->fetch($templateFile);

		//解析模板标签
		\Think\Hook::listen('template_filter',$output);

		//输出模板内容
		echo $output;
	}
}