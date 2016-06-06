<?php
/**
 * 专题管理 业务
 * wangbaoqing@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Controller;

use Org\Util\Filter;

class TopicController extends CommonController
{
	public function __construct()
	{
		parent::__construct();

		$this->topicmap = C('TOPIC');
	}

	//获取专题id
	private function _getTopicid($ck=false, $ajax=true)
	{
		$topicid = mRequest('topicid');
		$this->assign('topicid', $topicid);

		$ck&&$ajax&&!$topicid ? $this->ajaxReturn(1, '未知专题信息！') : null;
		$ck&&!$ajax&&!$topicid ? $this->pageReturn(1, '未知专题信息！') : null;

		return $topicid;
	}

	//获取专题名称
	private function _getTitle($ck=false, $ajax=true)
	{
		$title = mRequest('title');
		$this->assign('title', $title);

		$ck&&$ajax&&!$title ? $this->ajaxReturn(1, '未知专题名称！') : null;
		$ck&&!$ajax&&!$title ? $this->pageReturn(1, '未知专题名称！') : null;

		return $title;
	}

	//获取图片
	private function _getPic($ck=false, $ajax=true)
	{
		$pic = mRequest('pic');
		$this->assign('pic', $pic);

		$ck&&$ajax&&!$pic ? $this->ajaxReturn(1, '未知图片！') : null;
		$ck&&!$ajax&&!$pic ? $this->pageReturn(1, '未知图片！') : null;

		return $pic;
	}

	//获取专题作者
	private function _getAuthor($ck=false, $ajax=true)
	{
		$author = mRequest('author');
		$this->assign('author', $author);

		$ck&&$ajax&&!$author ? $this->ajaxReturn(1, '未知作者！') : null;
		$ck&&!$ajax&&!$author ? $this->pageReturn(1, '未知作者！') : null;

		return $author;
	}

	//获取描述
	private function _getDesc($ck=false, $ajax=true)
	{
		$desc = mRequest('desc');
		$this->assign('desc', $desc);

		$ck&&$ajax&&!$desc ? $this->ajaxReturn(1, '未知描述！') : null;
		$ck&&!$ajax&&!$desc ? $this->pageReturn(1, '未知描述！') : null;

		return $desc;
	}

	//获取关键字
	private function _getKeywords()
	{
		$keywords = mRequest('keywords');
		$this->assign('keywords', $keywords);

		return $keywords;
	}

	//获取itemid
	private function _getItemid($ck=false, $ajax=true)
	{
		$itemid = mRequest('itemid');
		$this->assign('itemid', $itemid);

		$ck&&$ajax&&!$itemid ? $this->ajaxReturn(1, '未知专题点！') : null;
		$ck&&!$ajax&&!$itemid ? $this->pageReturn(1, '未知专题点！') : null;

		return $itemid;
	}

	//获取专题点字段field
	private function _getTopicitemfield($field=null, $name=null, $ck=false)
	{
		$value = mRequest($field);
		$this->assign($field, $value);

		$ck&&!$value ? $this->ajaxReturn(1, '未知'.$name.'！') : null;

		return $value;
	}

	//上传图片
	public function picupload()
	{
		$upload = new \Think\Upload();
		$upload->maxSize  = 5242880; //5M
		$upload->exts     = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = APP_PATH;
		$upload->savePath = '/Upload/topic/';
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

	public function index(){}

	//专题
	public function topic()
	{
        $data = D('Topic')->getTopic();

        $this->assign('datalist', $data);

		$this->display();
	}

	//编辑专题
	public function uptopic()
	{
		$topicid = $this->_getTopicid(true, false);

		//获取专题信息
		$topicinfo = D('Topic')->getTopicByID($topicid);
		$this->assign('topicinfo', $topicinfo);

		$this->display();
	}

	//编辑专题 - 保存
	public function uptopicsave()
	{
		$topicid = $this->_getTopicid(true);
		$title = $this->_getTitle(true);
		$pic = $this->_getPic(true);
		$author = $this->_getAuthor(true);
		$desc = $this->_getDesc(true);

		$data = array(
			'title'      => $title,
			'pic'        => $pic,
			'author'     => $author,
			'desc'       => $desc,
			'updatetime' => TIMESTAMP,
		);
		$result = D('Topic')->savetopic($topicid, $data);
		if ($result) {
			$this->ajaxReturn(0, '保存成功！');
		} else {
			$this->ajaxReturn(1, '保存失败！');
		}
	}

	//专题点
	public function topicitem()
	{
		$topicid = $this->_getTopicid(true);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->pageReturn(1, '未知专题！');

		$this->assign('topicmapinfo', $topicmapinfo);

		$keywords = $this->_getKeywords();

        list($start, $length) = $this->_mkPage();
        $data = D('Topic')->getTopicitem($topicid, null, $keywords, $start, $length);
        $total = $data['total'];
        $datalist = $data['data'];

        $this->assign('datalist', $datalist);

        $params = array(
            'keywords' => $keywords,
        );
        $this->assign('params', $params);
        //解析分页数据
        $this->_mkPagination($total, $params);

		$this->display();
	}

	//专题点 - 新增
	public function newtopicitem()
	{
		$topicid = $this->_getTopicid(true, false);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->pageReturn(1, '未知专题！');

		$this->assign('topicmapinfo', $topicmapinfo);

		$this->display();
	}

	//专题点 - 新增 - 保存
	public function newtopicitemsave()
	{
		$topicid = $this->_getTopicid(true, false);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->pageReturn(1, '未知专题！');

		$this->assign('topicmapinfo', $topicmapinfo);

		//专题数据data
		$data = array();
		foreach ($topicmapinfo['fields'] as $field) {
			if ($field['inup']) {
				$fvalue = $this->_getTopicitemfield($field['field'], $field['name'], $field['need']);

				$data[$field['field']] = $fvalue;
			}
		}
		$data['createtime'] = TIMESTAMP;

		$result = D('Topic')->saveTopicitem($topicid, null, $data);
		if ($result) {
			$this->ajaxReturn(0, '保存成功！');
		} else {
			$this->ajaxReturn(1, '保存失败！');
		}
	}

	//专题点 - 编辑
	public function uptopicitem()
	{
		$topicid = $this->_getTopicid(true, false);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->pageReturn(1, '未知专题！');

		$this->assign('topicmapinfo', $topicmapinfo);

		$itemid = $this->_getItemid(true, false);

		$topiciteminfo = D('Topic')->getTopicitem($topicid, $itemid);
		if (!$topiciteminfo['total']) $this->pageReturn(1, '未知专题点！');

		$topiciteminfo = current($topiciteminfo['data']);

		$this->assign('point_x', (double)$topiciteminfo['point_x']);
		$this->assign('point_y', (double)$topiciteminfo['point_y']);
		$this->assign('topiciteminfo', $topiciteminfo);
		$this->display();
	}

	//专题点 - 编辑 - 保存
	public function uptopicitemsave()
	{
		$topicid = $this->_getTopicid(true);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->ajaxReturn(1, '未知专题！');

		$this->assign('topicmapinfo', $topicmapinfo);

		$itemid = $this->_getItemid(true);

		//专题数据data
		$data = array();
		foreach ($topicmapinfo['fields'] as $field) {
			if ($field['inup']) {
				$fvalue = $this->_getTopicitemfield($field['field'], $field['name'], $field['need']);

				$data[$field['field']] = $fvalue;
			}
		}
		$data['updatetime'] = TIMESTAMP;

		$result = D('Topic')->saveTopicitem($topicid, $itemid, $data);
		if ($result) {
			$this->ajaxReturn(0, '保存成功！');
		} else {
			$this->ajaxReturn(1, '保存失败！');
		}
	}

	//上传图片
	public function topicitempicupload()
	{
		$upload = new \Think\Upload();
		$upload->maxSize  = 5242880; //5M
		$upload->exts     = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = APP_PATH;
		$upload->savePath = '/Upload/topic/';
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

	//专题点 - 图集
	public function topicitempics()
	{
		$topicid = $this->_getTopicid(true);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->ajaxReturn(1, '未知专题！');

		$this->assign('topicmapinfo', $topicmapinfo);

		$itemid = $this->_getItemid(true);

		$topiciteminfo = D('Topic')->getTopicitem($topicid, $itemid);
		if (!$topiciteminfo['total']) $this->pageReturn(1, '未知专题点！');

		$topiciteminfo = current($topiciteminfo['data']);

		//获取专题点图集信息
		$topicitempics = D('Topic')->getTopicitempics($topicid, $itemid);
		$topiciteminfo['pics'] = $topicitempics;

		$this->assign('picnum', range(0,29));

		$this->assign('topiciteminfo', $topiciteminfo);
		$this->display();
	}

	//专题点 - 图集 - 保存
	public function topicitempicssave()
	{
		$topicid = $this->_getTopicid(true);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->ajaxReturn(1, '未知专题！');

		$this->assign('topicmapinfo', $topicmapinfo);

		$itemid = $this->_getItemid(true);

		//获取图集图片路径
		$data = array();
		$picnum = range(0,29);
		foreach ($picnum as $num) {
			$pic = mRequest('pic'.$num);
			if ($pic) {
				$data[] = array(
					'topicid'    => $topicid,
					'itemid'     => $itemid,
					'pic'        => $pic,
					'createtime' => TIMESTAMP,
				);
			}
		}

		$result = D('Topic')->saveTopicitempics($topicid, $itemid, $data);
		if ($result) {
			$this->ajaxReturn(0, '保存成功！');
		} else {
			$this->ajaxReturn(1, '保存失败！');
		}
	}

	//导入专题点
	public function importtopicitem()
	{
		$topicid = $this->_getTopicid(true);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->ajaxReturn(1, '未知专题！');

		$this->assign('topicmapinfo', $topicmapinfo);

		$this->display();
	}

	//导入专题点Excel
	public function topicexcelimport()
	{
		$topicid = $this->_getTopicid(true);
		$topicmapinfo = $this->topicmap[$topicid];
		if (!is_array($topicmapinfo)||empty($topicmapinfo)) $this->ajaxReturn(1, '未知专题类型！');

		//解析导入字段
		$namefield = null;
		$addressfield = null;
		$excelfields = array();
		foreach ($topicmapinfo['fields'] as $field) {
			if ($field['apifield'] == 'name') $namefield = $field['field'];
			if ($field['apifield'] == 'address') $addressfield = $field['field'];

			$excelfields[$field['excel']] = $field['field'];
		}
		$picfields = array();
		foreach ($topicmapinfo['pics'] as $d) {
			$picfields[$d['excel']] = $d['field'];
		}

		$topicpics_uploadpath = '/Upload/topic/';

		$upload = new \Think\Upload();
		$upload->maxSize  = 5242880; //5M
		$upload->exts     = array('xls', 'xlsx');
		$upload->rootPath = APP_PATH;
		$upload->savePath = '/Upload/topic/excel/';
		$upload->saveName = array('uniqid','');
		$upload->subName  = array('date','Y/md');
		$info = $upload->upload();

		$error = null;
        $msg = '导入成功！';
        $data = array();
		if (!$info) {
			$error = 1;
			$msg = $upload->getError();
		} else {
			$fileinfo = current($info);
			$excelfile = APP_PATH.$fileinfo['savepath'].$fileinfo['savename'];

			//导入数据
			$data = array(
				'success' => 0,
				'failure' => 0,
				'result'  => array(),
			);
			//Excel数据
			$ExcelData = $this->_readExcel($excelfile);
			//解析数据
			$datas = array();
			array_shift($ExcelData);
			foreach ($ExcelData as $dcell) {
				$ddd = array();
				$picsddd = array();
				$picfolder = '';
				$subfolder = '';
				$picfile = array();
				foreach ($dcell as $colname=>$value) {
					if (isset($excelfields[$colname])) {
						//解析数据类型
						switch ($excelfields[$colname]) {
							case 'point_x':
								$value = (double)$value;
							break;
							case 'point_y':
								$value = (double)$value;
							break;
							default:
							break;
						}

						strtoupper($value)=='NULL' ? $value='' : null;
						$ddd[$excelfields[$colname]] = $value;
					}

					if (isset($picfields[$colname])) {
						if ($picfields[$colname] == 'picfolder') {
							$picfolder = $value.'/';
						}
						if ($picfields[$colname] == 'subfolder') {
							$subfolder = $value.'/';
						}
					}
				}

				//遍历图集目录-收集图片
				if ($picfolder || $subfolder) {
					$dir = APP_PATH.$topicpics_uploadpath.$picfolder.$subfolder;
					if (is_dir($dir) && $dh=opendir($dir)) {
						while (($file = readdir($dh)) !== false) {
							$file = iconv('GB2312', 'UTF-8', $file);
							if ($file && preg_match("/(\.jpg|\.png|\.jpeg|\.gif)$/i", $file)) {
								$picsddd[] = $topicpics_uploadpath.$picfolder.$subfolder.$file;
							}
						}
						closedir($dh);
					}
				}

				if (!$ddd[$namefield] && !$ddd[$addressfield] && !$ddd['point_x'] && !$ddd['point_y']) continue;

				//查询是否已经存在该名字的专题点
				$topiciteminfo = D('Topic')->getTopicitemByName($topicid, $ddd[$namefield]);
				if (!empty($topiciteminfo)) {
					foreach ($ddd as $key=>$value) {
						if (!$value && $topiciteminfo[$key]) $ddd[$key] = $topiciteminfo[$key];
					}
					$ddd['updatetime'] = TIMESTAMP;
					$result = D('Topic')->saveTopicitem($topicid, $topiciteminfo['itemid'], $ddd);
					if ($result && is_array($picsddd) && !empty($picsddd)) {
						//删除原来的关联图集
						M('topic_pics')->where(array('topicid'=>$topicid, 'itemid'=>$topiciteminfo['itemid']))->delete();
						//关联新图集
						$picsdddddd = array();
						foreach ($picsddd as $d) {
							$picsdddddd[] = array(
								'topicid'    => $topicid,
								'itemid'     => $topiciteminfo['itemid'],
								'pic'        => $d,
								'createtime' => TIMESTAMP,
							);
						}
						M('topic_pics')->addAll($picsdddddd);
					}
				} else {
					$ddd['createtime'] = TIMESTAMP;
					$ddd['updatetime'] = TIMESTAMP;
					$result = D('Topic')->saveTopicitem($topicid, null, $ddd);
					if ($result && is_array($picsddd) && !empty($picsddd)) {
						$itemid = $result;
						//关联新图集
						$picsdddddd = array();
						foreach ($picsddd as $d) {
							$picsdddddd[] = array(
								'topicid'    => $topicid,
								'itemid'     => $itemid,
								'pic'        => $d,
								'createtime' => TIMESTAMP,
							);
						}
						M('topic_pics')->addAll($picsdddddd);
					}
				}

				if ($result) {
					$data['success']++;
					$data['result'][] = $ddd[$namefield]." - <font color='green'>导入成功！</font>\r\n";
				} else {
					$data['failure']++;
					$data['result'][] = $ddd[$namefield]." - <font color='red'>导入失败！</font>\r\n";
				}
			}
		}

        $this->ajaxReturn($error, $msg, $data);
	}

	//获取ExcelData
	private function _readExcel($excelfile=null)
	{
		if (!$excelfile) return false;

		//加载EXCEL
		include(VENDOR_PATH.'PHPExcel-1.8.1/PHPExcel.php');
		include(VENDOR_PATH.'PHPExcel-1.8.1/PHPExcel/IOFactory.php');
		include(VENDOR_PATH.'PHPExcel-1.8.1/PHPExcel/Reader/Excel5.php');
		include(VENDOR_PATH.'PHPExcel-1.8.1/PHPExcel/Reader/Excel2007.php');

		$fileinfo = pathinfo($excelfile);
		if ($fileinfo['extension'] == 'xls') {
			$excelObjReader = \PHPExcel_IOFactory::createReader('Excel5');
		} else if ($fileinfo['extension'] == 'xlsx') {
			$excelObjReader = \PHPExcel_IOFactory::createReader('Excel2007');
		}
		$excelObjReader->setReadDataOnly(true);
		$excelObjPHPExcel = $excelObjReader->load($excelfile);
		$excelObjWorksheet = $excelObjPHPExcel->getActiveSheet();

		$highestRow = $excelObjWorksheet->getHighestRow();
		$highestColumn = $excelObjWorksheet->getHighestColumn();

		// $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

		$excelData = array();
		for ($row=1; $row<=$highestRow; $row++) {
			for ($col='A'; $col<=$highestColumn; $col++) {
				$excelData[$row][$col] = (string)$excelObjWorksheet->getCell($col.$row)->getValue();
			}
		}

		return $excelData;
	}
}