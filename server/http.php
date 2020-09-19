<?php
define('DS', '/');

define("ROOT_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向index.php的上一级 */

define('APP_NAME',      'app');

define("APP_PATH",ROOT_PATH . DS . APP_NAME );

define("LOGS_PATH",ROOT_PATH . DS . 'log' );

define("SUPPORT_PATH",ROOT_PATH . DS . 'support');

define("CONF_PATH",ROOT_PATH . DS . 'config' );

define("VENDOR_PATH",ROOT_PATH . DS . 'vendor' );

require  SUPPORT_PATH. DS ."helpers.php";;
//require VENDOR_PATH . "/autoload.php";


//自动加载类库
spl_autoload_register(function ($class) {
    $path = APP_PATH;
    foreach (explode('\\', $class) as $key => $value) {
        $path = $path. DS . $value;
    }
    include $path.'.php';
});

$swoole_config =  config('swoole');
$http = new Swoole\Http\Server("0.0.0.0", $swoole_config['port']);
$http->set($swoole_config);

$http->on('WorkerStart' ,  function ($server, $worker_id) {

});
$http->on('request', function ($request, $response) {
    try{
         $api = $request->server['request_uri'];
         $routeConfig= config('route');
         if(!isset($routeConfig[$api])){
             $response->status(404); 
             $response->end();
             return;
         }
         $routeConfig[$api] =str_replace("/","\\", $routeConfig[$api] );
         list($class, $func) =explode('@', $routeConfig[$api]);
         $object = new $class;

         $object->httpRequest = $request;
         $object->httpResponse = $response;

         $result = $object->$func();
         if($result!==NULL){
             if(is_array($result) || is_object($result)){
                 $response->header('Content-Type','text/json; charset=UTF-8');
                 $result = json_encode($result,JSON_UNESCAPED_UNICODE);
             }elseif(is_string($result)){
                 $response->header('Content-Type','text/html; charset=UTF-8');
             }
             $response->status(200); 
             $response->end($result);
         }


    } catch (\Exception $ex) {
        $response->status(500); 
        $response->end($ex->getMessage());
        return;

    }

});

$http->start();

