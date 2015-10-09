<?php

class IndexController extends Controller
{
	public function demoAction()
	{
		$web_name = Config::find('web_name');
		$hello = "Hello World!";

		$this->assign("hello",$hello);
		$this->assign("name",$web_name);
		$this->display();
	}
}