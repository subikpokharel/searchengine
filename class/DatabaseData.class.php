<?php

	require_once "Database.class.php";

	class DatabaseData extends Database{

		public $url, $title, $description;

		public function selectAllUrls(){
			return $this->select('tbl_urls', array('*'));
		}
	}
?>
