<?php
defined('APP_PATH') or exit();
defined('LIB_PATH') or exit();

class Controller
{
    private $template   = null;

	public function initTemplate($engine,$tpl_root)
	{/*{{{*/
		$this->template = Object::find('Template');
		$this->template->init($engine,$tpl_root);
	}/*}}}*/

	public function display($tpl=null)
	{/*{{{*/
		$this->template->display($tpl);
	}/*}}}*/

	public function fetch($tpl = null)
	{/*{{{*/
		return $this->template->fetch($tpl);
	}/*}}}*/

	public function getTplDir()
	{/*{{{*/
		if($this->template != null){
			return $this->template->getTplDir();
		}else{
			return null;
		}
	}/*}}}*/

	public function getTplRoot()
	{/*{{{*/
		if($this->template != null){
			return $this->template->getTplRoot();
		}else{
			return null;
		}
	}/*}}}*/

	public function assign($name,$value)
	{/*{{{*/
		$this->template->assign($name,$value);
	}/*}}}*/

	public function get($key,$default = '')
	{/*{{{*/
		if(!isset($_GET[$key])){
			if($default == '') return ;
			return $default;
		}
		return htmlspecialchars(addslashes($_GET[$key]));
	}/*}}}*/

	public function post($key,$default = '')
	{/*{{{*/
		if(!isset($_POST[$key])){
			if($default == '') return ;
			return $default;
		}
		if(is_array($_POST[$key])){
			foreach($_POST[$key] as $k=>&$v){
				$v = htmlspecialchars(addslashes($v));
			}
			return $_POST[$key];
		}else{
			return htmlspecialchars(addslashes($_POST[$key]));
		}
	}/*}}}*/

	public function renderJson($res)
	{/*{{{*/
		echo json_encode($res);
	}/*}}}*/

	public function getController()
	{/*{{{*/
		$web = Object::find("Web");
		if(empty($web->controller)){
			$web->controller = get_class($this);
		}
		return $web->controller;
	}/*}}}*/

	public function getAction()
	{/*{{{*/
		$web = Object::find("Web");
		return $web->action;
	}/*}}}*/

	public function getMethod()
	{/*{{{*/
		$controller = strtolower(str_replace('Controller','',$this->getController()));
		$action = strtolower(str_replace('Action','',$this->getAction()));
		return $controller . '.' . $action;
	}/*}}}*/

	public function dispatch($action)
	{/*{{{*/
		$this->$action();
	}/*}}}*/

}

