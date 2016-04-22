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

	/**
	 * 用户头像
	 */
	public function getUserAvatar($userid=null)
	{
		if (!$userid) return C('DEFAULT_AVATAR');

		$userinfo = $this->getUserByID($userid);
		return isset($userinfo['isloadpic'])&&$userinfo['isloadpic'] ? C('RS.IMAGE_SERVER').'/uploadfiles/userheader/users/'.$userinfo['id'].'1.png?'.rand(100000,999999) : C('DEFAULT_AVATAR');
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
	 * 设置用户信息
	 */
	public function setUserInfo($userid=null,$data=array())
	{
		if (!$userid || !is_array($data) || empty($data)) return false;

		//整理数据
		$datas = array();
		isset($data['isloadpic']) ? $datas['isloadpic'] = $data['isloadpic'] : null;
		isset($data['nickname'])  ? $datas['nickname']  = $data['nickname']  : null;
		isset($data['usergroup']) ? $datas['usergroup'] = $data['usergroup'] : null;
		isset($data['username'])  ? $datas['username']  = $data['username']  : null;
		isset($data['gender'])    ? $datas['gender']    = $data['gender']    : null;
		isset($data['phone'])     ? $datas['phone']     = $data['phone']     : null;

		$return = M('User')->where(array('id'=>$userid))->save($data);

		return $return;
	}

	/**
	 * 检查用户手机号码是否存在
	 * @return boolean true已存在 false不存在
	 */
	public function CKPhoneExists($phone=null)
	{
		if (!$phone) return false;

		$where = array(
			'status' => null
		);
		$userinfo = $this->getUser($phone,0,1,$where);

		return is_array($userinfo)&&!empty($userinfo) ? true : false;
	}
}
