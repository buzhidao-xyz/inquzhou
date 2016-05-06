<?php
/**
 * 用户模型
 * wbq@xlh-tech.com
 * 2016-04-07
 */
namespace Api\Model;

class UserModel extends CommonModel
{
	/**
	 * 初始化
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 用户密码加密
	 */
	public function passwordEncrypt($password=null)
	{
		$password = md5($password);

		return $password;
	}

	//获取用户信息-userid
	public function getUserByUserid($userid=null)
	{
		if (!$userid) return false;

		$userinfo = M('user')->where(array('userid'=>$userid))->find();

		return is_array($userinfo) ? $userinfo : array();
	}

	//获取用户信息-phone
	public function getUserByPhone($phone=null)
	{
		if (!$phone) return false;

		$userinfo = M('user')->where(array('phone'=>$phone))->find();

		return is_array($userinfo) ? $userinfo : array();
	}

	//获取用户信息 通过source+oauthtoken
	public function getUserByOauth($source=null, $oauthtoken=null)
	{
		if (!$source || !$oauthtoken) return false;

		$userinfo = M('user')->where(array('source'=>$source, 'oauthtoken'=>$oauthtoken))->find();

		return is_array($userinfo) ? $userinfo : array();
	}

	/**
	 * 保存用户注册信息
	 */
	public function userSave($data=array())
	{
		if (!is_array($data) || empty($data)) return false;

		$return = M('User')->add($data);

		return $return;
	}

	/**
	 * 检查原密码是否正确 修改密码
	 */
	public function CKUserPassword($userid=null,$password=null)
	{
		if (!$userid || !$password) return false;

		$return = M('User')->where(array('id'=>$userid,'password'=>$password))->find();

		return is_array($return)&&!empty($return) ? true : false;
	}

	/**
	 * 检查用户手机号码是否存在
	 * @return boolean true已存在 false不存在
	 */
	public function CKPhoneExists($phone=null)
	{
		if (!$phone) return false;

		$userinfo = M('user')->where(array('phone'=>$phone))->find();

		return is_array($userinfo)&&!empty($userinfo) ? true : false;
	}
}
