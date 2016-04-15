<?php
/**
 * 用户管理 业务
 * wangbaoqing@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Controller;

use Org\Util\Filter;

class UserController extends CommonController
{
	//用户来源
	private $_sourcelist = array(
		0 => array('id'=>0, 'title'=>'正常注册'),
	);

	//用户状态
	private $_statuslist = array(
		0 => array('id'=>0, 'title'=>'已禁用', 'class'=>'danger'),
		1 => array('id'=>1, 'title'=>'已启用', 'class'=>'success'),
	);

	public function __construct()
	{
		parent::__construct();

		$this->assign('sourcelist', $this->_sourcelist);
		$this->assign('statuslist', $this->_statuslist);
	}

	public function index(){}

	//获取userid
	private function _getUserid()
	{
		$userid = mRequest('userid');
		$this->assign('userid', $userid);

		return $userid;
	}

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
		if (!$phone || !Filter::F_Phone($phone)) $this->ajaxReturn(1, '未知手机号！');
		$passwd = $this->_getPasswd();
		if (!$passwd || !Filter::F_Password($passwd)) $this->ajaxReturn(1, '密码格式错误！');
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
		$userid = $this->_getUserid();

		$userinfo = D('User')->getUserByID($userid);
		$this->assign('userinfo', $userinfo);

		$this->display('upuser.html');
	}

	//编辑用户-保存
	public function upusersave()
	{
		$userid = $this->_getUserid();
		if (!$userid) $this->ajaxReturn(1, '未知用户信息！');

		$phone = $this->_getPhone();
		if (!$phone || !Filter::F_Phone($phone)) $this->ajaxReturn(1, '未知手机号！');

		$passwd = $this->_getPasswd();
		if ($passwd) {
			if (!Filter::F_Password($passwd)) $this->ajaxReturn(1, '密码格式错误！');
			$passwdc = $this->_getPasswdc();
			if ($passwd != $passwdc) $this->ajaxReturn(1, '两次输入的密码不一致！');

			$passwd = D('User')->passwordEncrypt($passwd);
		}

		$username = $this->_getUsername();
		if (!$username) $this->ajaxReturn(1, '未知用户名！');

		$avatar = $this->_getAvatar();

		$data = array(
			'phone'      => $phone,
			'username'   => $username,
			'avatar'     => $avatar,
			'updatetime' => TIMESTAMP,
		);
		if ($passwd) $data['passwd'] = $passwd;
		$userid = D('User')->saveuser($userid, $data);
		if ($userid) {
			$this->ajaxReturn(0, '保存成功！');
		} else {
			$this->ajaxReturn(1, '保存失败！');
		}
	}

	//管理用户
	public function userlist()
	{
        $keywords = mRequest('keywords');
        $this->assign('keywords', $keywords);

        list($start, $length) = $this->_mkPage();
        $data = D('User')->getUser(null, $keywords, null, $start, $length);
        $total = $data['total'];
        $datalist = $data['data'];

        $this->assign('datalist', $datalist);

        $params = array(
            'keywords' => $keywords,
        );
        $this->assign('param', $params);
        //解析分页数据
        $this->_mkPagination($total, $params);

		$this->display();
	}

	//启用-禁用用户
	public function enableuser()
	{
		$userid = $this->_getUserid();
		if (!$userid) $this->ajaxReturn(1, '未知用户信息！');

		$status = $this->_getStatus();
		if (!isset($this->_statuslist[$status])) $this->ajaxReturn(1, '未知用户状态！');

		$result = M('user')->where(array('userid'=>$userid))->save(array('status'=>$status));
		if ($result) {
			$this->ajaxReturn(0, '编辑成功！');
		} else {
			$this->ajaxReturn(1, '编辑失败！');
		}
	}

	//意见反馈
	public function lvword()
	{
        $keywords = mRequest('keywords');
        $this->assign('keywords', $keywords);

        list($start, $length) = $this->_mkPage();
        $data = D('User')->getLvword(null, $keywords, $start, $length);
        $total = $data['total'];
        $datalist = $data['data'];

        $this->assign('datalist', $datalist);

        $params = array(
            'keywords' => $keywords,
        );
        $this->assign('param', $params);
        //解析分页数据
        $this->_mkPagination($total, $params);

		$this->display();
	}

	//删除意见反馈
	public function lvworddelete()
	{
		$lvwordid = mRequest('lvwordid');
		if (!$lvwordid) $this->ajaxReturn(1, '未知意见反馈信息！');

		$result = M('lvword')->where(array('lvwordid'=>$lvwordid))->delete();
		if ($result) {
			$this->ajaxReturn(0, '删除成功！');
		} else {
			$this->ajaxReturn(1, '删除失败！');
		}
	}

	//设备日志
	public function device()
	{
		
	}
}