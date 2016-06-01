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
		if ($username) $where['_complex'] = array(
			'_logic'   => 'or',
			'phone'    => array('like', '%'.$username.'%'),
			'username' => array('like', '%'.$username.'%'),
		);
		if ($source!==null) $where['source'] = $source;

		$total = M('user')->where($where)->count();
		$data = M('user')->where($where)->order('registtime asc')->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取用户信息-通过ID
	public function getUserByID($userid=null)
	{
		if (!$userid) return false;

		$userinfo = $this->getUser($userid);

		return $userinfo['total'] ? current($userinfo['data']) : array();
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

	//获取意见反馈
	public function getLvword($userid=null, $content=null, $start=0, $length=9999)
	{
		$where = array();
		if ($userid) $where['userid'] = is_array($userid) ? array('in', $userid) : $userid;
		if ($content) $where['content'] = array('like', '%'.$content.'%');

		$total = M('lvword')->alias('a')->where($where)->count();
		$data = M('lvword')->alias('a')
						   ->field('a.*, b.username, b.phone')
						   ->join(' __USER__ b on a.userid=b.userid ')
						   ->where($where)
						   ->order('createtime asc')
						   ->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取用户设备信息
	public function getDevicelog($username=null, $devicekeyword=null, $start=0, $length=9999)
	{
		$where = array();
		if ($username) $where['b.username'] = array('like', '%'.$username.'%');
		if ($devicekeyword) $where['_complex'] = array(
			'_logic' => 'or',
			'a.deviceid' => 'like', '%'.$devicekeyword.'%',
			'a.devicetype' => 'like', '%'.$devicekeyword.'%',
			'a.deviceos' => 'like', '%'.$devicekeyword.'%',
		);

		$total = M('devicelog')->where($where)->count();
		$data = M('devicelog')->alias('a')->join(' left join __USER__ b on a.userid=b.userid ')->where($where)->order('createtime asc')->limit($start,$length)->select();

		return array('total'=>$total, 'data'=>$data);
	}
}