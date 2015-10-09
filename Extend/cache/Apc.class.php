<?php

class Apc extends Cache
{

	public function set($key, $value, $timeout)
	{
		return apc_store($name, $value, $timeout);
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