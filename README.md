📃 开源协议 Apache License Version 2.0 see http://www.apache.org/licenses/LICENSE-2.0.html
v10086原创
# 简介

基于Swoole的高性能应用型框架

简约可靠的架构  可用来开发高性能api

ab 测试 qps达到了3万左右

版本说明
--------------------------------------------------------------------------

Swoole4.3+版本
PHP7.0+版本
任意Yaf Stable版本(新版本无需安装Yaf)


INI配置
--------------------------------------------------------------------------
[swoole]
extension=swoole.so

示例
--------------------------------------------------------------------------


```php

<?php
namespace controller;
class Index extends \controller\Base {
    
    public function index(){
        
        $this->httpRequest->get;//获取get数据
        
        $this->httpRequest->post;//获取get数据
        
        $this->httpRequest->getData();//获取rawContent数据
        
        return  ['code'=>200,'msg'=>date("Y-m-d H:i:sa")];
    }
    
    //测试异常抛出
    public function test(){
        throw new \Exception("test not found",500);
    }
    
    //协程操作数据库
    public function coroutineMySQL(){
        
        //启用协程化
       \Swoole\Runtime::enableCoroutine();
       
       //协程化后两个sleep(5)并发执行,仅用时5秒多
       $chan = new \Swoole\Coroutine\Channel(2);
       
//       //sql执行示例
//       $sql='select * from user where id=? limit 1';
//       $params[]=1;
//       $res = dbexec($sql,$params);

        go(function () use ($chan){
            $db = dbnew(config('database.default'));
            $sql='select sleep(5)';
            $sth = @$db->prepare($sql);
            $sth->execute();
            $chan->push(['rest'=>$sth->fetch(),'chan'=>1]);
            $sth=null;
            $db=null;
        });
        go(function () use ($chan){
            $sql='select sleep(5)';
            $resp=dbexec($sql)[0];
            $chan->push(['rest'=> $resp,'chan'=>2]);
        
        });
        $data[]=$chan->pop();
        $data[]=$chan->pop();
        return $data;
        
    }
    
}



```

API 请求
------------------------------------------------------------


![1](https://github.com/v10086/easyswoole/blob/master/test/20180701214348.png) 



性能测试
------------------------------------------------------------

;启动 swoole http server

cd ./server/

php HttpServer.php 

ab -c 200  -n 1000000 127.0.0.1:8080/


测压结果
------------------------------------------------------------
![test](https://github.com/v10086/easyswoole/blob/master/test/abtest.png) 






