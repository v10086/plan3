<?php

return [
    'port'=>8080,
    'worker_num'            => 20,         //worker进程数 
    //'max_conn'              => 65535,           //最大允许的连接数， 此参数用来设置Server最大允许维持多少个tcp连接。超过此数量后，新进入的连接将被拒绝。
    'max_request'           => 2000,        //此参数表示worker进程在处理完n次请求后结束运行。manager会重新创建一个worker进程。此选项用来防止worker进程内存溢出。
    'ipc_mode'              => 1,           // 1，默认项，使用Unix Socket作为进程间通信,2，使用系统消息队列作为进程通信方式
//    'task_worker_num'       => 4,    //task_worker进程数 
//    'task_ipc_mode'         => 1,      //1, 使用unix socket通信，2, 使用消息队列通信，3, 使用消息队列通信，并设置为争抢模式
//    'task_max_request'      => 5000,   //设置task进程的最大任务数
    'dispatch_mode'         => 1,      //1平均分配，2按FD取摸固定分配，3抢占式分配，默认为取摸(dispatch=2)
    'daemonize'             => 0,          //守护进程化
    'backlog'               => 2048,            //最多同时有多少个等待accept的连接
    'open_tcp_keepalive'    => 1, //启用tcp keepalive
    'tcp_defer_accept'      => 5,   //当一个TCP连接有数据发送时才触发accept
    'open_tcp_nodelay'      => 1,   //开启后TCP连接发送数据时会无关闭Nagle合并算法，立即发往客户端连接。在某些场景下，如http服务器，可以提升响应速度。 
    'log_file'              => LOGS_PATH .DS. 'swoole.log' //日志文件路径
    //'task_tmpdir'         => APP_PATH . '/data/task',
    //'heartbeat_check_interval' => 5, //每隔多少秒检测一次，单位秒，Swoole会轮询所有TCP连接，将超过心跳时间的连接关闭掉
    //'heartbeat_idle_time' => 5, //TCP连接的最大闲置时间，单位s , 如果某fd最后一次发包距离现在的时间超过heartbeat_idle_time会把这个连接关闭。		
];
