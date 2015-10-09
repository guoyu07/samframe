<?php
class Strings
{
	public static function mb_cut($str, $length, $charset = 'UTF-8', $suffix = '…', $clearHtml = true)
	{
		$str = trim(htmlspecialchars_decode( $str ));
		if($clearHtml) {
			$str = strip_tags($str);
		}
		$strcut = '';
		if(mb_strlen($str, $charset) > $length) {
			$strcut = mb_substr($str, 0, $length, $charset);
			if(!empty($strcut)) {
				$strcut .= $suffix;
			}
		} else {
		    $strcut = $str;
		}
		return htmlspecialchars($strcut);
	}

}
