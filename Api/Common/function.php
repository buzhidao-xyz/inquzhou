<?php
/**
 * APP模块公共方法
 * wangbaoqing@imoolu.com
 * 2014-7-22
 */

/**
 * MongoDB直接调用方法
 * @param string $collection 集合名称
 * @param string $db 数据库名称
 */

define(EARTH_RADIUS, 6371);


function Mongo($collection=null,$db=null,$dbConfig=null)
{
	$model = new \APP\Model\PublicModel();

	return $model->mongoInit($collection,$db,$dbConfig);
}

/**
 * 获取GET参数
 * @param string 参数名
 */
function mGet($var=null,$urldecode=true)
{
	$paramTypes = array('string','integer','double');
	$value = isset($_GET[$var]) ? $_GET[$var] : '';
    $urldecode ? $value = urldecode($value) : null;
    
    $type = gettype($value);
    if (in_array($type, $paramTypes)) {
        $value = htmlentities(trim($value),ENT_QUOTES,'UTF-8');
    }

	return $value;
}

/**
 * 获取POST参数
 * @param string 参数名
 */
function mPost($var=null,$urldecode=true)
{
	$paramTypes = array('string','integer','double');
	$value = isset($_POST[$var]) ? $_POST[$var] : '';
    $urldecode ? $value = urldecode($value) : null;
    
    $type = gettype($value);
    if (in_array($type, $paramTypes)) {
        $value = htmlentities(trim($value),ENT_QUOTES,'UTF-8');
    }

	return $value;
}

/**
 * 获取REQUEST参数
 * @param string 参数名
 */
function mRequest($var=null,$urldecode=true)
{
    $paramTypes = array('string','integer','double');
    $value = isset($_REQUEST[$var]) ? $_REQUEST[$var] : '';
    $urldecode ? $value = urldecode($value) : null;
    
    $type = gettype($value);
    if (in_array($type, $paramTypes)) {
        $value = htmlentities(trim($value),ENT_QUOTES,'UTF-8');
    }

    return $value;
}

/**
 * ipaddress转longint
 */
function iptolong($ip=null)
{
    if (!preg_match('/^[0-9]{1,3}(\.[0-9]{1,3}){3}$/',$ip)) return 0;

    $ipa = explode('.', $ip);
    $ipint = 0;
    $i = 0;
    foreach ($ipa as $d) {
        if (!is_numeric($d) || $d > 255) return 0;

        $d = $d << (3-$i)*8;
        $ipint += $d;
        $i++;
    }

    return sprintf('%u',$ipint);
}

/**
 * 获取N位随机字符串
 * 字符串取值0-9 a-z之间
 * @param $n 要获取的随机字符串长度(1-100) 默认值6
 */
function randStr($n=6,$type=2)
{
    $return = '';
    $totalStr = array(
        0 => '123456789',
        1 => 'abcdefghijklmnopqrstuvwxyz',
        2 => '123456789abcdefghijklmnopqrstuvwxyz',
    );
    
    $n = preg_match('/^[1-9][0-9]{0,1}$/', $n) ? $n : 0;
    $l = strlen($totalStr[$type])-1;
    for ($i= 0; $i< $n; $i++) {
        $return .= $totalStr[$type]{rand(0,$l)};
    }

    return $return;
}

/**
 * 实例化控制器类
 */
function CR($name,$path='')
{
    return controller($name,$path);
}

/**
 * 格式化时间 将秒数格式化为 天.时.分.秒
 */
function ftime2dayhourminsec($time=null)
{
    if (!is_numeric($time)||$time<=0) return array();

    $minutesec = 60;
    $hoursec = 3600;
    $daysec = 24*$hoursec;
    //天
    $day = floor($time/$daysec);
    //时
    $time -= $day*$daysec;
    $hour = floor($time/$hoursec);
    //分
    $time -= $hour*$hoursec;
    $minute = floor($time/$minutesec);
    //秒
    $second = $time-$minute*$minutesec;

    return array(
        'day' => $day,
        'hour' => $hour,
        'minute' => $minute,
        'second' => $second
    );
}

/**
 * 计算两个经纬度之间的距离
 * @return 公里
 */
 function getdistance($lng1,$lat1,$lng2,$lat2){
    //将角度转为狐度
    $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
    $radLat2=deg2rad($lat2);
    $radLng1=deg2rad($lng1);
    $radLng2=deg2rad($lng2);
    $a=$radLat1-$radLat2;
    $b=$radLng1-$radLng2;
    $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
    $return = round($s/1000);
    return $return;
}


function returnSquarePoint($lng, $lat,$distance = 10){
    $dlng =  2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));

    $dlng = rad2deg($dlng);

    $dlat = $distance/EARTH_RADIUS;

    $dlat = rad2deg($dlat);

    return array(

        'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),

        'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),

        'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),

        'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)

    );
}

/**
 * 处理图片链接
 * @param string $imageurl 图片路径
 */
function ImageURL($imageurl=null)
{
    if (!$imageurl) return "";

    return mb_convert_encoding(C('HOST.HTTP_HOST').$imageurl, 'UTF-8');
}

//计算大小
function format_bytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}