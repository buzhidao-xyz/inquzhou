<?php
/**
 * APP模块API 控制逻辑
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Controller;

class OrgController extends CommonController
{
    //初始化
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {}

    /**
     * 发送短信
     */
    public function sendsms($phone=null, $code=null)
    {
    	if (!\Think\Filter::CKPhone($phone) || !$code) return false;

        $netease  = C('NETEASE');
        $appkey   = $netease['appkey'];
        $nonce    = \Org\Util\String::randString(18);
        $curtime  = TIMESTAMP;
        $checksum = sha1($netease['appsecret'].$nonce.$curtime);

    	$api = 'https://api.netease.im/sms/sendtemplate.action';
        $vars = 'templateid=6421&mobiles=["'.$phone.'"]&params=["'.$code.'"]';
        $header = array(
            'AppKey: '.$appkey,
            'CurTime: '.$curtime,
            'CheckSum: '.$checksum,
            'Nonce: '.$nonce,
            'charset: utf-8',
            'Content-Type: application/x-www-form-urlencoded',
        );

        //http请求
        $result = $this->HttpClient('post',$api,$vars,$header,3,0,array('CURLOPT_SSL_VERIFYPEER'=>0));
    	$result = json_decode($result, true);

    	if ($result['code'] === 200) {
            return $result['obj'];
        } else {
            return false;
        }
    }

    /**
     * 上传图片通用接口
     */
    public function uploadimg()
    {
        $this->CKQuest('post');

        //检查用户登录状态
        $this->CKUserLogon(1);
        $userinfo = $this->userinfo;

        $imgfile = mRequest('imgfile',false);
        if (!$imgfile) $this->apiReturn(1,'请选择图片！');

        $api = C('RS.IMAGE_UPLOAD');

        $postvars = array(
            'action'   => 'appnormalupload',
            'imagekey' => html_entity_decode($imgfile)
        );
        //http请求
        $result = $this->HttpClient('post',$api,$postvars);
        $result = json_decode($result,true);

        //返回
        if ($result['State'] == 0) {
            $this->apiReturn(0,'',array(
                    'success' => 1,
                    'imgpath' => $result['Url'],
                    'imgurl'  => C('RS.IMAGE_SERVER').$result['Url']
                )
            );
        } else {
            $msg = $result['ErrorMessage'] ? $result['ErrorMessage'] : '图片上传失败！';
            $this->apiReturn(1,$msg);
        }
    }
}