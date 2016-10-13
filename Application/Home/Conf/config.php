<?php
return array(
	//'配置项'=>'配置值'
    'URL_MODEL' => 1,
    'BAIDU_MAP_KEY' => 'O64nl3yKG6MNbniKhhOmIVNjmNecAxHC',
    'ADDR_REQ_URL' => 'http://api.map.baidu.com/geocoder/v2/',
    'VIDEO_ROOT' => 'http://115.159.66.204/uploads/video/',
    'MAX_TIME_SPAN' => 24*60*60,
    'MAX_SEG_SPAN' => 0.5*60*60,
    'MAX_TIME_STATION_SPAN' => 60*24*60*60,
    'MAX_WATER_LEVEL' => 2,
    'MAX_REPORT_TIME_SPAN' => 6*30*24*60*60,
    'MAX_EVALUATION_TIME_SPAN' => 6*30*24*60*60,
    'CAR_TYPE_HUANWEI' => 101,
    'CAR_TYPE_LAJI' => 102,
    'DEVICE_TYPE' => array(
        array(
            'id' => 101,
            'name' => '环卫车'
        ),
        array(
            'id' => 102,
            'name' => '垃圾车'
        ),
        array(
            'id' => 201,
            'name' => '环卫工人'
        ),
        array(
            'id' => 301,
            'name' => '中转站'
        ),
        array(
            'id' => 401,
            'name' => '垃圾箱'
        )
    ),
);