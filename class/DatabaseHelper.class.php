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


		public function saveKeyword($id){

			//print_r($keywordArray);

			/*$sql = "INSERT INTO tbl_keywords (keyword) VALUES ";
			
			foreach($keywordArray as $kw){
				$sql = $sql."('$kw'),";
			}
			$sql = substr($sql, 0, strlen($sql)-1);	
			echo $sql;


			$result = $this->execute_sql($sql);
			if($result == 1){
                                $kw = ($this->select('tbl_keywords', array('*')));
				print_r($kw);
				echo '<br>';
				/*foreach($kw as $k)
					echo $k[0],'<-->';
				*///echo $kwid[0][0];
				//$this->insert('tbl_www_index', array('kw_id','url_id'), array($kwid[0][0], $id));
			 $kw = $this->select('tbl_keywords', array('kw_id'), array('keyword' => $this->keyword));
                        // print_r($kw);
			//echo $kw[0][kw_id];
			if($kw[0][kw_id] > 0)
				$this->insert('tbl_www_index', array('kw_id','url_id'), array($kwid[0][kw_id], $id));
			else{
				$result = $this->insert('tbl_keywords', array('keyword'), array($this->keyword));
                        	if($result == 1){
                                	$kw_data = $this->select('tbl_keywords', array('kw_id'), array('keyword' => $this->keyword));
					echo $kw_data[0][kw_id], '--';
					if($kw_data[0][kw_id] > 0)
						$this->insert('tbl_www_index', array('kw_id','url_id'), array($kw_data[0][kw_id], $id));
				}

			}
			/*$result = $this->insert('tbl_keywords', array('keyword'), array($this->keyword));
			if($result == 1){
				$kw = $this->select('tbl_keywords', array('kw_id'), array('keyword' => $this->keyword));
				print_r($kw);
			}*/				
		}

/*INSERT INTO tbl_name
    (a,b,c)
VALUES
    (1,2,3),
    (4,5,6),
    (7,8,9);


/*foreach($keywordArray as $keyword) //for each entry in the array
					{	
    						$keyword = (trim($keyword)); //echo your URL. Encode the keyword in case special chars are present
						$obj->keyword = $keyword;
						$obj->saveKeyword($ref_id);
						//echo "<br>";
					}
*/

	//	}
	}
?>
