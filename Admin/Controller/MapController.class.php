<?php
/**
 * 地图管理 业务
 * wangbaoqing@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Controller;

use Org\Util\Filter;

class MapController extends CommonController
{
	public function __construct()
	{
		parent::__construct();
	}

	//获取title
	private function _getTitle()
	{
		$title = mRequest('title');
		if (!$title) $this->ajaxReturn(1, '请填写标题！');

		return $title;
	}

	//获取version
	private function _getVersion()
	{
		$version = mRequest('version');
		if (!$version) $this->ajaxReturn(1, '请填写版本号！');

		return $version;
	}

	//获取path、httppath
	private function _getPath()
	{
		$path = mRequest('path');
		$httppath = mRequest('httppath');

		if (!$path && !$httppath) $this->ajaxReturn(1, '请上传地图包或填写远程URL地址！');
		if ($httppath && !preg_match("/^http:\/\//", $httppath)) $this->ajaxReturn(1, '地图包远程地址不正确！');

		return $httppath ? $httppath : C('HOST.HTTP_HOST').$path;
	}

	//获取size
	private function _getSize()
	{
		$size = mRequest('size');
		if (!$size) $this->ajaxReturn(1, '请填写地图包大小！');

		return $size;
	}

	public function index(){}

	//上传地图
	public function mapupload()
	{
		$upload = new \Think\Upload();
		$upload->maxSize  = 524288000; //500M
		$upload->exts     = array('zip');
		$upload->rootPath = APP_PATH;
		$upload->savePath = '/Upload/map/';
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

	//离线地图
	public function offline()
	{
        $keywords = mRequest('keywords');
        $this->assign('keywords', $keywords);

        list($start, $length) = $this->_mkPage();
        $data = D('Map')->getMap(null, $keywords, $start, $length);
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

	//新增离线地图
	public function newoffline()
	{
		$this->display();
	}

	//新增离线地图-保存
	public function newofflinesave()
	{
		$title = $this->_getTitle();
		$version = $this->_getVersion();
		$path = $this->_getPath();
		$size = $this->_getSize();

		$data = array(
			'title'      => $title,
			'path'       => $path,
			'version'    => $version,
			'size'       => $size,
			'uploadtime' => TIMESTAMP,
		);
		$result = M('map')->add($data);
		if ($result) {
			$this->ajaxReturn(0, '保存成功！');
		} else {
			$this->ajaxReturn(1, '保存失败！');
		}
	}

	//删除离线地图
	public function deloffline()
	{
		$mapid = mRequest('mapid');
		if (!$mapid) $this->ajaxReturn(1, '未知离线地图ID！');

		$result = M('map')->where(array('mapid'=>$mapid))->delete();
		if ($result) {
			$this->ajaxReturn(0, '删除成功！');
		} else {
			$this->ajaxReturn(1, '删除失败！');
		}
	}
}