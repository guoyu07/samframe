<?php

class Object
{
    private $_objs = array();

    static public function getInstance()
    {/*{{{*/
        static $container = null;
        if(is_null($container))
        {
            $container = new Object();
        }
        return $container;
    }/*}}}*/
    
    static public function find($name)
    {/*{{{*/
        $container = self::getInstance();        
        return $container->get($name);
    } /*}}}*/

    private function get($name)
    {/*{{{*/
        if(!isset($this->_objs[$name]))
        {
            $this->set($name);
        }
        return $this->_objs[$name];
    }/*}}}*/

    private function set($name)
    {/*{{{*/
		$c = new ReflectionClass($name);
        $this->_objs[$name] = $c->newInstance();
    }/*}}}*/

	static public function destroy($name)
	{
		$container = self::getInstance();
		unset($container->_objs[$name]);
	}

	static function viewAllObjs()
	{
		return self::getInstance()->_objs;
	}


}

?>
