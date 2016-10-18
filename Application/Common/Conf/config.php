<?php
return array(
	//'配置项'=>'配置值'
    'LOAD_EXT_CONFIG'   => 'db',
    'URL_MODEL' => 3,
    'LOG_RECORD' => true, // 开启日志记录
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
    'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式

    /* 自动运行配置 */
    'CRON_CONFIG_ON' => true, // 是否开启自动运行
    'CRON_CONFIG' => array(
        '测试定时任务' => array('Home/Index/crons', '10', ''), //路径(格式同R)、间隔秒（0为一直运行）、指定一个开始时间
    ),
);