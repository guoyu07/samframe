<?php
class Http
{
	public static function getClientIp()
	{
		if ( $_SERVER['HTTP_CLIENT_IP'] ) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif( $_SERVER['HTTP_X_FORWARDED_FOR'] ) {
		    return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    return $_SERVER['REMOTE_ADDR'];
		}
	}

}
