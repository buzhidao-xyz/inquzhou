<?php
/**
 * 系统管理 业务
 * wangbaoqing@xlh-tech.com
 * 2016-04-18
 */
namespace Admin\Controller;

use Org\Util\Filter;

class SystemController extends CommonController
{
	//地图设置参数
	private $_mapconfig = array(
		'maplayer_road' => array('type'=>'int', 'key'=>'maplayer_road'),
	);

	public function __construct()
	{
		parent::__construct();
	}

	//获取图层名称
	private function _getTitle($ck=false, $ajax=true)
	{
		$title = mRequest('title');
		$this->assign('title', $title);

		$ck&&!$title&&$ajax ? $this->ajaxReturn(1, '请填写名称！') : null;
		$ck&&!$title&&!$ajax ? $this->pageReturn(1, '请填写名称！') : null;

		return $title;
	}

	//获取图层接口url
	private function _getUrl($ck=false, $ajax=true)
	{
		$url = mRequest('url');
		$this->assign('url', $url);

		$ck&&!$url&&$ajax ? $this->ajaxReturn(1, '请填写接口地址！') : null;
		$ck&&!$url&&!$ajax ? $this->pageReturn(1, '请填写接口地址！') : null;

		return $url;
	}

	//获取图层接口url
	private function _getIcon($ck=false, $ajax=true)
	{
		$icon = mRequest('icon');
		$this->assign('icon', $icon);

		$ck&&!$icon&&$ajax ? $this->ajaxReturn(1, '请上传图标！') : null;
		$ck&&!$icon&&!$ajax ? $this->pageReturn(1, '请上传图标！') : null;

		return $icon;
	}

	//获取configvalue
	private function _getConfigvalue($configtype=null, $configkey=null)
	{
		$configvalue = mRequest($configkey);
		$configvalue = D('System')->configvalueFormat($configtype, $configvalue);
		$this->assign($configkey, $configvalue);

		return $configvalue;
	}

	public function index(){}

	//上传icon
	public function iconupload()
	{
		$upload = new \Think\Upload();
		$upload->maxSize  = 5242880; //5M
		$upload->exts     = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = APP_PATH;
		$upload->savePath = '/Upload/layer/';
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

	//图层设置
	public function layer()
	{
        $keywords = mRequest('keywords');
        $this->assign('keywords', $keywords);

        list($start, $length) = $this->_mkPage();
        $data = D('System')->getLayer(null, $keywords, $start, $length);
        $total = $data['total'];
        $datalist = $data['data'];

        $this->assign('datalist', $datalist);

        $params = array(
            'keywords' => $keywords,
        );
        $this->assign('param', $params);
        //解析分页数据
        $this->_mkPagination($total, $params);

		$this->display();
	}

	//新增图层
	public function newlayer()
	{
		$this->display();
	}

	//新增图层 - 保存
	public function newlayersave()
	{
		$title = $this->_getTitle(true);
		$url = $this->_getUrl(true);
		$icon = $this->_getIcon(true);

		$data = array(
			'title'      => $title,
			'url'        => $url,
			'icon'       => $icon,
			'ashow'      => 0,
			'status'     => 1,
			'createtime' => TIMESTAMP,
			'updatetime' => TIMESTAMP
		);
		$result = D('System')->savelayer(null, $data);
		if ($result) {
			$this->ajaxReturn(0, '保存成功！');
		} else {
			$this->ajaxReturn(1, '保存失败！');
		}
	}
}