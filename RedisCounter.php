<?php

class RedisCounter
{

    private $config = [];

    private $redis = null;

    public function __construct($config)
    {
        $this->config = $config;
        $this->redis = $this->connect();
    }

    public function incr($key, $incr=1)
    {
        return intval($this->redis->incrBy($key, $incr));
    }

    public function expire($key, $time)
    {
       return  $this->redis->expire($key, $time);
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