<?php
/**
 * 分页类 分页统一处理模型
 * wangbaoqing@imooly.com
 * 2014-07-16
 */
namespace Think;

class Page
{
    // 总数据行数
    private $_total;

    // 每页开始数据行号
    private $_start;

    // 每页数据行数 默认20
    private $_pagesize = 20;

    // 总页数
    private $_pagecount;

    // 当前页码 默认1
    private $_page = 1;

    // 页码导航栏显示的页码数 奇数 默认11 当前页的左右两边各5
    private $_roolpage = 11;

    // 页面跳转附带参数
    private $_parameter = null;

    // 页码参数
    private $_pagevar = 'page';

    //pagesize下拉列表
    private $_pagesizelist = array(20,40,60,80);

    // 返回数组
    private $_return = null;

    // 初始化分页类
    public function __construct($total=null,$pagesize=null,$parameter=null)
    {
    	//总记录数
    	$this->_total = $total;

    	//每页记录数
    	is_numeric($pagesize)&&$pagesize>0 ? $this->_pagesize = $pagesize : null;

        //分页参数
        is_array($parameter) ? $this->_parameter = $parameter : null;

        //获取页码
        $this->_getPage();

        //获取每页记录数
        $this->_getPagesize();

        //分页预处理 生成start limit等
        $this->_MKPage();
    }

    //获取页码
    private function _getPage()
    {
        $page = $_REQUEST['page'];

        is_numeric($page)&&$page>0 ? $this->_page = $page : null;

        return $this->_page;
    }

    //获取每页记录数
    private function _getPagesize()
    {
        $pagesize = $_REQUEST['pagesize'];

        is_numeric($pagesize)&&$pagesize>0 ? $this->_pagesize = $pagesize : null;

        return $this->_pagesize;
    }

    /**
     * 分页预处理
     * @access private
     * @param void
     * @return void
     */
    private function _MKPage()
    {
        //获取开始记录行号
        $start = ($this->_page-1)*$this->_pagesize;

        //总页数
        $pagecount = ceil($this->_total/$this->_pagesize);
        
        $this->_start = $start;
        $this->_pagecount = $pagecount;
    }

    //参数处理
    private function _MKParameter()
    {
        $parameter = null;
        if (is_array($this->_parameter)) {
            foreach ($this->_parameter as $p=>$v) {
                $parameter = $parameter ? $parameter.'&'.$p.'='.$v : $p.'='.$v;
            }
        }

        return $parameter;
    }

    //获取当前页url
    private function _getRequestURI()
    {
        if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) {
            $url = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
        } else if (isset($_SERVER['argv']) && $_SERVER['argv']) {
            $url = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
        } else if (isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
        } else {
            $url = $_SERVER['PHP_SELF'];
        }
        
        return $url;
    }

    //页面url逻辑处理
    private function _MKURL($url=null)
    {
        if (!$url) return false;

        $parse = parse_url($url);
        $link = '?';

        //定义path和query
        $parse['path'] = isset($parse['path'])&&$parse['path']!="/" ? $parse['path'] : "/";
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$this->_pagevar]);

            $url = $parse['path'].'?'.http_build_query($params);
            $link = '&';
        }

        return $url.$link;
    }

    /**
     * 分页方法 生成分页数组
     * @access private
     * @param void
     * @return array pages=array()
     */
    public function GCPage()
    {
        $page = array();

        //URL、参数整合
        $parameter = $this->_MKParameter();
        $url = $this->_getRequestURI();
        $url = $this->_MKURL($url);
        $url .= $parameter;

        $link = $url.'&'.$this->_pagevar.'=';
        $prev = $this->_page-1;
        $next = $this->_page+1;
        $page = array(
            'total' => $this->_total,
            'page'  => $this->_page,
            'pagecount' => $this->_pagecount,
            'pagesize'  => $this->_pagesize,
            'pagesizelist' => $this->_pagesizelist,
            'link'  => $link,
            'first' => array(
                'page' => 1,
                'link' => $link.'1'
            ),
            'prev' => array(
                'page' => (int)$prev,
                'link' => $link.$prev
            ),
            'current' => array(
                'page' => (int)$this->_page,
                'link' => $link.$this->_page
            ),
            'next' => array(
                'page' => 1,
                'link' => $link.$next
            ),
            'last' => array(
                'page' => (int)$this->_pagecount,
                'link' => $link.$this->_pagecount
            ),
            'rool' => array(),
        );

        //计算roolpage
        $roolpagen = ($this->_roolpage-1)/2;
        //计算pagestart
        $pagestart = $this->_page-$roolpagen;
        $pagestart < 1 ? $pagestart = 1 : null;
        $pagestart > $this->_pagecount-$this->_roolpage+1 ? $pagestart = $this->_pagecount-$this->_roolpage+1 : null;
        //计算pageend
        $pageend = $this->_page+$roolpagen;
        $pageend < $this->_roolpage ? $pageend  = $this->_roolpage : null;
        $pageend > $this->_pagecount ? $pageend = $this->_pagecount : null;

        for ($i=$pagestart; $i<=$pageend; $i++) {
            $page['rool'][] = array(
                'page' => $i,
                'link' => $link.$i
            );
        }

        //返回limit+page
        return array(
            'limit' => array(
                'start' => $this->_start,
                'limit' => $this->_pagesize,
            ),
            'page' => $page
        );
    }
}