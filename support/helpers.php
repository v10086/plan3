<?php

    //string $source 必需。规定要复制的文件
    //string $destination  写入目标
    //string $name 文件名
    function fileSave($source,$destination,$name){
        // 自动创建日志目录
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        return copy($source,$destination.DS.$name);
    }

    //读取配置文件
    function config($params){
        $config = explode('.', $params);
        if(!isset($GLOBALS['cache_configs']) || !isset($GLOBALS['cache_configs'][$config[0]])){
            $GLOBALS['cache_configs'][$config[0]]=require (CONF_PATH. DS .$config[0] .'.php');
        }
        $value =$GLOBALS['cache_configs'][$config[0]];
        for($i=1;$i<sizeof($config);$i++){
            if(!isset($value[$config[$i]])){
                return FALSE;
            }
            $value=$value[$config[$i]];
        }
        return $value;

    }
    
    //以默认配置创建新的数据库C端实例 并执行sql
    function dbexec($sql,$params=[],$db=null){
        if($db==null){
            $db = dbnew(config('database.default'));
        }
        $sth = @$db->prepare($sql);
        $sth->execute($params);
        if(strtoupper(substr(trim($sql),0,6))=='SELECT'){
            $resp=$sth->fetchAll();
        }else{
            $resp=$sth->rowCount();
        }
        //资源释放
        $sth=null;
        $db=null;
        return $resp; 
    }
    
    //创建数据库实例
    function dbnew($config){
        $db =  new \PDO($config['dsn'], $config['user'], $config['password']);
        $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES , FALSE);//数据库使用真正的预编译  
        return $db;
    }
    
