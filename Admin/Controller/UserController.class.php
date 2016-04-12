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

	//新增用户
	public function newuser()
	{
		$this->display();
	}

	//新增用户-保存
	public function newusersave()
	{
		$this->display();
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