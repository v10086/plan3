<?php
namespace controller;
class Base{
    public $httpResponse;
    public $httpRequest;
   
    public function __construct() {

    }
    //魔术方法 有不存在的操作的时候执行
    public function __call($method,$args) {
        throw new \Exception("$method not found",500);
        return;
    }
}

