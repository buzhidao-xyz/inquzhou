<?php
/**
 * 系统菜单
 * wangbaoqing@xlh-tech.com
 * 2016-04-12
 */
return array(
	1 => array(
        'groupid'   => 1,
        'groupname' => '管理中心',
        'class'     => 'a-blue',
        'icon'      => 'fa-desktop',
        'nodelist'  => array(
            1 => array(
                'nodeid'   => 1,
                'nodename' => '控制面板',
                'control'  => 'Index',
                'action'   => 'dashboard',
                'icon'     => 'fa-home',
                'nodelist' => array(),
            ),
            2 => array(
                'nodeid'   => 2,
                'nodename' => '用户管理',
                'control'  => 'User',
                'action'   => 'index',
                'icon'     => 'fa-user',
                'nodelist' => array(
                    3 => array(
                        'nodeid'   => 3,
                        'nodename' => '新增用户',
                        'control'  => 'User',
                        'action'   => 'newuser',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                    4 => array(
                        'nodeid'   => 4,
                        'nodename' => '管理用户',
                        'control'  => 'User',
                        'action'   => 'userlist',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                ),
            ),
        ),
    ),
);