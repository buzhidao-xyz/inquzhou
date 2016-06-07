<?php
/**
 * 收藏管理 业务
 * wbq@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Controller;

use Org\Util\Filter;

class FavController extends CommonController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index(){}

	//收藏点
	public function favplace()
	{
        $keywords = mRequest('keywords');
        $this->assign('keywords', $keywords);

        list($start, $length) = $this->_mkPage();
        $data = D('Fav')->getFavplace(null, $keywords, $start, $length);
        $total = $data['total'];
        $datalist = $data['data'];

        $this->assign('total', $total);
        $this->assign('datalist', $datalist);

        $params = array(
            'keywords' => $keywords,
        );
        $this->assign('param', $params);
        //解析分页数据
        $this->_mkPagination($total, $params);

		$this->display();
	}

	//收藏路线
	public function favline()
	{
        $keywords = mRequest('keywords');
        $this->assign('keywords', $keywords);

        list($start, $length) = $this->_mkPage();
        $data = D('Fav')->getFavline(null, $keywords, $start, $length);
        $total = $data['total'];
        $datalist = $data['data'];

        $this->assign('total', $total);
        $this->assign('datalist', $datalist);

        $params = array(
            'keywords' => $keywords,
        );
        $this->assign('param', $params);
        //解析分页数据
        $this->_mkPagination($total, $params);

		$this->display();
	}
}