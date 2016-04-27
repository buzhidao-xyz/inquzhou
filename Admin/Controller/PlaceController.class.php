<?php
/**
 * 地点管理 业务
 * wbq@xlh-tech.com
 * 2016-04-12
 */
namespace Admin\Controller;

use Org\Util\Filter;

class PlaceController extends CommonController
{
	//纠错类型
	private $_pmtypelist = array(
		1 => array('id'=>1, 'title'=>'位置错误'),
		2 => array('id'=>2, 'title'=>'名称错误'),
		9 => array('id'=>9, 'title'=>'其他错误'),
	);

	public function __construct()
	{
		parent::__construct();

		$this->assign('pmtypelist', $this->_pmtypelist);
	}

	public function index(){}

	//标注地点
	public function markplace()
	{
        $keywords = mRequest('keywords');
        $this->assign('keywords', $keywords);

        list($start, $length) = $this->_mkPage();
        $data = D('Place')->getMarkplace(null, $keywords, $start, $length);
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

	//新增地点
	public function ptplace()
	{
		$keywords = mRequest('keywords');
        $this->assign('keywords', $keywords);

        list($start, $length) = $this->_mkPage();
        $data = D('Place')->getPtplace(null, $keywords, $start, $length);
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

	//处理新增地点
	public function ptplacedo()
	{
		$ptplaceid = mRequest('ptplaceid');
		if (!$ptplaceid) $this->ajaxReturn(1, '未知地点！');

		$result = M('ptplace')->where(array('ptplaceid'=>$ptplaceid))->save(array('status'=>1));
		if ($result) {
			$this->ajaxReturn(0, '处理成功！');
		} else {
			$this->ajaxReturn(1, '处理失败！');
		}
	}

	//纠错地点
	public function pmplace()
	{
		$keywords = mRequest('keywords');
        $this->assign('keywords', $keywords);

        list($start, $length) = $this->_mkPage();
        $data = D('Place')->getPmplace(null, $keywords, $start, $length);
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

	//处理纠错地点
	public function pmplacedo()
	{
		$pmplaceid = mRequest('pmplaceid');
		if (!$pmplaceid) $this->ajaxReturn(1, '未知地点！');

		$result = M('pmplace')->where(array('pmplaceid'=>$pmplaceid))->save(array('status'=>1));
		if ($result) {
			$this->ajaxReturn(0, '处理成功！');
		} else {
			$this->ajaxReturn(1, '处理失败！');
		}
	}

	//导出
	public function export()
	{
		require(VENDOR_PATH.'PHPExcel-1.8.1/PHPExcel.php');

		// 创建一个处理对象实例
		$objPHPExcel = new \PHPExcel();
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		//设置当前的sheet索引，用于后续的内容操作。
		$objPHPExcel->setActiveSheetIndex(0);       
		$objActSheet = $objPHPExcel->getActiveSheet();

		$action = mRequest('action');
		if ($action == "markplace") {
			$title = "in衢州_标注地点";
			
			$keywords = mRequest('keywords');
			$this->assign('keywords', $keywords);

			$data = D('Place')->getMarkplace(null, $keywords, 0, 10000, 'asc');
	    	$datalist = $data["data"];

			//设置当前活动sheet的名称       
			$objActSheet->setTitle($title);

			//设置宽度，这个值和EXCEL里的不同，不知道是什么单位，略小于EXCEL中的宽度
			$objActSheet->getColumnDimension('A')->setWidth(30);
			$objActSheet->getColumnDimension('B')->setWidth(50);
			$objActSheet->getColumnDimension('C')->setWidth(70);
			$objActSheet->getColumnDimension('D')->setWidth(20);
			$objActSheet->getColumnDimension('E')->setWidth(20);
			$objActSheet->getColumnDimension('F')->setWidth(10);
			$objActSheet->getColumnDimension('G')->setWidth(25);
			//设置单元格的值
			// $objActSheet->setCellValue('A1', '总标题显示');
			//合并单元格
			// $objActSheet->mergeCells('A1:D1');

			//设置表格标题栏内容
			$objActSheet->setCellValue('A1', '名称');
			$objActSheet->setCellValue('B1', '地址');
			$objActSheet->setCellValue('C1', '描述说明');
			$objActSheet->setCellValue('D1', '经度');
			$objActSheet->setCellValue('E1', '纬度');
			$objActSheet->setCellValue('F1', '标注人');
			$objActSheet->setCellValue('G1', '标注时间');
			
			//遍历数据
			$n = 2;
			foreach ($datalist as $v) {
				$objActSheet->setCellValue('A'.$n, $v["title"]);
				$objActSheet->setCellValue('B'.$n, $v['address']);
				$objActSheet->setCellValue('C'.$n, $v["desc"]);
				$objActSheet->setCellValue('D'.$n, $v["lng"]);
				$objActSheet->setCellValue('E'.$n, $v["lat"]);
				$objActSheet->setCellValue('F'.$n, $v["username"]);
				$objActSheet->setCellValue('G'.$n, date('Y-m-d H:i:s', $v["marktime"]));

				$n++;
			}
		}
		
		if ($action == "ptplace") {
			$title = "in衢州_新增地点";
			
			$keywords = mRequest('keywords');
			$this->assign('keywords', $keywords);

			$data = D('Place')->getPtplace(null, $keywords, 0, 10000, 'asc');
	    	$datalist = $data["data"];

			//设置当前活动sheet的名称       
			$objActSheet->setTitle($title);

			//设置宽度，这个值和EXCEL里的不同，不知道是什么单位，略小于EXCEL中的宽度
			$objActSheet->getColumnDimension('A')->setWidth(30);
			$objActSheet->getColumnDimension('B')->setWidth(50);
			$objActSheet->getColumnDimension('C')->setWidth(70);
			$objActSheet->getColumnDimension('D')->setWidth(20);
			$objActSheet->getColumnDimension('E')->setWidth(20);
			$objActSheet->getColumnDimension('F')->setWidth(10);
			$objActSheet->getColumnDimension('G')->setWidth(15);
			$objActSheet->getColumnDimension('H')->setWidth(25);
			//设置单元格的值
			// $objActSheet->setCellValue('A1', '总标题显示');
			//合并单元格
			// $objActSheet->mergeCells('A1:D1');

			//设置表格标题栏内容
			$objActSheet->setCellValue('A1', '名称');
			$objActSheet->setCellValue('B1', '地址');
			$objActSheet->setCellValue('C1', '描述说明');
			$objActSheet->setCellValue('D1', '经度');
			$objActSheet->setCellValue('E1', '纬度');
			$objActSheet->setCellValue('F1', '提交人');
			$objActSheet->setCellValue('G1', '状态');
			$objActSheet->setCellValue('H1', '新增时间');
			
			//遍历数据
			$n = 2;
			foreach ($datalist as $v) {
				$objActSheet->setCellValue('A'.$n, $v["title"]);
				$objActSheet->setCellValue('B'.$n, $v['address']);
				$objActSheet->setCellValue('C'.$n, $v["desc"]);
				$objActSheet->setCellValue('D'.$n, $v["lng"]);
				$objActSheet->setCellValue('E'.$n, $v["lat"]);
				$objActSheet->setCellValue('F'.$n, $v["username"]);
				$objActSheet->setCellValue('G'.$n, $v["status"]?'已处理':'未处理');
				$objActSheet->setCellValue('H'.$n, date('Y-m-d H:i:s', $v["pttime"]));

				$n++;
			}
		}

		if ($action == "pmplace") {
			$title = "in衢州_纠错地点";
			
			$keywords = mRequest('keywords');
			$this->assign('keywords', $keywords);

			$data = D('Place')->getPmplace(null, $keywords, 0, 10000, 'asc');
	    	$datalist = $data["data"];

			//设置当前活动sheet的名称       
			$objActSheet->setTitle($title);

			//设置宽度，这个值和EXCEL里的不同，不知道是什么单位，略小于EXCEL中的宽度
			$objActSheet->getColumnDimension('A')->setWidth(15);
			$objActSheet->getColumnDimension('B')->setWidth(50);
			$objActSheet->getColumnDimension('C')->setWidth(70);
			$objActSheet->getColumnDimension('D')->setWidth(20);
			$objActSheet->getColumnDimension('E')->setWidth(20);
			$objActSheet->getColumnDimension('F')->setWidth(10);
			$objActSheet->getColumnDimension('G')->setWidth(15);
			$objActSheet->getColumnDimension('H')->setWidth(25);
			//设置单元格的值
			// $objActSheet->setCellValue('A1', '总标题显示');
			//合并单元格
			// $objActSheet->mergeCells('A1:D1');

			//设置表格标题栏内容
			$objActSheet->setCellValue('A1', '类型');
			$objActSheet->setCellValue('B1', '地址');
			$objActSheet->setCellValue('C1', '描述说明');
			$objActSheet->setCellValue('D1', '经度');
			$objActSheet->setCellValue('E1', '纬度');
			$objActSheet->setCellValue('F1', '提交人');
			$objActSheet->setCellValue('G1', '状态');
			$objActSheet->setCellValue('H1', '提交时间');
			
			//遍历数据
			$n = 2;
			foreach ($datalist as $v) {
				$objActSheet->setCellValue('A'.$n, $this->_pmtypelist[$v['pmtype']]['title']);
				$objActSheet->setCellValue('B'.$n, $v['address']);
				$objActSheet->setCellValue('C'.$n, $v["desc"]);
				$objActSheet->setCellValue('D'.$n, $v["lng"]);
				$objActSheet->setCellValue('E'.$n, $v["lat"]);
				$objActSheet->setCellValue('F'.$n, $v["username"]);
				$objActSheet->setCellValue('G'.$n, $v["status"]?'已处理':'未处理');
				$objActSheet->setCellValue('H'.$n, date('Y-m-d H:i:s', $v["pmtime"]));

				$n++;
			}
		}

		//输出内容
		$outputFileName = $title.'_'.date('Ymd_His', TIMESTAMP).".xlsx";
		//到文件
		// $objWriter->save($outputFileName);

		//到浏览器
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="'.iconv('UTF-8', 'GB2312', $outputFileName).'"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save("php://output");
		exit;
	}
}