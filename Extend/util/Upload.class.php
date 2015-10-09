<?php
/**
*文件上传类
*@支持缩略图、水印
*/

class Upload
{
	public $allow_type = array('pjpeg','gif','bmp','png','jpg','jpeg');
	public $max_size = '100000';
	public $auto_dir_on =true;//保存目录不存在是否自动创建

	public $thumb = false;//是否生成缩略图
	public $thumb_width = array(100,50);//缩略图宽度，数量表示缩略图数目
	public $thumb_height = array(50,50);//缩略图高度
	public $thumb_preffix = array("m_","b_");//缩略图前缀
	public $thumb_path = '';
	public $thumb_del_on = true;//删除原图

	public $water = false;//是否添加水印
	public $water_path = './upload/water.png';//水印路径

	public $save_path = '';//保存路径
	public $sub_dir_on = false;//是否启用子目录
	public $sub_dir_type = "date"; //子目录规则hash、date
	public $date_format = "Ymd";
	public $name_rule = "uniqid";//新文件名生成规则time、date
	public $error_msg = '';
	public $same_replace = true;

	public function __construct($config = array())
	{
	}

	private function createDir($save_path)
	{
		$dir_arr = explode("/",$save_path);
		array_shift($dir_arr);
		$n = count($dir_arr);

		$dir = ".";
		for($i=0;$i<$n;$i++){
			$dir .="/".$dir_arr[$i]; 
			if(!is_dir($dir)) mkdir($dir);
		}
	}

	public function upload($files)
	{
		$save_path = $this->save_path;

		if(!is_dir($save_path)){
			if($this->auto_dir_on){
				$this->createDir($save_path);
			}else{
				$this->error_msg = "保存目录不存在！！！";
				return false;
			}
		}

		$files = $this->arrayFile($files);

		foreach($files as $key=>&$file){
			$this->getFileType($file);
			if($this->check($file)){
				$this->getSavePath($file);
				$this->getSaveName($file);
				$this->save($file);
			}else{
				$file['res'] = 1;
				unset($file['tmp_name']);
			}
		}
		return $files;
	}

    /**
	*组合上传文件数组
	*@access private
	*@param array $files
	*@return array
	*/
	private function arrayFile($files)
	{
		$newfile = array();

		if(is_array($files['name'])){
			//多文件上传
			$n = count($files['name']);
			for($i=0;$i<$n;$i++){
				if(empty($files['name'][$i])){
					continue;
				}

				$newfile[] = array(
					'name'=>$files['name'][$i],
					'type'=>$files['type'][$i],
					'tmp_name'=>$files['tmp_name'][$i],
					'error'=>$files['error'][$i],
					'size'=>$files['size'][$i],
					);
			}
		}else{
			//单个文件
			$newfile[]=$files;
		}
		return $newfile;
	}

	private function getFileType(&$file)
	{
		$file_info = pathinfo($file['name']);
		$file['type'] = $file_info['extension'];
	}

    /**
	*获取文件保存目录，支持子目录
	*@access private
	*@param array $files
	*@return null
	*/
	private function getSavePath(&$file)
	{
		$savepath = $this->save_path;
		//若使用子目录
		if($this->sub_dir_on)
		{
			switch($this->sub_dir_type)
			{
				case 'date':
					$dir = date("{$this->date_format}",time());
					break;
				case 'hash':
				default:
					$dir = md5($file['name']);
			}

			$savepath .="/".$dir;
			if(!is_dir($savepath))
			{
				mkdir($savepath,0777,true);
			}
		}
		$file['savepath'] = $savepath;
	}

    /**
	*获取文件名
	*@access private
	*@param array $files
	*@return null
	*/
	private function getSaveName(&$file)
	{
		$rule = $this->name_rule;
		switch($rule){
			case 'date':
				$name = date("YmdHis",time());
				break;
			case 'time':
				$name = time();
			     break;
			case 'uniqid':
			default:
				$name = uniqid();
		}
        //生成新文件名，包含后缀
		$file['savename'] = $name.".".$file['type'];
	}

    /**
	*保存文件
	*@access private
	*@param array $file index
	*/
	private function save(&$file)
	{
		$filename = $file['savepath']."/".$file['savename'];
        if(!$this->same_replace && is_file($filename)) {
            // 不覆盖同名文件
            $this->error_msg	=	'文件已经存在！'.$filename;
            return false;
        }

		if(!move_uploaded_file($file['tmp_name'], $filename)) {
			$file['res'] = 1;
            $this->error_msg = '文件上传保存错误！';
			unset($file['tmp_name']);
            return false;
        }else{
			$file['res'] = 0;
			unset($file['tmp_name']);

			if($this->water && in_array($file['type'],array('gif','jpg','jpeg','bmp','png'))){
				$this->setWater($file['savepath']."/".$file['savename']);
			}
			//是图片则生成缩略图
			if($this->thumb && in_array($file['type'],array('gif','jpg','jpeg','bmp','png'))){
				$this->setThumb($file);
			}
			
		}
	}

	private function setWater($source)
	{
		//import("util/Image.class.php");//此类在调用upload时引入
		Image::water($source, $this->water_path,null,100);
	}

	/**
	*生成缩略图
	*/
	private function setThumb(&$file)
	{
		//import("util/Image.class.php");
		$filename = $file['savepath']."/".$file['savename'];
		$num = count($this->thumb_preffix);
		$thumb_path = empty($this->thumb_path)?$file['savepath']:$this->thumb_path;
		$thumb_preffix = $this->thumb_preffix;
		$thumb_width = $this->thumb_width;
		$thumb_height = $this->thumb_height;
		if(!is_dir($thumb_path)){
			$this->createDir($thumb_path);
		}

		for($i=0; $i<$num; $i++) {
			$thumb_name = $thumb_path."/".$thumb_preffix[$i].$file['savename'];
			$res = Image::thumb($filename,$thumb_name,'',$thumb_width[$i],$thumb_height[$i],true);
			if(!empty($res)){
				$file['thumb'][] = $res;
			}
		}

		if($this->thumb_del_on){
			unlink($filename);
			unset($file['savename']);
			unset($file['savepath']);
		}

	}

    /**
	*检查文件是否合法
	*@param array $file
	*@return boolean
	*/
	private function check($file)
	{
		if($file['error'] != 0){
			$this->error_msg = "文件上传错误";
			return false;
		}

		if($file['size'] > $this->max_size){
			$this->error_msg = "文件过大";
			return false;
		}

		if(!in_array(strtolower($file['type']),$this->allow_type)){
			$this->error_msg = "文件类型不合法";
			return false;
		}

		if(is_uploaded_file($file['name'])){
			$this->error_msg = "文件非法提交";
			return false;
		}
		return true;
	}

    /**
	*获取错误信息
	*/
	public function getError()
	{
		return $this->error_msg;
	}

}
