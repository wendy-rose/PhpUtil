<?php

/**
 * 并发请求限制，加锁
 */
class RedisLock
{

    private $config = [];

    private $redis = null;

    public function __construct($config)
    {
        $this->config = $config;
        $this->redis = $this->connect();
    }

    public function lock($key, $expire)
    {
        $is_lock = $this->redis->setnx($key, time() + $expire);
        if (!$is_lock){
            $lock_time = $this->get($key);
            if (time() > $lock_time){
                $this->delete($key);
                $is_lock = $this->redis->setnx($key, time() + $expire);
            }
        }
        return $is_lock ? true : false;
    }


    public function get($key)
    {
        return intval($this->redis->get($key));
    }

    public function delete($key)
    {
        $this->redis->delete($key);
    }

    private function connect()
    {
        try{
            $redis = new Redis();
            $redis->connect($this->config['host'], $this->config['port'], $this->config['timeout'], $this->config['reserved'], $this->config['retry_interval']);
            //是否密码验证
            if (!empty($this->config['auth'])){
                $redis->auth($this->config['auth']);
            }
            //选择哪个数据库，默认是0
            if (!empty($this->config['index'])){
                $redis->select($this->config['index']);
            }
        }catch (RedisException $exception){
            throw new Exception($exception->getMessage());
        }
        return $redis;
    }
}