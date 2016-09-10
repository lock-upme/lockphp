<?php
/**
 * @desc home page
 */
class Index extends Lock{
	
	public function __construct(){
		parent::__construct();
	}	
	
	/**
	 * @desc user home page
	 */
	public function Page_Index() {		
		/*
		//取表数据
		$sql = "SELECT id,username FROM ".TNAME."members limit 1";
		$this->db->query($sql);
		$result = $this->db->fetchRow();
		pr($result);
		
		//单条缓存
		$sql = "SELECT id,username FROM ".TNAME."members limit 1";
		$result = $this->db->fetchRowCache($sql);
		pr($result);
		
		//多数组缓存
		$sql = "SELECT id,username FROM ".TNAME."members limit 10";
		$result = $this->db->fetchRowsCache($sql);
		pr($result);
		*/
		$this->smarty->assign('lock','lockPHP 框架');
		$this->smarty->display('index.tpl');
	}
}
