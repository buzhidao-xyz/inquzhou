<?php
/**
 * 用户模型
 * wbq@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Model;

class UserModel extends CommonModel
{
	public function __construct()
	{
		parent::__construct();
	}

	//密码加密
    public function passwordEncrypt($password=null)
    {
        return md5($password);
    }

	//获取用户信息
	public function getUser($userid=null, $username=null, $source=null, $start=0, $length=9999)
	{
		$where = array();
		if ($userid) $where['userid'] = is_array($userid) ? array('in', $userid) : $userid;
		if ($username) $where['username'] = array('like', '%'.$username.'%');
		if ($source!==null) $where['source'] = $source;

		$total = M('user')->where($where)->count();
		$data = M('user')->where($where)->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取用户信息-通过ID
	public function getUserByID()
	{
		if (!$userid) return false;

		$userinfo = $this->getUser($userid);

		return $userinfo['total'] ? current($userinfo) : array();
	}

	//保存用户信息
	public function saveuser($userid=null, $data=array())
	{
		if (!is_array($data) || empty($data)) return false;

		if ($userid) {
			$result = M('user')->where(array('userid'=>$userid))->save($data);
			$result ? $result = $userid : null;
		} else {
			$result = M('user')->add($data);
		}

		return $result;
	}
}