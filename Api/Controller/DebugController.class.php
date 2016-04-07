<?php
/**
 * 调试 控制器
 * wangbaoqing@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Controller;

class DebugController extends BaseController
{
    private $_sessionid;
    private $_signature;

    //初始化
    public function __construct()
    {
        if (!APP_DEBUG) {
            header('location:http://www.baidu.com/');exit;
        }
        parent::__construct();

        $debuguserinfo = session('debuguserinfo');
        if (is_array($debuguserinfo)&&!empty($debuguserinfo)) {
            $timestamp = (string)TIMESTAMP;

            //生成sign
            $signdata = array($timestamp);
            sort($signdata);
            $signature = md5(implode('',$signdata));

            $this->assign('timestamp',TIMESTAMP);
            $this->assign('sessionid',session_id());
            $this->assign('signature',$sign);
        }
    }

    //windows Http登录验证
    private function HttpAuth()
    {
        $authorized = false;

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];

            if ($username == 'moolyapp' || $password == 'mooly!@34') {
                $authorized = true;
            }
        }

        if (!$authorized) {
            Header("WWW-Authenticate: Basic realm=\"guess it!\"");
            Header("HTTP/1.0 401 Unauthorized");

            echo <<<EOB
                <html><body>
                <h1>Rejected!</h1>
                <big>Wrong Username or Password!</big>
                </body></html>
EOB;
            exit;
        }
    }

    //登录
    public function login()
    {
        session('debuguserinfo',null);
        $this->display();
    }

    //登录验证
    public function loginck()
    {
        $username = mPost('username');
        $password = mPost('password');

        if ($username == 'moolyapp' && $password == 'mooly!@34') {
            session('debuguserinfo',array(
                'username' => $username
            ));
            header('location:'.__ROOT__.'/debug/apilist');
            exit;
        }

        echo '登录失败！账户或密码错误！';
        exit;
    }

    //退出
    public function logout()
    {
        session('ticket',null);
        session('debuguserinfo',null);
        session_destroy();
        session_unset();
        header('location:'.__ROOT__.'/debug/login');
    }
    
    /**
     * API列表
     */
    public function apilist()
    {
        $debuguserinfo = session('debuguserinfo');
        if (!$debuguserinfo || empty($debuguserinfo)) {
            header('location:'.__ROOT__.'/debug/login');
            exit;
        }

        $this->display();
    }

    /**
     * 用户登录
     */
    public function userlogin()
    {
        $this->assign('password',md5(md5('123456').$this->_appsecret));
        $this->display();
    }
}