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

        //API请求校验signature
        $this->CKSignature();
    }

    public function index() {}

    /**
     * 发送短信
     */
    public function sendsms($phone=null,$msg=null)
    {
    	if (!\Think\Filter::CKPhone($phone)||!$msg) return false;

    	$api = C('RS.SMS_API').'?phone='.$phone.'&content='.urlencode($msg);

        //http请求
        $result = $this->HttpClient('get',$api,array(),1,0);
    	$result = json_decode($result);

    	if (!isset($result->result)||!$result->result) {
    		//发送失败
    		return false;
    	}

    	return true;
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