<?php
/**
 * 第三方库接口逻辑 短信、验证码等
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Admin\Controller;

class OrgController extends BaseController
{
    private $_vcode_key = "vcode_login";

    //初始化
    public function __construct()
    {
        parent::__construct();
    }

    //生成验证码
    public function VCode()
    {
        $Verify = new \Any\Verify(array(
            'codeSet'  => 'ABCDEFGHJKLMNPQRTUVWXY',
            'useCurve' => false,
            'fontSize' => 15,
            'imageW'   => 110,
            'imageH'   => 32,
            'length'   => 4,
            'fontttf'  => '2.ttf',
        ));
        $Verify->entry($this->_vcode_key);
    }

    //验证验证码
    public function CKVcode($vcode=null)
    {
        if (!$vcode) return false;

        $Verify = new \Any\Verify(array(
            'codeSet' => 'ABCDEFGHJKLMNPQRTUVWXY',
            'useCurve' => false,
            'fontSize' => 15,
            'imageW' => 110,
            'imageH' => 32,
            'length' => 4,
        ));
        $result = $Verify->check($vcode, $this->_vcode_key);

        return $result ? true : false;
    }
}