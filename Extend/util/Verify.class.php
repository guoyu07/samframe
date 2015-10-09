<?php
class Verify
{
	static public function get()
	{
		$code_list = array();
		for($num = 48;$num <= 57;$num++){
			$code_list[] = chr($num);
		}
		for($num = 65;$num <= 90;$num++){
			$code_list[] = chr($num);
		}
		for($num = 97;$num <= 122;$num++){
			//$code_list[] = chr($num);
		}
		for($i = 0;$i < 4;$i++){
			$code[] = $code_list[array_rand($code_list,1)];
		}

		$_SESSION['verify'] = strtolower(implode('',$code)); 
		return Image::buildImageVerify(implode('',$code));
	}

	static public function check($code)
	{
		if(strtolower($code) === $_SESSION['verify']){
			unset($_SESSION['verify']);
			return true;
		}else{
			unset($_SESSION['verify']);
			return false;
		}
	}
}
