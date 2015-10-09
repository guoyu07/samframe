<?php

class PhpTpl
{
	private $vars = array();

	public function assign($var,$value)
	{/*{{{*/
		$this->vars[$var] = $value;
	}/*}}}*/

	public function display($tpl)
	{/*{{{*/
		$content = $this->fetch($tpl);
		echo $content;
	}/*}}}*/

	public function fetch($tpl)
	{/*{{{*/
		ob_start();
		extract($this->vars);
		include $tpl;
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}/*}}}*/

}
