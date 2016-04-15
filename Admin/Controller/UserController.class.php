<?php
/**
 * 用户管理 业务
 * wangbaoqing@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Controller;

class UserController extends CommonController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index(){}

	//获取phone
	private function _getPhone()
	{
		$phone = mRequest('phone');
		$this->assign('phone', $phone);

		return $phone;
	}

	//获取passwd
	private function _getPasswd()
	{
		$passwd = mRequest('passwd');
		$this->assign('passwd', $passwd);

		return $passwd;
	}

	//获取passwdc
	private function _getPasswdc()
	{
		$passwdc = mRequest('passwdc');
		$this->assign('passwdc', $passwdc);

		return $passwdc;
	}

	//获取username
	private function _getUsername()
	{
		$username = mRequest('username');
		$this->assign('username', $username);

		return $username;
	}

	//获取avatar
	private function _getAvatar()
	{
		$avatar = mRequest('avatar');
		if (!$avatar) $avatar = C('USER.USER_AVATAR_DEFAULT');

		$this->assign('avatar', $avatar);

		return $avatar;
	}

	//获取status
	private function _getStatus()
	{
		$status = mRequest('status');
		$this->assign('status', $status);

		return (int)$status;
	}

	//上传头像
	public function avatarupload()
	{
		$upload = new \Think\Upload();
		$upload->maxSize  = 5242880; //5M
		$upload->exts     = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = APP_PATH;
		$upload->savePath = '/Upload/avatar/';
		$upload->saveName = array('uniqid','');
		$upload->subName  = array('date','Y/md');
		$info = $upload->upload();

		$error = null;
        $msg = '上传成功！';
        $data = array();
		if (!$info) {
			$error = 1;
			$msg = $upload->getError();
		} else {
			$fileinfo = current($info);
			$data = array(
				'filepath' => $fileinfo['savepath'],
				'filename' => $fileinfo['savename'],
			);
		}

        $this->ajaxReturn($error, $msg, $data);
	}

	//新增用户
	public function newuser()
	{
		$this->display();
	}

	//新增用户-保存
	public function newusersave()
	{
		$phone = $this->_getPhone();
		if (!$phone) $this->ajaxReturn(1, '未知手机号！');
		$passwd = $this->_getPasswd();
		if (!$passwd) $this->ajaxReturn(1, '未知密码！');
		$passwdc = $this->_getPasswdc();
		if ($passwd != $passwdc) $this->ajaxReturn(1, '两次输入的密码不一致！');
		$username = $this->_getUsername();
		if (!$username) $this->ajaxReturn(1, '未知用户名！');

		$avatar = $this->_getAvatar();
		$status = $this->_getStatus();

		$passwd = D('User')->passwordEncrypt($passwd);
		$data = array(
			'phone'      => $phone,
			'passwd'     => $passwd,
			'username'   => $username,
			'avatar'     => $avatar,
			'source'     => 0,
			'status'     => $status,
			'registtime' => TIMESTAMP,
		);
		$userid = D('User')->saveuser(null, $data);
		if ($userid) {
			$this->ajaxReturn(0, '保存成功！');
		} else {
			$this->ajaxReturn(1, '保存失败！');
		}
	}

	//编辑用户
	public function upuser()
	{
		$this->display('upuser.html');
	}

	//编辑用户-保存
	public function upusersave()
	{

	}

	//管理用户
	public function userlist()
	{
		$this->display();
	}
}