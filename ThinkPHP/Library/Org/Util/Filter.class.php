<?php
/**
 * 过滤工具
 * 2015-05-23
 * imbzd
 */
namespace Org\Util;

class Filter
{
    //初始化
    public function __construct(){}

    //过滤规则：字母开始、字母数字_-.组合、长度5-20
    static public function F_CharNoShort($var=null)
    {
        $regexp = "/^[a-zA-Z][a-zA-Z0-9_-.]{4,19}$/i";
        if (preg_match($regexp, $var) == 0) {
            return false;
        }

        return true;
    }

    //检测account 过滤规则：字母开始 字母数字下划线 长度5-20
    static public function F_Account($var=null)
    {
        $regexp = "/^[a-zA-Z][a-zA-Z0-9_]{4,19}$/i";
        if (preg_match($regexp, $var) == 0) {
            return false;
        }

        return true;
    }

    //检测password 过滤规则：字母数字开始 字母数字下划线!@#$% 长度5-20
    static public function F_Password($var=null)
    {
        $regexp = "/^[a-zA-Z0-9][a-zA-Z0-9_!@#$%]{4,19}$/i";
        if (preg_match($regexp, $var) == 0) {
            return false;
        }

        return true;
    }

    //检测phone 过滤规则：1(33|35|37|38|39|50|51|56|57|58|59|70|80|81|82|83|89)[0-9]{8}
    static public function F_Phone($var=null)
    {
        $regexp = "/^1(3|5|6|7|8|9)[0-9]{9}$/i";
        if (preg_match($regexp, $var) == 0) {
            return false;
        }

        return true;
    }
}