<?php

	require_once "Database.class.php";

	class DatabaseHelper extends Database{

		public $url_id,$url, $title, $description, $keyword;

		public function saveUrls(){
			$data = get_object_vars($this);
			unset($data['url_id']);
			unset($data['keyword']);
			//echo "Saving ---->   ".$data[url];
			//print_r($data);
			$result = $this->insert('tbl_urls', array_keys($data), array_values($data));
			//echo $url_id;
			if($result == 1)
				return($this->select('tbl_urls', array('url_id'), array('url' => $this->url)));

			return false;
		}

		public function selectAllUrls(){
			return $this->select('tbl_urls', array('*'));
		}


		public function saveKeyword($id, $keywordArray){

			//print_r($keywordArray);

			$sql = "INSERT INTO tbl_keywords (keyword) VALUES ";

			//ho "IN";
			/*$result = $this->insert('tbl_keywords', array('keyword'), array($this->keyword));
			//echo $result;
			if($result == 1){
                                $kwid = ($this->select('tbl_keywords', array('kw_id'), array('keyword' => $this->keyword)));
				//print_r($kwid);

				//echo $kwid[0][0];
				$this->insert('tbl_www_index', array('kw_id','url_id'), array($kwid[0][0], $id));
			}*/

/*INSERT INTO tbl_name
    (a,b,c)
VALUES
    (1,2,3),
    (4,5,6),
    (7,8,9);*/

		}
	}
?>
