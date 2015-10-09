<?php
/**
*memcache缓存类
*/
class Memcached extends Cache
{
	public $config = array();

	public function __construct($config = array())
	{
		if(empty($config)){
			$this->config = Config::find("MEMCACHE_CONG");
		}else{
			$this->config = $config;
		}
		$mem = new Memcache;
		$this->cache = $mem;
		$mem->connect($this->config['host'], $this->config['port']);
	}

	public function set($key, $value, $timeout)
	{
		$this->cache->set($key, $value, 0, $timeout);
	}

	public function get($key)
	{
		return $this->cache->get($key);
	}

	public function del($key)
	{
		$this->cache->delete($key);
	}

	public function clear()
	{
		$this->cache->flush();
	}


}