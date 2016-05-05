<?php
/**
 * 收藏 业务逻辑
 * wbq@xlh-tech.com
 * 2016-04-12
 */
namespace Api\Controller;

use Org\Util\Filter;

class FavController extends CommonController
{
	public function __construct()
	{
		parent::__construct();
	}

    //获取名称
    private function _getTitle($ck=false)
    {
        $title = mRequest('title');

        $ck&&!$title ? $this->apiReturn(1, '未知名称！') : null;

        return $title;
    }

    //获取地址
    private function _getAddress($ck=false)
    {
        $address = mRequest('address');

        $ck&&!$address ? $this->apiReturn(1, '未知地址！') : null;

        return $address;
    }

    //获取sour
    private function _getSour()
    {
        $sour = mRequest('sour');

        $ck&&!$sour ? $this->apiReturn(1, '未知起点！') : null;

        return $sour;
    }

    //获取sourlat
    private function _getSourlat()
    {
        $sourlat = mRequest('sourlat');

        $ck&&!$sourlat ? $this->apiReturn(1, '未知起点纬度！') : null;

        return (double)$sourlat;
    }

    //获取sourlng
    private function _getSourlng()
    {
        $sourlng = mRequest('sourlng');

        $ck&&!$sourlng ? $this->apiReturn(1, '未知起点经度！') : null;

        return (double)$sourlng;
    }

    //获取dest
    private function _getDest()
    {
        $dest = mRequest('dest');

        $ck&&!$dest ? $this->apiReturn(1, '未知终点！') : null;

        return $dest;
    }

    //获取destlat
    private function _getDestlat()
    {
        $destlat = mRequest('destlat');

        $ck&&!$destlat ? $this->apiReturn(1, '未知终点纬度！') : null;

        return (double)$destlat;
    }

    //获取destlng
    private function _getDestlng()
    {
        $destlng = mRequest('destlng');

        $ck&&!$destlng ? $this->apiReturn(1, '未知终点经度！') : null;

        return (double)$destlng;
    }

	public function index(){}

    //收藏地点
    public function newfavplace()
    {
        $title = $this->_getTitle(true);
        $address = $this->_getAddress(true);
        $lat = $this->_getLat(true);
        $lng = $this->_getLng(true);

        $userid = $this->userinfo['userid'];

        $data = array(
            'userid'  => $userid,
            'title'   => $title,
            'address' => $address,
            'lat'     => $lat,
            'lng'     => $lng,
            'favtime' => TIMESTAMP,
        );
        $result = D('Fav')->savefavplace($data);

        if ($result) {
            $msg = '收藏地点成功！';
            $result = array(
                'result' => 1,
            );
        } else {
            $msg = '收藏地点失败！';
            $result = array(
                'result' => 0,
            );
        }
        $this->apiReturn(0, $msg, $result);
    }

    //收藏路线
    public function newfavline()
    {
        $sour = $this->_getSour(true);
        $sourlat = $this->_getSourlat(true);
        $sourlng = $this->_getSourlng(true);
        $dest = $this->_getDest(true);
        $destlat = $this->_getDestlat(true);
        $destlng = $this->_getDestlng(true);

        $userid = $this->userinfo['userid'];

        $data = array(
            'userid'  => $userid,
            'sour'    => $sour,
            'sourlat' => $sourlat,
            'sourlng' => $sourlng,
            'dest'    => $dest,
            'destlat' => $destlat,
            'destlng' => $destlng,
            'favtime' => TIMESTAMP,
        );
        $result = D('Fav')->savefavline($data);

        if ($result) {
            $msg = '收藏路线成功！';
            $result = array(
                'result' => 1,
            );
        } else {
            $msg = '收藏路线失败！';
            $result = array(
                'result' => 0,
            );
        }
        $this->apiReturn(0, $msg, $result);
    }

    //我的收藏-地点
    public function favplace()
    {
        $userid = $this->userinfo['userid'];

        list($start, $length) = $this->mkPage();
        $result = D('Fav')->getFavplace(null, $userid, $start, $length);
        
        $data = array();
        foreach ($result['data'] as $d) {
            $data[] = array(
                'placeid' => (int)$d['placeid'],
                'title'   => $d['title'],
                'address' => $d['address'],
                'lat'     => $d['lat'],
                'lng'     => $d['lng'],
                'favtime' => date('Y-m-d H:i:s', $d['favtime']),
            );
        }

        $this->apiReturn(0,'',array(
            'total' => (int)$result['total'],
            'data' => $data
        ));
    }

    //我的收藏-路线
    public function favline()
    {
        $userid = $this->userinfo['userid'];

        list($start, $length) = $this->mkPage();
        $result = D('Fav')->getFavline(null, $userid, $start, $length);
        
        $data = array();
        foreach ($result['data'] as $d) {
            $data[] = array(
                'lineid'  => (int)$d['lineid'],
                'sour'    => $d['sour'],
                'sourlat' => $d['sourlat'],
                'sourlng' => $d['sourlng'],
                'dest'    => $d['dest'],
                'destlat' => $d['destlat'],
                'destlng' => $d['destlng'],
                'favtime' => date('Y-m-d H:i:s', $d['favtime']),
            );
        }

        $this->apiReturn(0,'',array(
            'total' => (int)$result['total'],
            'data' => $data
        ));
    }

    //删除收藏信息
    public function delfav()
    {
        $userid = $this->userinfo['userid'];

        $favid = mRequest('favid');
        if (!$favid) $this->apiReturn(1, '未知收藏信息！');

        $favtype = mRequest('favtype');
        $table = null;
        $favidfield = null;
        switch ($favtype) {
            case 'place':
                $table = 'favplace';
                $favidfield = 'placeid';
            break;
            case 'line':
                $table = 'favline';
                $favidfield = 'lineid';
            break;
            default:
            break;
        }
        if (!$table) $this->apiReturn(1, '未知收藏类型！');

        $result = M($table)->where(array($favidfield=>$favid, 'userid'=>$userid))->save(array('isdelete'=>1));
        if ($result) {
            $this->apiReturn(0, '删除成功！', array(
                'result' => 1
            ));
        } else {
            $this->apiReturn(0, '删除失败！', array(
                'result' => 0
            ));
        }
    }
}