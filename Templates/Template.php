<?php
class Template
{
	private $tpl_engine = null;
	private $tpl_root   = null;
	private $tpl_suffix;
	private $tpl_dir = null;

	public function init($engine = 'php',$tpl_root = '')
	{/*{{{*/
		if($tpl_root == ''){
			$this->tpl_root = APP_PATH.'/views/';
		}else{
			$this->tpl_root = APP_PATH . '/' . $tpl_root;
		}

		switch($engine){
			case 'smarty' :$this->initSmarty();break;
			case 'php' :$this->initPhpEngine();break;
		}
		//设置公共变量
		$this->assign('APP',APP_PATH);
		$this->assign('VIEW',$this->tpl_root);
	}/*}}}*/

	private function initSmarty()
	{/*{{{*/
		$smarty_conf = Config::find("smarty");
		if(!$smarty_conf['using']) return;

		include LIB_PATH.'/Templates/drivers/smarty/Smarty.class.php';
		$this->tpl_engine = new Smarty();
		$this->tpl_engine->compile_dir     = ROOT_PATH."/cache/templates_c/";
		$this->tpl_engine->config_dir      = ROOT_PATH."/cache/configs/";
		$this->tpl_engine->cache_dir       = ROOT_PATH."/cache/cache/";
		$this->tpl_engine->template_dir    = $this->tpl_root;
		$this->tpl_engine->left_delimiter  = $smarty_conf['left'];
		$this->tpl_engine->right_delimiter = $smarty_conf['right'];
		$this->tpl_engine->force_compile   = false;
		$this->tpl_engine->debugging       = $smarty_conf['debug'];
		$this->tpl_suffix = $smarty_conf['tpl_suffix'];
	}/*}}}*/

	private function initPhpEngine()
	{/*{{{*/
		include LIB_PATH.'/Templates/drivers/phptemplate/tpl.class.php';
		$this->tpl_engine = new PhpTpl();
		$this->tpl_suffix = 'tpl.php';
	}/*}}}*/

	public function assign($var,$value)
	{/*{{{*/
		if(empty($var)){
			throw new QException('模板变量名称不得为空！Usage:$this->assign($var,$value)');
		}
		$this->tpl_engine->assign($var,$value);
	}/*}}}*/

	public function display($tpl_path)
	{/*{{{*/
		$tpl_path = $this->getDefaultTpl($tpl_path);
		$this->assign("TPL",$tpl_path);
		if(!file_exists($tpl_path)){
			throw new QException('模板文件:' . $tpl_path . '不存在！');
		}
		$this->tpl_engine->display($tpl_path);
	}/*}}}*/

	public function fetch($tpl_path)
	{/*{{{*/
		if(empty($tpl_path)){
			$tpl_path = $this->getDefaultTpl();
		}
		if(!file_exists($tpl_path)){
			throw new QException('模板文件:' . $tpl_path . '不存在！');
		}
		return $this->tpl_engine->fetch($tpl_path);
	}/*}}}*/

	public function getTplDir()
	{/*{{{*/
		return $this->tpl_dir;
	}/*}}}*/

	public function getTplRoot()
	{/*{{{*/
		return $this->tpl_root;
	}/*}}}*/

	private function getDefaultTpl($tpl_path = '')
	{/*{{{*/
		if($tpl_path == ''){
			$web = Object::find("Web");
			$view_path = $web->view_path;
			$view_name = $web->view_name;
			$this->tpl_dir = $view_path;

			$tpl_path = $view_path . '/' . $view_name . '.' . $this->tpl_suffix;
		}else{
			$path = explode('/',$tpl_path);
			$this->tpl_dir = $path[0];
		}
		return str_replace("\\","/",$this->tpl_root . $tpl_path);
	}/*}}}*/

}
