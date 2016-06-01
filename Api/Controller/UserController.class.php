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
    //短信发送时间间隔
    private $_sms_sendexpire = 60;
    //短信验证码有效期
    private $_sms_expiretime = 600;

    //用户来源
    private $_user_source = array('regist','weixin','qq','weibo');

    //初始化
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {}

    /**
     * 获取手机账号
     */
    private function _getPhone($ck=true)
    {
        $phone = mRequest('phone');
        if ($ck&&!\Org\Util\Filter::F_Phone($phone)) $this->apiReturn(1,'未知手机号码！');

        return $phone;
    }

    //获取username
    private function _getUsername($ck=true)
    {
        $username = mRequest('username');
        if ($ck&&!$username) $this->apiReturn(1, '请填写姓名！');

        return $username;
    }

    /**
     * 获取登录密码
     */
    private function _getPasswd()
    {
        $passwd = $_REQUEST['passwd'];
        if (!$passwd) $this->apiReturn(1, '请填写登录密码！');

        return $passwd;
    }

    /**
     * 获取手机短信验证码
     */
    private function _getVcode()
    {
        $vcode = mRequest('vcode');
        if (!$vcode) $this->apiReturn(1, '验证码错误！');

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

    //获取source
    private function _getSource($ck=true)
    {
        $source = mRequest('source');
        if ($ck&&!in_array($source, $this->_user_source)) $this->apiReturn(1, '用户来源错误！');

        return $source;
    }

    //获取oauthtoken
    private function _getOauthtoken($ck=true)
    {
        $oauthtoken = mRequest('oauthtoken');
        if (!$oauthtoken) $this->apiReturn(1, 'oauthtoken错误！');

        return $oauthtoken;
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
     * 发送手机短信验证码
     */
    public function sendvcode()
    {
        $action = $this->_getAction();
        $phone = $this->_getPhone();

        if ($action=="regist" && D('User')->CKPhoneExists($phone)) $this->apiReturn(1, '该手机号码已注册！');
        if ($action=="forgot" && !D('User')->CKPhoneExists($phone)) $this->apiReturn(1, '该手机号码尚未注册！');

        //查询是否刚发送过验证码 1分钟内不能重复发送
        if (M('vcode')->where(array('phone'=>$phone,'action'=>$action,'sendtime'=>array('egt', TIMESTAMP-$this->_sms_sendexpire)))->count()) {
            $this->apiReturn(1, '1分钟内请勿重复发送短信！');
        }

        $code = \Org\Util\String::randString(6,1);
        $obj = CR('Org')->sendsms($phone, $code);
        if ($obj) {
            //短信发送成功
            $data = array(
                'phone'      => $phone,
                'code'       => $code,
                'action'     => $action,
                'checked'    => 0,
                'sendtime'   => TIMESTAMP,
                'expiretime' => TIMESTAMP+$this->_sms_expiretime,
            );
            $result = M('vcode')->add($data);
            if ($result) {
                $this->apiReturn(0, '短信发送成功！', array(
                    'result' => 1
                ));
            }
        }

        $this->apiReturn(0,'短信发送失败！', array(
            'result' => 1
        ));
    }

    /**
     * 验证手机短信验证码是否真实有效
     */
    public function checkvcode()
    {
        $action = $this->_getAction();
        $phone = $this->_getPhone();

        //验证码
        $vcode = $this->_getVcode();

        //检查短信验证码是否已过期
        $vcodeinfo = M('vcode')->where(array('phone'=>$phone,'action'=>$action,'checked'=>0,'expiretime'=>array('egt', TIMESTAMP)))->order('expiretime desc')->find();
        if (is_array($vcodeinfo) && !empty($vcodeinfo) && $vcodeinfo['code']==$vcode) {
            //标识为已使用
            M('vcode')->where(array('vcodeid'=>$vcodeinfo['vcodeid']))->save(array('checked'=>1));

            $this->apiReturn(0,'短信验证码验证成功！',array(
                'result' => 1
            ));
        } else {
            $this->apiReturn(0,'短信验证码不存在或已过期！',array(
                'result' => 0
            ));
        }
    }

    /**
     * 用户注册
     */
    public function regist()
    {
        $action = 'regist';

        $phone = $this->_getPhone(true);
        $vcode = $this->_getVcode();
        $username = $this->_getUsername();
        $passwd = $this->_getPasswd();

        //检查验证码
        $vcodeinfo = M('vcode')->where(array('phone'=>$phone,'action'=>$action,'checked'=>1))->order('expiretime desc')->find();
        if (!is_array($vcodeinfo) || empty($vcodeinfo) || $vcodeinfo['code']!==$vcode) {
            $this->apiReturn(1, '验证码错误！');
        }

        //检查手机号码是否已存在
        if (M('user')->where(array('phone'=>$phone))->count()) {
            $this->apiReturn(1, '账号已存在！');
        }

        //检查密码格式
        if (!\Org\Util\Filter::F_Password($passwd)) {
            $this->apiReturn(1, '密码错误！5-20位数字字母下划线！');
        }

        $user = C('USER');
        $passwd = D('User')->passwordEncrypt($passwd);
        $data = array(
            'phone'      => $phone,
            'passwd'     => $passwd,
            'username'   => $username,
            'avatar'     => $user['USER_AVATAR_DEFAULT'],
            'source'     => 'regist',
            'oauthtoken' => '',
            'status'     => 1,
            'registtime' => TIMESTAMP,
        );
        $result = M('User')->add($data);
        if ($result) {
            $this->apiReturn(0, '注册成功！', array('result'=>1));
        } else {
            $this->apiReturn(0, '注册失败！', array('result'=>0));
        }
    }

    /**
     * 用户登录
     */
    public function login()
    {
        $source = $this->_getSource(false);

        if ($source == '' || $source == 'regist') {
            $phone = $this->_getPhone(true);
            $passwd = $this->_getPasswd();
            //查询用户信息
            $userinfo = D('User')->getUserByPhone($phone);

            if (empty($userinfo) || D('User')->passwordEncrypt($passwd)!=$userinfo['passwd']) {
                $this->apiReturn(1, '登录失败！账号或密码错误！');
            }
        } else {
            //第三方登录
            
            $usid = mRequest('usid');
            if (!$usid) $this->apiReturn(1, '未知USID！');
            $oauthtoken = $this->_getOauthtoken();

            $username = mRequest('username');
            $avatar = mRequest('avatar');

            //查询是否已经有该oauthuser
            $userinfo = M('user')->where(array('source'=>$source, 'usid'=>$usid, 'oauthtoken'=>$oauthtoken))->find();
            if (!is_array($userinfo) || empty($userinfo)) {
                //第三方用户入库
                $userinfo = array(
                    'phone'      => '',
                    'passwd'     => '',
                    'username'   => $username,
                    'avatar'     => $avatar,
                    'source'     => $source,
                    'usid'       => $usid,
                    'oauthtoken' => $oauthtoken,
                    'status'     => 1,
                    'registtime' => TIMESTAMP,
                );
                $userinfo['userid'] = M('user')->add($userinfo);
            }
        }

        //设置登录session
        $this->SetUserInfos(array(
            'userid'     => $userinfo['userid'],
            'phone'      => $userinfo['phone'],
            'username'   => $userinfo['username'],
            'source'     => $userinfo['source'],
            'usid'       => $userinfo['usid'],
            'oauthtoken' => $userinfo['oauthtoken'],
        ));

        //用户信息返回
        $data = array(
            'userid'    => (int)$userinfo['userid'],
            'phone'     => $userinfo['phone'],
            'username'  => $userinfo['username'],
            'avatar'    => ImageURL($userinfo['avatar']),
            'source'    => $userinfo['source'],
            'sessionid' => session_id(),
        );
        $this->apiReturn(0, '登录成功！', $data);
    }

    /**
     * 用户退出
     */
    public function logout()
    {
        //注销登录session
        $this->USUserInfo();

        $this->apiReturn(0, '退出登录成功！', array(
            'result' => 1,
        ));
    }

    //修改用户资料 - 姓名、头像
    public function setuserinfo()
    {
        $userinfo = $this->userinfo;
        $userid = $userinfo['userid'];

        $username = $this->_getUsername(false);
        $avatar = $this->_getAvatar();
        if (!$username && !$avatar) $this->apiReturn(1, '用户信息错误！');

        //更新姓名
        if ($username) {
            $result = M('user')->where(array('userid'=>$userid))->save(array(
                'username' => $username,
                'updatetime' => TIMESTAMP
            ));
            if (!$result) $this->apiReturn(1, '姓名设置失败！', array(
                'result' => 0,
            ));
        }

        //更新头像
        if ($avatar) {
            $imagedata = base64_decode($avatar);

            $rootpath = APP_PATH;
            $uploadpath = 'Upload/avatar/'.date('Y/md', TIMESTAMP).'/';
            mkdir($rootpath.$uploadpath, 0777, true);
            $avatarfile = $uploadpath.uniqid().'_'.TIMESTAMP.'.png';
            $size = file_put_contents($rootpath.$avatarfile, $imagedata);

            $result = true;
            if ($size) {
                $result = M('user')->where(array('userid'=>$userid))->save(array(
                    'avatar' => '/'.$avatarfile,
                    'updatetime' => TIMESTAMP
                ));
            }

            if (!$result) $this->apiReturn(1, '头像更新失败！', array(
                'result' => 0,
            ));
        }

        $this->apiReturn(0, '更新成功！', array(
            'result' => 1
        ));
    }

    //修改密码
    public function setpasswd()
    {
        $userinfo = $this->userinfo;
        $userid = $userinfo['userid'];

        //用户信息
        $userinfo = D('User')->getUserByUserid($userid);

        $originpasswd = mRequest('originpasswd');
        if ($userinfo['passwd']!=D('User')->passwordEncrypt($originpasswd)) $this->apiReturn(1, '原密码不正确！');

        $newpasswd = mRequest('newpasswd');
        if (!\Org\Util\Filter::F_Password($newpasswd)) $this->apiReturn(1, '新密码错误！5-20位数字字母下划线！');

        $confirmpasswd = mRequest('confirmpasswd');
        if ($confirmpasswd != $newpasswd) $this->apiReturn(1, '两次输入的新密码不一致！');

        $result = M('User')->where(array('userid'=>$userid))->save(array(
            'passwd' => D('User')->passwordEncrypt($newpasswd),
            'updatetime' => TIMESTAMP
        ));
        if ($result) {
            $this->apiReturn(0, '修改成功！', array(
                'result' => 1
            ));
        } else {
            $this->apiReturn(1, '修改失败！', array(
                'result' => 0
            ));
        }
    }

    /**
     * 找回密码
     */
    public function rebackpasswd()
    {
        $action = 'forgot';

        //手机号码
        $phone = $this->_getPhone();
        if (!D('User')->CKPhoneExists($phone)) $this->apiReturn(1,'该手机号码尚未注册！');

        //验证码
        $vcode = $this->_getVcode();
        //检查短信验证码是否已过期
        $vcodeinfo = M('vcode')->where(array('phone'=>$phone,'action'=>$action,'checked'=>0,'expiretime'=>array('egt', TIMESTAMP)))->order('expiretime desc')->find();
        if (is_array($vcodeinfo) && !empty($vcodeinfo) && $vcodeinfo['code']==$vcode) {
            //标识为已使用
            M('vcode')->where(array('vcodeid'=>$vcodeinfo['vcodeid']))->save(array('checked'=>1));
        } else {
            $this->apiReturn(0,'短信验证码验证失败！',array(
                'result' => 0
            ));
        }

        //用户密码
        $passwd = $this->_getPasswd();
        if (!\Org\Util\Filter::F_Password($passwd)) $this->apiReturn(1, '密码错误！5-20位数字字母下划线！');

        //设置新密码
        $result = M('User')->where(array('phone'=>$phone))->save(array(
            'passwd' => D('User')->passwordEncrypt($passwd),
            'updatetime' => TIMESTAMP
        ));
        if ($result) {
            $this->apiReturn(0, '新密码设置成功！', array(
                'result' => 1
            ));
        } else {
            $this->apiReturn(1, '新密码设置失败！', array(
                'result' => 0
            ));
        }
    }

    /**
     * 获取个人信息
     */
    public function userinfo()
    {
        $userinfo = $this->userinfo;
        $userid = $userinfo['userid'];

        //用户信息
        $userinfo = D('User')->getUserByUserid($userid);

        $this->apiReturn(0, '', array(
            'userid'    => (int)$userinfo['userid'],
            'phone'     => $userinfo['phone'],
            'username'  => $userinfo['username'],
            'avatar'    => ImageURL($userinfo['avatar']),
            'source'    => $userinfo['source'],
        ));
    }

    //意见反馈
    public function lvword()
    {
        $userinfo = $this->userinfo;
        $userid = $userinfo['userid'];

        $content = mRequest('content');
        if (!$content) $this->apiReturn(1, '请填写意见内容！');

        $result = M('lvword')->add(array(
            'userid' => $userid,
            'content' => $content,
            'createtime' => TIMESTAMP
        ));
        if ($result) {
            $this->apiReturn(0, '反馈意见提交成功！', array(
                'result' => 1
            ));
        } else {
            $this->apiReturn(1, '反馈意见提交失败！', array(
                'result' => 0
            ));
        }
    }

    //用户设备
    public function device()
    {
        $userinfo = $this->userinfo;
        $userid = $userinfo['userid'];

        $deviceid = mRequest('deviceid');
        if (!$deviceid) $this->apiReturn(1, '未知设备ID号！');

        $devicetype = mRequest('devicetype');
        $deviceos = mRequest('deviceos');
        $osversion = mRequest('osversion');

        $ip = mRequest('ip');
        $lat = mRequest('lat');
        $lng = mRequest('lng');

        $data = array(
            'userid'     => $userid,
            'deviceid'   => $deviceid,
            'devicetype' => $devicetype,
            'deviceos'   => $deviceos,
            'deviceid'   => $deviceid,
            'osversion'  => $osversion,
            'ip'         => $ip,
            'lat'        => $lat,
            'lng'        => $lng,
            'createtime' => TIMESTAMP
        );

        //判断deviceid是否已存在
        if (M('devicelog')->where(array('deviceid'=>$deviceid))->count()) {
            $result = M('devicelog')->where(array('deviceid'=>$deviceid))->save($data);
        } else {
            $result = M('devicelog')->add($data);
        }
        if ($result) {
            $this->apiReturn(0, '成功！', array(
                'result' => 1
            ));
        } else {
            $this->apiReturn(1, '失败！', array(
                'result' => 0
            ));
        }
    }
}