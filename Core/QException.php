<?php

class QException extends Exception
{
	//1controller相关，2模板相关
	private $error_msg;

	public $error_code;

	public function __construct($error = '')
	{
		$this->error_msg = $error;
	}

	public function getError()
	{
		return $this->error_msg;
	}
}
