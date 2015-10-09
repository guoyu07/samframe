<?php
/**
*cache抽象类
*@extended by memcahe/apc/xcache
*/
abstract class Cache
{
	public $cache = '';

	abstract function set($key, $value, $timeout);
	abstract function get($key);
	abstract function del($key);
	abstract function clear();
}