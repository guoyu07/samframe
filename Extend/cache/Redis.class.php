<?php
class Redis extends Cache
{

	private function getInstance()
	{
		static $redis = null;
		if($redis == null){
			$redis = new Redis();
		}
		$redis->connect('',6379,3);;
	}

	public function set($key, $value, $timeout)
	{
	}

	public function get($key)
	{
		return apc_fetch($key);
	}

	public function del($key)
	{
		return apc_delete($key);
	}

	public function clear()
	{
		return apc_clear_cache();
	}

}
