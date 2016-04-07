/**
 * 统一JS类 包括普通方法、统一处理函数等
 * wangbaoqing@imooly.com
 * 2014-07-19
 */
var CommonClass = function (){

	//统一弹出框方法 改写系统alert
	var windowalert = window.alert;
	window.alert = function ($string){
		windowalert($string);
		// $("#commonalert").show();
	};
	
}

new CommonClass();