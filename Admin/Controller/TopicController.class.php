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
}