<?php
/**
 * 过滤类库 防注入 过滤危险字符 检查字符串有效性
 * wangbaoqing@imooly.com
 * 2014-1-9
 */
namespace Think;

class Filter
{

    /**
     * IP checking
     * 
     * @param  ip $var
     * @return bool
     */
    static public function CKIp($var=null)
    {
        $matchexp = "/^(([0-9]|([1-9][0-9])|([1][0-9][0-9])|(2[0-4][0-9])|(25[0-5]))\.){3,3}([0-9]|([1-9][0-9])|([1][0-9][0-9])|(2[0-4][0-9])|(25[0-5]))$/";

        if (!preg_match($matchexp, $var)) return false;

        return true;
    }
    
    /**
     * Email checking
     * @return bool
     */
    static public function CKMail($var=null)
    {
        $matchexp = "/^[a-z0-9A-Z](([a-z0-9A-Z_-]*[\.])*[a-z0-9A-Z])*@([a-z0-9A-Z]+([-][a-z0-9A-Z])*[\.])+[a-z]{2,5}$/i";
        
        if (!preg_match($matchexp, $var)) return false;

        return true;
    }

    /**
     * 检查手机号码有效性
     * @param string var 手机号码
     * @return boolean
     */
    static public function CKPhone($var=null)
    {
        $matchexp = '/^(13[0-9]|14[0-9]|15[0-9]|16[0-9]|17[0-9]|18[0-9])[0-9]{8}$/';

        if (!preg_match($matchexp, $var)) return false;

        return true;
    }

    /**
     * 检查密码 包括有效字符 安全性 长度
     * 规则：数字字母开始 包含数字字母_!@#$的6-20位字符串
     * @param string $varstr 密码字符串
     */
    static public function CKPasswd($varstr=null)
    {
        if (!$varstr) return false;

        $matchexp = '/^[a-zA-Z0-9][a-zA-Z0-9_!@#$]{5,19}$/';
        if (!preg_match($matchexp, $varstr)) {
            return false;
        }

        return true;
    }
}