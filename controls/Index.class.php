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
		$sql = "SELECT uid,username FROM ".TNAME."member limit 1";
		$this->db->query($sql);
		$result = $this->db->fetchRow();
		print_r($result);
		
		//单条缓存
		$sql = "SELECT uid,username FROM ".TNAME."member limit 1";
		$result = $this->db->fetchRowCache($sql);
		print_r($result);
		
		//多数组缓存
		$sql = "SELECT uid,username FROM ".TNAME."member limit 10";
		$result = $this->db->fetchRowsCache($sql);
		print_r($result);
		*/
		
		$this->smarty->assign('lock','LockPhp 框架');
		$this->smarty->display('index.tpl');
	}
	

}
