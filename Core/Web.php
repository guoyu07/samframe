<?php

class Web
{

	public  $controller          = null;
	public  $action              = null;
	public  $view_path           = null;
	public  $view_name           = null;
	private $_default_controller = 'Index';
	private $_controller_suffix  = 'Controller';
	private $_dafault_action     = 'index';
	private $_action_suffix      = 'Action';
	private $_separator          = '.';
	private $_prefix_method      = '_befor';
	private $_suffix_method      = '_after';

	public function parseRoute()
	{/*{{{*/
		$route_arr = explode($this->_separator,$_GET['method']);

		$controller = isset($route_arr[0]) ? ucfirst($route_arr[0]) : $this->_default_controller;
		$this->view_path = $controller;
		$controller .= $this->_controller_suffix;
		$this->controller = $controller;

		$action = isset($route_arr[1]) ? strtolower($route_arr[1]) : $this->_default_action;
		$this->view_name = $action;
		$action .= $this->_action_suffix;
		$this->action = $action;
		unset($_GET['method']);
	}/*}}}*/

	public function run()
	{/*{{{*/
		if(class_exists($this->controller)){
			$instance = Object::find($this->controller);
		}else{
			throw new QException(100);
		}

		if(method_exists($instance,$this->action)){
			$action = $this->action;
		}else{
			throw new QException(101);
		}

		if(method_exists($instance,$this->_prefix_method)){
			$instance->dispatch($this->_prefix_method);
		}
		$instance->dispatch($action);
		if(method_exists($instance,$this->_suffix_method)){
			$instance->dispatch($this->_suffix_method);
		}
	}/*}}}*/

}
