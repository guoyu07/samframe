<?php
defined('ROOT_PATH') or exit();

class Config
{
    public static function find($key)
    {/*{{{*/
        static $configs = array();

        if(array_key_exists($key,$configs))
        {
            return $configs[$key];
        }else{
            $configs = self::getConfigVars('common');
            return isset($configs[$key]) ? $configs[$key] : '';
        }
    }/*}}}*/

	public static function rout($key)
    {/*{{{*/
        static $configs = array();

        if(array_key_exists($key,$configs))
        {
            return $configs[$key];
        }else{
            $configs = self::getConfigVars('rout');
            return isset($configs[$key]) ? $configs[$key] : '';
        }
    }/*}}}*/

    static public function getConfigVars($type)
    {/*{{{*/
        include dirname(ROOT_PATH)."/config/{$type}.inc.php";
        return get_defined_vars();
    }/*}}}*/



}