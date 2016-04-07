<?php
/**
 * 分享配置文件
 * wangbaoqing@imooly.com
 * 2015-01-09
 */

return array(
	/**
	 * 分享平台
	 * weibo:新浪微博
	 * qzone:QQ空间
	 * qqmsg:QQ好友
	 * wxmsg:微信好友
	 * wxcircle:微信朋友圈 
	 */
	'platform' => array(
		'weibo',
		'qzone',
		'qqmsg',
		'wxmsg',
		'wxcircle'
	),
	/**
	 * 分享对象 goods:商品 estore:实体店 fstore:旗舰店 app:应用
	 */
	'object' => array(
		'goods',
		'estore',
		'fstore',
		'app'
	),
	/**
	 * 分享对象的参数
	 */
	'object_params' => array(
		'goods' => array('goodsid'),
		'estore' => array('estoreid'),
		'fstore' => array('fstoreid'),
	),
	//分享对象文案内容
	'object_content' => array(
		'goods'  => '我在魔力网发现了一个不错的商品：[T001]，点击查看：[T002] | 下载魔力网APP查看更多商品[T003]',
		'estore' => '我在魔力网发现了一家不错的实体商家：[T001]，点击查看：[T002] | 下载魔力网APP查看更多商品[T003]',
		'fstore' => '我在魔力网发现了一家不错的旗舰店：[T001]，点击查看：[T002] | 下载魔力网APP查看更多商品[T003]',
		'app'    => '我发现了一款很赞的应用，魔力网移动客户端！会员价，手机下单、支付超方便，喜欢的商品还能分享给好友哦！一起试试吧！|下载魔力网手机客户端[T003]',
	),
	//分享对象文案内容链接
	'object_link' => array(
		'goodslink' => 'http://www.imooly.com/item-[L001].html',
		'estorelink' => 'http://bs.imooly.com/Union/V?businessid=[L002]',
		'fstorelink' => 'http://www.imooly.com/[L003].html',
		'applink'   => 'http://www.imooly.com/',
	),
	/**
	 * 分享内容类型 word:文字 image:图片 wimg:图文 app:应用
	 */
	'object_type' => array(
		'goods' => 'word',
		'estore' => 'word',
		'fstore' => 'word',
		'app'   => 'wimg'
	),
);