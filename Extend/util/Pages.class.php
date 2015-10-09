<?php
class Pages {
	// 起始行数
	public $firstRow;
	
	// 列表每页显示行数
	public $listRows = 20;
	
	// 页面地址
	public $url;
	
	// 分页总页面数
	public $totalPage;
	
	// 总行数
	public $totalRows;
	
	// 当前页数
	public $nowPage;
	
	// 分页栏每页显示的页数
	public $rollPage = 5;

	public $fomate_rule = "d";
	public $replace_rule = "";
	
	// 分页显示定制
	protected $config = array('prev'=>'<i class="fa fa-angle-left"></i>','next'=>'<i class="fa fa-angle-right"></i>','first'=>'首页','last'=>'末页','theme'=>'%prePage%  %firstPage%  %passFirstNumPage%  %linkPage%  %passLastNumPage%  %lastPage%  %nextPage%  %goPage%');


	/**
     * 构造函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
	public function __construct($totalRows = 0, $listRows = 0, $nowPage = 1, $base = '', $query = '', $rollPage = 5, $other = array()) {
		if(!empty($other)){
			foreach($other as $param=>$value){
				$this->$param = $value;
			}
		}

		$this->totalRows = $totalRows;
		//$this->url = $this->setUrl($base, $query);
		$this->url = $base;

		$this->rollPage = $rollPage;
		$this->listRows = !empty($listRows) ? intval($listRows) : $this->listRows;
		$this->totalPage = ceil($this->totalRows/$this->listRows);//总页数
		$this->nowPage  = !empty($nowPage) ? intval($nowPage) : 1;
		
		if(!empty($this->totalPage) && $this->nowPage > $this->totalPage) {
			$this->nowPage = $this->totalPage;
		}
		if ($this->nowPage <= 0) {
			$this->nowPage = 1;
		}


		$this->firstRow = $this->listRows*($this->nowPage-1);
	}

	public function setConfig($name,$value) {
		if(isset($this->config[$name])) {
			$this->config[$name] = $value;
		}
	}

	/**
     * 分页显示输出
     */
	public function show($pageRewriteUrlName = '', $goPage = '') {
		if ( $this->totalPage <= 1 ) {
			return '';
		}

		$linkPage = $this->pageNum($pageRewriteUrlName);

		//上一页
		if ($this->nowPage != 1) {
			$prePage = $this->nowPage - 1;
			$prePageBar = '<li><a href="'.$this->getUrl($prePage, $pageRewriteUrlName).'">'.$this->config['prev'].'</a></li>';
		} else {
			$prePageBar = '<li class="disabled"><a href="#">'.$this->config['prev'].'</a></li>';
		}

		//下一页
		if ($this->nowPage < $this->totalPage) {
			$nextPage = $this->nowPage + 1;
			$nextPageBar = '<li><a href="'.$this->getUrl($nextPage, $pageRewriteUrlName).'">'.$this->config['next'].'</a></li>';
		} else {
			$nextPageBar = '<li class="disabled"><a href="#">'.$this->config['next'].'</a></li>';		
		}


		//第1页
		if ($this->minPage != 1) {
			$firstPageBar = '<li><a href="'.$this->getUrl(1, $pageRewriteUrlName).'">1</a></li>';
		} else {
			$firstPageBar = "";
		}

		//最后1页
		if ($this->maxPage < $this->totalPage) {
			$lastPageBar = '<li><a href="'.$this->getUrl($this->totalPage, $pageRewriteUrlName).'">'.$this->totalPage.'</a></li>';
		} else {
			$lastPageBar = "";	
		}


		//省略号
		$passFirstNumPageBar = $passLastNumPageBar = '';
		if ($this->minPage > 2) {
			$passFirstNumPageBar = '<li><a href="#">...</a></li>';
		}
		if ($this->maxPage < $this->totalPage - 1) {
			$passLastNumPageBar = '<li><a href="#">...</a></li>';
		}


		$pageStr = str_replace(
		array('%prePage%','%firstPage%','%passFirstNumPage%','%linkPage%','%passLastNumPage%','%lastPage%','%nextPage%','%goPage%'),
		array($prePageBar,$firstPageBar,$passFirstNumPageBar,$linkPage,$passLastNumPageBar,$lastPageBar,$nextPageBar,$goPage), $this->config['theme']);
		
		return $pageStr;
	}


	//对页数进行循环显示，如 1 2 3 4 5
	public function pageNum($pageRewriteUrlName = '') {
		$page = $this->nowPage;//当前页
		$rollPage = $this->rollPage;//每次分页数
		$totalPage = $this->totalPage;//总页数

		if($page <= $rollPage) {
			$minPage = 1;
			$maxPage = $rollPage;
		} elseif($page >= $totalPage - ($rollPage-1)) {
			$minPage = $totalPage - ($rollPage-1);
			$maxPage = $totalPage;
		} else {
			$mid = floor( $rollPage / 2 );
			$last = $rollPage - 2;
			$minPage = ( $page - $mid ) < 1 ? 1 : $page - $mid;
			$maxPage = $minPage + $last;
		}

		if ( $maxPage > $totalPage ) {
			$maxPage = $totalPage;
			//$minPage = $maxPage - $last;
			$minPage = $minPage < 1 ? 1 : $minPage;
		}

		
		$this->minPage = $minPage;
		$this->maxPage = $maxPage;

		$numPageBar = '';
		for ( $i = $minPage; $i <= $maxPage; $i++ ) {
			if ($i == $page) {
				$numPageBar .= '<li class="active"><a href="#">' . $i . '</a></li>';
			} else {
				if ($page == 1) {
					$numPageBar .= "";
				}

				$numPageBar .= '<li><a href="'.$this->getUrl($i, $pageRewriteUrlName).'">'.$i.'</a></li>';
				if ($page == $totalPage) {
					$numPageBar .= "";
				}
			}
		}

		return $numPageBar;
	}

	//得到url地址
	protected function getUrl($page, $pageRewriteUrlName = '') {
		$url = sprintf($this->url,$page);
		return $url;
	}


	protected function setUrl($base = '', $query = '') {
		if ( !$base ) {
			if ( $_SERVER['HTTP_REQUEST_URI'] ) {
				list( $base, $query ) = explode( '?', $_SERVER['HTTP_REQUEST_URI'] );
			} else {
				list( $base, $query ) = explode( '?', $_SERVER['REQUEST_URI'] );
			}
		}
		$linkArray = explode( "page=", $query );
		$linkArg = $linkArray[0];
		if ( $linkArg == '' ) {
			$url = $base . "?";
		}else {
			$linkArg = substr( $linkArg, -1 ) == "&" ? $linkArg : $linkArg . '&';
			$url = $base . '?' . $linkArg;
		}
		$url = htmlspecialchars($url,ENT_COMPAT,'ISO-8859-1');

		return $url;
	}
	
}
