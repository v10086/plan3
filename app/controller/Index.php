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

