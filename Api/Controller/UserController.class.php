<?php
/**
 * 登录注册 控制器
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Controller;

use Org\Net\Http;

class UserController extends CommonController
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
     * 获取手机账号
     */
    private function _getPhone($ck=1)
    {
        $phone = mRequest('phone');
        if ($ck&&!\Think\Filter::CKPhone($phone)) $this->apiReturn(1,'未知手机号码！');
        if ($phone&&!\Think\Filter::CKPhone($phone)) $this->apiReturn(1,'未知手机号码！');

        return $phone;
    }

    /**
     * 获取登录密码
     */
    private function _getPassword()
    {
        $password = $_REQUEST['password'];

        return $password;
    }

    /**
     * 获取确认密码
     */
    private function _getPasswordc()
    {
        $passwordc = $_REQUEST['passwordc'];

        return $passwordc;
    }

    /**
     * 获取手机短信验证码
     */
    private function _getVcode()
    {
        $vcode = mRequest('vcode');

        return $vcode;
    }

    /**
     * 获取发送短信的动作action
     */
    private function _getAction()
    {
        $action = mRequest('action');
        $actions = array('regist','forgot');
        if (!in_array($action,$actions)) $this->apiReturn(1,'未知短信发送动作！');

        return $action;
    }

    /**
     * 发送手机短信验证码
     */
    public function sendvcode()
    {
        $this->CKQuest('post');
        //短信防重发间隔
        $sms_resend_expire = C('RS.SMS_RESEND_EXPIRE');

        //查询session是否在N秒内有短信发送记录
        if (session('smssendflag','',0,0)) $this->apiReturn(1,$sms_resend_expire.'秒内请勿重复发送短信！');

        //发送短信的动作
        $action = $this->_getAction();
        if ($action == 'regist') {
            $phone = $this->_getPhone();
            $userinfo = D('User')->getUserByPhone($phone);
            if (!empty($userinfo)) $this->apiReturn(1,'该手机号码已注册！');
        }
        if ($action == 'forgot') {
            $phone = $this->_getPhone();
            $userinfo = D('User')->getUserByPhone($phone);
            if (empty($userinfo)) $this->apiReturn(1,'该手机号码未注册！');
        }
        if ($action == 'forgotwalletpwd') {
            $phone = $this->_getPhone(0);

            //检查登录
            $this->CKUserLogon(1);

            $userinfo = $this->userinfo;
            //判断要发送的手机号码是否是该登录用户手机号码
            if ($phone&&$phone!=$userinfo['phone']) $this->apiReturn(1,'发送手机号码验证失败！');

            $phone = $userinfo['phone'];
        }

        //短信验证码过期时间
        $sms_code_expire = C('RS.SMS_CODE_EXPIRE');
        //生成短信验证码
        $vcode = D('Org')->GCsmsvcode();

        //短信模板编码
        $templatecode = D('Org')->smsTemplateCode($action);
        //获取短信模板
        $smstemplate = D('Org')->getSmstemplate($templatecode);
        $msg = isset($smstemplate['caption']) ? str_replace('ml000009', $vcode, $smstemplate['caption']) : null;

        //存储短信验证码
        $invalitedate = TIMESTAMP+$sms_code_expire;
        $return = D('Org')->smsvcodeSave($templatecode,$phone,$vcode,$invalitedate);
        if ($return) {
            //发送短信
            CR('Org')->sendsms($phone,$msg);

            //session记录短信发送标记 N秒内防重发
            session('smssendflag',1,$sms_resend_expire);

            $this->apiReturn(0,'短信发送成功！',array(
                'success' => 1,
                'message' => ''
            ));
        }

        $this->apiReturn(1,'短信发送失败！');
    }

    /**
     * 验证手机短信验证码是否真实有效
     */
    public function ckvcode()
    {
        $this->CKQuest('get');

        //手机号码
        $phone = $this->_getPhone();
        //验证码
        $vcode = $this->_getVcode();

        //发送短信的动作
        $action = $this->_getAction();
        //短信模板编码
        $templatecode = D('Org')->smsTemplateCode($action);

        //检查短信验证码是否已过期
        $return = D('Org')->ckSmsVcodeExpire($templatecode,$phone,$vcode);
        if ($return) {
            $this->apiReturn(0,'',array(
                'success' => 1,
                'message' => ''
            ));
        } else {
            $this->apiReturn(1,'短信验证码不存在或已过期！');
        }
    }

    /**
     * 用户注册
     */
    public function regist()
    {
        $this->CKQuest('post');
    }

    /**
     * 用户登录
     */
    public function login()
    {
        $this->CKQuest('post');

        //获取手机号码
        $phone = $this->_getPhone();

        //获取密码或登录码
        $password = $this->_getPassword();
        $signtoken = $this->_getSigntoken();
        if (!$password && !$signtoken) $this->apiReturn(1,'未知密码！');

        //appid
        $appid = mRequest('appid');
        //获取appdevice
        $appinfo = D('Apps')->getAppdeviceByAppid($appid);
        $appsecret = isset($appinfo['appsecret']) ? $appinfo['appsecret'] : "";

        //IP
        $ip = $this->_getIp();
        //经度
        $lng = $this->_getLng();
        //维度
        $lat = $this->_getLat();

        //登录成功
        import('Org.Net.Http');

        //登录信息
        $postvars = array(
            'appid' => $appid,
            'appsecret' => $appsecret,
            'phone' => $phone,
            'password' => $password,
            'ip' => $ip,
            'lng' => $lng,
            'lat' => $lat,
            'signtoken' => $signtoken,
            'ticket' => D('Apps')->GSTicket($appid)
        );

        //连接单点登录服务器 远程登录认证
        require_once(MODULE_PATH.'Conf/sso.config.php');
        $api = 'http://'.$sso_host.':'.$sso_port.__ROOT__.'/sso/login';
        //http请求
        $result = $this->HttpClient('post',$api,$postvars);
        $result = json_decode($result);

        if ($result->success) {
            $userinfo = (array)$result->userinfo;
            $avatar = D('User')->getUserAvatar($userinfo['id']);

            //生成登录signtoken
            $signtoken = D('User')->GCSigntoken($appid,$phone);
            //保存登录signtoken
            D('User')->signtokenSave($userinfo['id'],$appid,$signtoken);

            //用户绑定appid
            $appid = mRequest('appid');
            D('User')->bindingAppid($userinfo['id'],$appid);

            //设置登录session
            $this->GSUserInfo(array(
                'userid' => $userinfo['id'],
                'phone' => $userinfo['phone'],
                'nickname' => $userinfo['nickname'],
                'username' => $userinfo['username'],
            ));

            $vipflag = $userinfo['roleid']==C('USER.USERROLE_P2') && strtotime($userinfo['disabledate'])>TIMESTAMP ? 1 : 0;

            //返回数据
            $return = array(
                'userid' => $userinfo['id'],
                'phone'  => $userinfo['phone'],
                'nickname' => $userinfo['nickname'],
                'email'  => $userinfo['email'],
                'bindingbusinessid' => $userinfo['bindingbusinessid'],
                'bindingbusiness'   => $userinfo['bindingbusiness'],
                'avatar'   => $avatar,
                'userrole' => $userinfo['roleid'],
                'status' => $userinfo['status'],
                'signtoken' => $signtoken,
                'sessionid' => session_id(),
                'vipflag' => $vipflag
            );
            $this->apiReturn(0,null,$return);
        } else {
            $this->apiReturn(1,'登录失败！账户或密码错误！');
        }
    }

    /**
     * 用户退出
     */
    public function logout()
    {
        $this->CKQuest('get');

        //注销登录session
        $this->USUserInfo();

        //清除signtoken
        D('User')->signtokenSave($userinfo['id'],null,null,0);
        //用户解绑appid
        D('User')->bindingAppid($userinfo['id'],null,0);

        $this->apiReturn(0,'',array(
            'success' => 1,
            'message' => ''
        ));
    }

    /**
     * 忘记密码-验证手机号码
     */
    public function forgotpwdckphone()
    {
        $this->CKQuest('post');

        $action = 'forgot';

        //手机号码
        $phone = $this->_getPhone();
        if (!D('User')->CKPhoneExists($phone)) $this->apiReturn(1,'该手机号码未注册！');

        //短信验证码
        $vcode = $this->_getVcode();
        $templatecode = D('Org')->smsTemplateCode($action);
        if (!D('Org')->ckSmsVcodeExpire($templatecode,$phone,$vcode)) $this->apiReturn(1,'短信验证码不存在或已过期！');

        $this->apiReturn(0,'',array(
            'success' => 1,
            'message' => ''
        ));
    }

    /**
     * 忘记密码-设置新密码
     */
    public function forgotpwdnew()
    {
        $this->CKQuest('post');

        $action = 'forgot';

        //手机号码
        $phone = $this->_getPhone();
        if (!D('User')->CKPhoneExists($phone)) $this->apiReturn(1,'该手机号码尚未注册！');

        //短信验证码
        $vcode = $this->_getVcode();
        $templatecode = D('Org')->smsTemplateCode($action);
        // if (!D('Org')->ckSmsVcodeExpire($templatecode,$phone,$vcode)) $this->apiReturn(1,'短信验证码不存在或已过期！');

        //用户密码
        $password = $this->_getPassword();
        if (!\Think\Filter::CKPasswd($password)) $this->apiReturn(1,'密码规则：数字字母开始 包含数字字母_!@#$的6-20位字符串');
        //确认密码
        $passwordc = $this->_getPasswordc();
        //检查密码
        if ($password != $passwordc) $this->apiReturn(1,'两次输入的密码不一致！');

        //设置新密码
        
        //获取用户信息
        $userinfo = D('User')->getUserByPhone($phone);
        
        //用户信息
        $data = array(
            'password' => md5($password)
        );

        //用户ID
        $userid = $userinfo['id'];
        //设置用户所属的人群角色
        $return = D('User')->setUserInfo($userid,$data);

        if ($return) {
            $this->apiReturn(0,'',array(
                'success' => 1,
                'message' => ''
            ));
        } else {
            $this->apiReturn(1,'设置新密码失败！');
        }
    }

    /**
     * 获取个人信息
     */
    public function userinfo()
    {
        $this->CKQuest('get');

        //检查用户登录状态
        $this->CKUserLogon(1);

        $userid = $this->userinfo['userid'];
        //获取用户信息
        $userinfo = D('User')->getUserByID($userid);

        //用户钱包信息
        $userwallet = D('Wallet')->getUserWallet($userid);
        $totalvolume = isset($userwallet['silver']) ? $userwallet['silver'] : 0;

        $avatar = D('User')->getUserAvatar($userinfo['id']);
        $usergroup = isset($userinfo['usergroup']) ? $userinfo['usergroup'] : null;
        $this->apiReturn(0,'',array(
            'userid' => $userid,
            'avatar' => $avatar,
            'nickname' => $userinfo['nickname'],
            'totalvolume' => $totalvolume,
            'usergroup' => $usergroup,
            'truename'  => $userinfo['username'],
            'gender'    => $userinfo['gender'],
            'phone'     => $userinfo['phone']
        ));
    }

    /**
     * 获取头像 图片内容 base64编码
     */
    private function _getAvatar()
    {
        $avatar = mRequest('avatar',false);

        return $avatar;
    }

    /**
     * 获取昵称
     */
    private function _getNickname()
    {
        $nickname = mRequest('nickname');

        return $nickname;
    }

    /**
     * 获取真实姓名
     */
    private function _getTruename()
    {
        $truename = mRequest('truename');

        return $truename;
    }

    /**
     * 获取性别 男/女
     */
    private function _getGender()
    {
        $gender = mRequest('gender');

        return $gender;
    }

    /**
     * 更新个人信息
     */
    public function setinfo()
    {
        $this->CKQuest('post');

        //检查用户登录状态
        $this->CKUserLogon(1);
        $userinfo = $this->userinfo;

        $data = array();

        //获取头像 avatar
        $avatar = $this->_getAvatar();
        if ($avatar) {
            $api = C('RS.AVATAR_API');

            $postvars = array(
                'action' => 'appupload',
                'id'     => $userinfo['userid'],
                'imagekey' => $avatar
            );
            //http请求
            $result = $this->HttpClient('post',$api,$postvars);
            $result = json_decode($result);

            if (isset($result->State) && $result->State === 0) {
                $data['isloadpic'] = 1;
            }
        }

        //获取昵称
        $nickname = $this->_getNickname();
        if ($nickname) $data['nickname'] = $nickname;

        //获取用户所属人群
        $usergroup = $this->_getUsergroup(0);
        if ($usergroup) $data['usergroup'] = $usergroup;

        //获取真实姓名
        $truename = $this->_getTruename();
        if ($truename) $data['username'] = $truename;

        //获取gender
        $gender = $this->_getGender();
        if ($gender) $data['gender'] = $gender;

        //获取phone
        $phone = $this->_getPhone(0);
        if ($phone) $data['phone'] = $phone;

        //设置用户信息
        $return = D('User')->setUserInfo($userinfo['userid'],$data);
        if ($return) {
            //更新昵称或手机号
            if ($nickname||$phone) {
                $userinfo = array();
                if ($nickname) $userinfo['nickname'] = $nickname;
                if ($phone) $userinfo['phone'] = $phone;
                $this->GSUserInfo($userinfo,0);
            }

            $this->apiReturn(0,'',array(
                'success' => 1,
                'message' => ''
            ));
        } else {
            $this->apiReturn(1,'更新失败！');
        }
    }

    /**
     * 修改密码
     */
    public function setuserpwd()
    {
        $this->CKQuest('post');

        //检查用户登录状态
        $this->CKUserLogon(1);
        $userinfo = $this->userinfo;

        $data = array();

        //获取原密码
        $oldpassword = mRequest('oldpassword');
        $oldpassword = D('User')->passwordEncrypt($oldpassword);
        //检查原密码是否正确
        if (!$oldpassword || !D('User')->CKUserPassword($userinfo['userid'],$oldpassword)) $this->apiReturn(1,L('oldpassword_error'));
    
        //新密码
        $password = $this->_getPassword();
        if (!\Think\Filter::CKPasswd($password)) $this->apiReturn(1,'密码规则：数字字母开始 包含数字字母_!@#$的6-20位字符串');
        //确认密码
        $passwordc = $this->_getPasswordc();
        //检查密码
        if ($password != $passwordc) $this->apiReturn(1,'两次输入的密码不一致！');

        //设置密码
        $data = array(
            'password' => D('User')->passwordEncrypt($password)
        );
        $return = D('User')->setUserInfo($userinfo['userid'],$data);
        if ($return) {
            $this->apiReturn(0,'',array(
                'success' => 1,
                'message' => ''
            ));
        } else {
            $this->apiReturn(1,'修改失败！');
        }
    }
}