<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//开启缓冲Buffer 注册输出gzip压缩方法ob_gzhandler
ob_start('ob_gzhandler');

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', True);

//绑定主入口模块Home 默认可直接用controller/action访问而无需加模块名
define('BIND_MODULE','Api');

//MODULE_NAME
define('MODULE_NAME','Api');

// 定义应用目录
define('APP_PATH', str_replace('\\','/',dirname(__FILE__)).'/');

//系统应用主入口标识
define('APP_INDEX', 1);

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

//冲刷缓冲Buffer 输出内容
ob_end_flush();
