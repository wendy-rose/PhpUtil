<?php
/**
 * 测试
 */
//include_once 'RedisCounter.php';
$config = [
    'host' => '127.0.0.1',
    'port' => '6379',
    'index' => 0,
    'auth' => '',
    'timeout' => 1,
    'reserved' => NULL,
    'retry_interval' => 100,
];

//$redisCounter = new RedisCounter($config);
//$key = 'wendy';
//echo $redisCounter->incr($key) . PHP_EOL;

//include_once 'UniqueID.php';
//echo UniqueID::createUniqueID(9996154148454, 3, 'W');

include_once 'RedisLock.php';
$redisLock = new RedisLock($config);
$isLock = $redisLock->lock('wendy', 30);
if ($isLock){
    echo 'get lock success';
}else{
    echo "get lock fail";
}
