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
                    5 => array(
                        'nodeid'   => 5,
                        'nodename' => '收藏点',
                        'control'  => 'Fav',
                        'action'   => 'favplace',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                    6 => array(
                        'nodeid'   => 6,
                        'nodename' => '收藏路线',
                        'control'  => 'Fav',
                        'action'   => 'favline',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                    7 => array(
                        'nodeid'   => 7,
                        'nodename' => '标注地点',
                        'control'  => 'Place',
                        'action'   => 'markplace',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                    8 => array(
                        'nodeid'   => 8,
                        'nodename' => '新增地点',
                        'control'  => 'Place',
                        'action'   => 'ptplace',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                    9 => array(
                        'nodeid'   => 9,
                        'nodename' => '纠错地点',
                        'control'  => 'Place',
                        'action'   => 'pmplace',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                    10 => array(
                        'nodeid'   => 10,
                        'nodename' => '意见反馈',
                        'control'  => 'User',
                        'action'   => 'lvword',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                    11 => array(
                        'nodeid'   => 11,
                        'nodename' => '用户设备',
                        'control'  => 'User',
                        'action'   => 'device',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                ),
            ),
            12 => array(
                'nodeid'   => 12,
                'nodename' => '专题管理',
                'control'  => 'Topic',
                'action'   => 'index',
                'icon'     => 'fa-leaf',
                'nodelist' => array(
                    13 => array(
                        'nodeid'   => 13,
                        'nodename' => '专题类型',
                        'control'  => 'Topic',
                        'action'   => 'topiclist',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                ),
            ),
            14 => array(
                'nodeid'   => 14,
                'nodename' => '系统设置',
                'control'  => 'System',
                'action'   => 'index',
                'icon'     => 'fa-gear',
                'nodelist' => array(
                    15 => array(
                        'nodeid'   => 15,
                        'nodename' => '图层设置',
                        'control'  => 'Map',
                        'action'   => 'layer',
                        'icon'     => '',
                        'nodelist' => array(),
                    ),
                ),
            ),
        ),
    ),
);