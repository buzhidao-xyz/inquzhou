<?php
/**
 * 魔力网SSO 配置文件
 * wang baoqing
 * 2014-09-09
 */
// //sso包地址
// $sso_path = str_replace('\\','/',dirname(__FILE__));

// //SSO Server host
// $sso_host = '192.168.10.213';
// //SSO Server port
// $sso_port = 83;

// //SSL方式 false不启用 true启用
// $sso_ssl = false;

// //登录地址
// $sso_login = '/sso/login';

// //退出地址
// $sso_logout = '/sso/logout';

// //退出key
// $sso_logout_key = '';

// //ticket验证地址
// $sso_validate = '/sso/serviceValidate';

// //更新session有效期地址
// $sso_update = '/sso/serviceUpdate';
// //更新所需要的token
// $sso_token = 'CCA65890-268C-E2FD-AC4C-4C48273917F9';

// //如果服务器需要客户端提供证书 证书目录
// $sso_cert_file = '';

// //session过期时间 1200秒(20分钟)
// $_session_expiretime = 1200;

//配置文件调整
include(CONF_PATH.'sso.config.php');