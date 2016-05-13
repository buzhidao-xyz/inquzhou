<?php
/**
 * 管理员管理 业务
 * wangbaoqing@xlh-tech.com
 * 2016-05-13
 */
namespace Admin\Controller;

use Org\Util\Filter;

class ManagerController extends CommonController
{
	public function __construct()
	{
		parent::__construct();
	}

	//修改密码
	public function chpasswd()
	{
		$this->display();
	}

	//修改密码-保存
	public function chpasswdsave()
	{
		$managerid = $this->managerinfo['managerid'];
		$managerinfo = D('Manager')->getManagerByID($managerid);

		$opasswd = mRequest('opasswd');
		if (!Filter::F_Password($opasswd) || D('Manager')->passwordEncrypt($opasswd)!=$managerinfo['passwd']) {
			$this->ajaxReturn(1, '原密码不正确！');
		}

		$passwd = mRequest('passwd');
		if (!Filter::F_Password($opasswd)) $this->ajaxReturn(1, '新密码不符合规则！');
		$passwdc = mRequest('passwdc');
		if ($passwd != $passwdc) $this->ajaxReturn(1, '两次输入的密码不一致！');

		$result = M('manager')->where(array('managerid'=>$managerid))->save(array(
			'passwd' => D('Manager')->passwordEncrypt($passwd),
			'updatetime' => TIMESTAMP
		));
		if ($result) {
			$this->ajaxReturn(0, '修改成功！');
		} else {
			$this->ajaxReturn(1, '修改失败！');
		}
	}
}