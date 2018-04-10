<?php

	require_once "Database.class.php";

	class DatabaseHelper extends Database{

		public $url_id,$url, $title, $description;

		public function saveUrls(){
			$data = get_object_vars($this);
			unset($data['url_id']);
			$result = $this->insert('tbl_urls', array_keys($data), array_values($data));
			//echo $url_id;
			if($result == 1)
				return($this->select('tbl_urls', array('url_id'), array('url' => $this->url)));

			return false;
		}

		public function selectAllUrls(){
			return $this->select('tbl_urls', array('*'));
		}
	}
?>
