/**
 * 天地图 JS类
 * wangbaoqing
 * 2016-06-08
 */
var TDTuClass = function (pobj) {
    var map, maptypeVector;

    //纬度
    var point_x = ('x' in pobj) ? pobj.x : 118.882;
    //经度
    var point_y = ('y' in pobj) ? pobj.y : 28.984;
    //默认zoom级别
    var zoom_n = pobj.x&&pobj.y ? 17 : 12;

    //初始化
    var initMap = function () {
        map = new MapALine.Map(document.getElementById("tdtu"));
        maptypeVector = map.addMapType(new MapALine.MapType(MapALine.Config.G_MAP_SERVER, 1));
        map.setMapType(maptypeVector);

        //创建point对象
        var point = new MapALine.Point(point_x, point_y);
        //设置地图中心点
        map.setCenter(point, zoom_n);

        //设置标注点
        var marker = new MapALine.Marker(point);

        var icon = new MapALine.Icon(new MapALine.Size(25, 25), "http://www.zjditu.cn/ResourceCenter/Module/API/demo/maptools/mymark_little.png");
        icon.setAnchor(new MapALine.Point(10, 34));
        marker.setIcon(icon);

        map.addOverlay(marker);
    }

    //标点
    $("#btnAddPoint").on('click', function (){
    	var drawPointTool = new MapALine.DrawPoint();
        map.setCurrentTool(drawPointTool);

        drawPointTool.drawEnd = function (map) {
            drawPointTool.ondrawend();

            map.setCurrentTool(new MapALine.PanTool());
        }
        drawPointTool.ondrawend = function (overlay) {
        	var point_x = drawPointTool._lastClickPoint.x;
        	var point_y = drawPointTool._lastClickPoint.y;

        	if (point_x) $("input[name=point_x]").val(point_x);
        	if (point_y) $("input[name=point_y]").val(point_y);
        }
    });

    //清除所有标点
    $("#btnCleanPoint").on('click', function (){
        map.clearOverlays(MapALine.Circle);
    });

    new initMap();

    return map;
}