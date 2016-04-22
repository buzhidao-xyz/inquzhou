<?php
/**
 * 专题配置信息
 * buzhidao
 * wbq@xlh-tech.com
 * 2016-04-15
 *
 * fields.key : 字段名
 * fields.value : 字段配置
 * fields.value.field : 字段名
 * fields.value.name : 字段说明
 * fields.value.inup : 是否新增编辑
 * fields.value.need : 是否必填
 * fields.value.show : 是否列表显示
 * fields.value.search : 是否表单搜索
 * fields.value.excel : excel导入对应列
 */
return array(
	1 => array(
		'id'     => 1,
		'table'  => 'bikestation',
		'title'  => '公共自行车',
		'fields' => array(
			'stationcap' => array(
				'field'  => 'stationcap',
				'name'   => '名称',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'A',
			),
			'address' => array(
				'field'  => 'address',
				'name'   => '地址',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'B',
			),
			'guardstatus' => array(
				'field'  => 'guardstatus',
				'name'   => '值守状态',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'C',
			),
			'servicetel' => array(
				'field'  => 'servicetel',
				'name'   => '服务电话',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'D',
			),
			'servicetime' => array(
				'field'  => 'servicetime',
				'name'   => '服务时间',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'E',
			),
			'remark' => array(
				'field'  => 'remark',
				'name'   => '备注',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'F',
			),
			'serviceid' => array(
				'field'  => 'serviceid',
				'name'   => '编号',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'F',
			),
			'type' => array(
				'field'  => 'type',
				'name'   => '类型',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'G',
			),
			'bikecount' => array(
				'field'  => 'bikecount',
				'name'   => '自行车总量',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'H',
			),
			'point_x' => array(
				'field'  => 'point_x',
				'name'   => '经度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'I',
			),
			'point_y' => array(
				'field'  => 'point_y',
				'name'   => '纬度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'J',
			),
		)
	),
	2 => array(
		'id'     => 2,
		'table'  => 'park',
		'title'  => '公共停车场',
		'fields' => array(
			'caption' => array(
				'field'  => 'caption',
				'name'   => '名称',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'A',
			),
			'type' => array(
				'field'  => 'type',
				'name'   => '类型',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'B',
			),
			'pricedes' => array(
				'field'  => 'pricedes',
				'name'   => '收费标准',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'C',
			),
			'totalcount' => array(
				'field'  => 'totalcount',
				'name'   => '车位数量',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'D',
			),
			'address' => array(
				'field'  => 'address',
				'name'   => '地址',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'E',
			),
			'servicetime' => array(
				'field'  => 'servicetime',
				'name'   => '服务时间',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'F',
			),
			'administra' => array(
				'field'  => 'administra',
				'name'   => '管理单位',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'G',
			),
			'tel' => array(
				'field'  => 'tel',
				'name'   => '联系电话',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'H',
			),
			'remark' => array(
				'field'  => 'remark',
				'name'   => '备注',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'I',
			),
			'ischarged' => array(
				'field'  => 'ischarged',
				'name'   => '服务方式',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'J',
			),
			'point_x' => array(
				'field'  => 'point_x',
				'name'   => '经度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'K',
			),
			'point_y' => array(
				'field'  => 'point_y',
				'name'   => '纬度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'L',
			),
		)
	),
	3 => array(
		'id'     => 3,
		'table'  => 'gasstation',
		'title'  => '加油站',
		'fields' => array(
			'name' => array(
				'field'  => 'name',
				'name'   => '名称',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'A',
			),
			'address' => array(
				'field'  => 'address',
				'name'   => '地址',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'B',
			),
			'point_x' => array(
				'field'  => 'point_x',
				'name'   => '经度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'C',
			),
			'point_y' => array(
				'field'  => 'point_y',
				'name'   => '纬度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'D',
			),
		)
	),
	4 => array(
		'id'     => 4,
		'table'  => 'hospital',
		'title'  => '医疗',
		'fields' => array(
			'region' => array(
				'field'  => 'region',
				'name'   => '区域',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'A',
			),
			'name' => array(
				'field'  => 'name',
				'name'   => '名称',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'B',
			),
			'secondname' => array(
				'field'  => 'secondname',
				'name'   => '别名',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 1,
				'excel'  => 'C',
			),
			'address' => array(
				'field'  => 'address',
				'name'   => '地址',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'D',
			),
			'category' => array(
				'field'  => 'category',
				'name'   => '类型',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'E',
			),
			'level' => array(
				'field'  => 'level',
				'name'   => '等级',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'F',
			),
			'postcode' => array(
				'field'  => 'postcode',
				'name'   => '邮编',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'G',
			),
			'point_x' => array(
				'field'  => 'point_x',
				'name'   => '经度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'H',
			),
			'point_y' => array(
				'field'  => 'point_y',
				'name'   => '纬度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'I',
			),
		)
	),
	5 => array(
		'id'     => 5,
		'table'  => 'farmhouse',
		'title'  => '农家乐',
		'fields' => array(
			'folder' => array(
				'field'  => 'folder',
				'name'   => '图集文件夹',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'A',
			),
			'point_x' => array(
				'field'  => 'point_x',
				'name'   => '经度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'B',
			),
			'point_y' => array(
				'field'  => 'point_y',
				'name'   => '纬度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'C',
			),
			'name' => array(
				'field'  => 'name',
				'name'   => '名称',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'D',
			),
			'fullname' => array(
				'field'  => 'fullname',
				'name'   => '全称',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 1,
				'excel'  => 'E',
			),
			'company' => array(
				'field'  => 'company',
				'name'   => '机构名称',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 1,
				'excel'  => 'F',
			),
			'address' => array(
				'field'  => 'address',
				'name'   => '具体地址',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'G',
			),
			'postcode' => array(
				'field'  => 'postcode',
				'name'   => '邮编',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'H',
			),
			'contactor' => array(
				'field'  => 'contactor',
				'name'   => '联系人',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'I',
			),
			'telephone' => array(
				'field'  => 'telephone',
				'name'   => '联系电话',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'J',
			),
			'mobilephone' => array(
				'field'  => 'mobilephone',
				'name'   => '手机',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'K',
			),
			'website' => array(
				'field'  => 'website',
				'name'   => '网址',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'L',
			),
			'totalcount' => array(
				'field'  => 'totalcount',
				'name'   => '全村总户数',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'M',
			),
			'farmcount' => array(
				'field'  => 'farmcount',
				'name'   => '经营农家乐户数',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'N',
			),
			'areasize' => array(
				'field'  => 'areasize',
				'name'   => '区域面积',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'O',
			),
			'persons' => array(
				'field'  => 'persons',
				'name'   => '可招待人数',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'P',
			),
			'bads' => array(
				'field'  => 'bads',
				'name'   => '床位数',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'Q',
			),
			'workmans' => array(
				'field'  => 'workmans',
				'name'   => '从业人员数',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'R',
			),
		)
	),
	6 => array(
		'id'     => 6,
		'table'  => 'culture',
		'title'  => '文物',
		'fields' => array(
			'region' => array(
				'field'  => 'region',
				'name'   => '区域',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 1,
				'excel'  => 'A',
			),
			'folder' => array(
				'field'  => 'folder',
				'name'   => '图集文件夹',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'B',
			),
			'caption' => array(
				'field'  => 'caption',
				'name'   => '名称',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'C',
			),
			'address' => array(
				'field'  => 'address',
				'name'   => '地址',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'D',
			),
			'stime' => array(
				'field'  => 'stime',
				'name'   => '时代',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'E',
			),
			'opentime' => array(
				'field'  => 'opentime',
				'name'   => '公布时间',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'F',
			),
			'level' => array(
				'field'  => 'level',
				'name'   => '级别',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'G',
			),
			'category' => array(
				'field'  => 'category',
				'name'   => '大类',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'H',
			),
			'seccategory' => array(
				'field'  => 'seccategory',
				'name'   => '小类',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 0,
				'search' => 0,
				'excel'  => 'I',
			),
			'point_x' => array(
				'field'  => 'point_x',
				'name'   => '经度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'J',
			),
			'point_y' => array(
				'field'  => 'point_y',
				'name'   => '纬度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'K',
			),
		)
	),
	7 => array(
		'id'     => 7,
		'table'  => 'wastestation',
		'title'  => '垃圾中转站',
		'fields' => array(
			'caption' => array(
				'field'  => 'caption',
				'name'   => '名称',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'A',
			),
			'address' => array(
				'field'  => 'address',
				'name'   => '地址',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'B',
			),
			'point_x' => array(
				'field'  => 'point_x',
				'name'   => '经度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'C',
			),
			'point_y' => array(
				'field'  => 'point_y',
				'name'   => '纬度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'D',
			),
		)
	),
	8 => array(
		'id'     => 8,
		'table'  => 'latrine',
		'title'  => '公共厕所',
		'fields' => array(
			'caption' => array(
				'field'  => 'caption',
				'name'   => '名称',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'A',
			),
			'address' => array(
				'field'  => 'address',
				'name'   => '地址',
				'inup'   => 1,
				'need'   => 0,
				'show'   => 1,
				'search' => 1,
				'excel'  => 'B',
			),
			'point_x' => array(
				'field'  => 'point_x',
				'name'   => '经度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'C',
			),
			'point_y' => array(
				'field'  => 'point_y',
				'name'   => '纬度',
				'inup'   => 1,
				'need'   => 1,
				'show'   => 1,
				'search' => 0,
				'excel'  => 'D',
			),
		)
	),
);