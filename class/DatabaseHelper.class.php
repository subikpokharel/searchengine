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


		public function saveKeyword($id, $array){

			$Karray = array();
			$Karray = array_unique(array_map("StrToLower",$array));


			$sql = "INSERT INTO tbl_keywords (keyword) VALUES ";
			foreach($Karray as $kw){
				$sql = $sql."('$kw'),";
			}
			$sql = substr($sql, 0, strlen($sql)-1);	
			//echo($sql);
			$result = $this->execute_sql($sql);
			if($result == 1){
				$sql = "SELECT kw_id FROM tbl_keywords WHERE keyword in (";
				foreach($Karray as $kw){
                	$sql = $sql."'$kw',";
		        }
				$sql = substr($sql, 0, strlen($sql)-1);
				$sql = $sql.")";
				//echo $sql;
				$ids = $this->select_query($sql);
				//print_r($ids);
				$ref_id = array();
				foreach($ids as $value)
					array_push($ref_id, $value[0]);

				$sql = "INSERT INTO tbl_www_index (kw_id, url_id) VALUES ";
				foreach($ref_id as $ri){
        	    	$sql = $sql."('$ri','$id'),";
	        	}
                $sql = substr($sql, 0, strlen($sql)-1);
                //echo $sql;
                $this->execute_sql($sql);
			}

		}


		public function insertDuplicateKeywords($id, $array){
			$sql = "INSERT INTO tbl_www_index (kw_id, url_id) VALUES ";
			foreach ($array as $a)
				$sql = $sql."('$a','$id'),";
			$sql = substr($sql, 0, strlen($sql)-1);
			//echo $sql;
			$this->execute_sql($sql);
		}

		public function selectAllKeywords(){
			return $this->select('tbl_keywords', array('*'));
		}


		public function selectAllKeys($id){

			$sql = "SELECT c.keyword, c.kw_id FROM tbl_www_index a JOIN tbl_keywords c ON a.kw_id = c.kw_id where a.url_id = $id ";
			return($this->select_query($sql));
		}
	
		public function selectKeywordById($id){
			$sql = "SELECT a.url_id,b.url, b.title, b.description, c.keyword FROM tbl_www_index a ";
			$sql .= "JOIN tbl_urls b ON a.url_id = b.url_id ";
			$sql .= "JOIN tbl_keywords c ON a.kw_id = c.kw_id where a.kw_id in ($id);";
			//echo $sql;

			return($this->select_query($sql));
		}
		//selectUrlById
		public function selectUrlById($id){
			$sql = "SELECT * FROM tbl_urls WHERE url_id = ($id);";
			//echo $sql;S
			return($this->select_query($sql));
		}

		public function search($keyword){
			//print_r($keyword);
			$output = array();
			//for each keyword entered by the uer extract the data for each keywords
			foreach ($keyword as $key) {
				$sql = "SELECT DISTINCT (a.url_id),b.url, b.title, b.description FROM tbl_www_index a ";
				$sql .= " JOIN tbl_urls b ON a.url_id = b.url_id JOIN tbl_keywords c ON a.kw_id = c.kw_id ";
				$sql .= " where c.keyword LIKE '%".$key."%' ORDER BY CASE ";
				$sql .= " WHEN c.keyword LIKE '".$key."' THEN 0 WHEN c.keyword LIKE '".$key."%' THEN 1 ";
				$sql .= " WHEN c.keyword LIKE '%".$key."%' THEN 2  WHEN c.keyword LIKE '%".$key."' THEN 3 ";
				$sql .= " ELSE 4 END";
				//echo $sql;
				//echo "<br>";
				$result = ($this->select_query($sql));
				//store the output into array
				foreach ($result as $r) {
					array_push($output, $r);
				}
				//print_r($result);
				//echo "<br><br>";
				//echo(sizeof($output));
			}

			//echo "<br><br>";
			//print_r($output);
			$temp = array();
			//echo "<br><br>";
			//extract the url_id from the output
			foreach ($output as $key ) {
				//echo($key['url_id']);
				array_push($temp, $key['url_id']);
				//echo "<br>";
			}

			//count the number of each url_id returned from the database
			$temp = (array_count_values($temp));
			//print_r($temp);
			//sort the count in descending order
			arsort($temp);
			//print_r($temp);
			//echo "<br><br>";
			 //array_map("unserialize", array_unique(array_map("serialize", $output)));
			//remove the repeated data from the output array
			$output = (array_map("unserialize", array_unique(array_map("serialize", $output))));
			$output = array_merge($output);
			//print_r($output);
			//echo "<br><br>";
			//echo(sizeof($output));
			//echo "<br><br>";


			$sorted = array();
			//$i = 0;
			//rank the output base on the occurance of keyword in the total urls
			foreach ($temp as $key => $value) {
				//echo($i);
				//echo($key);
				$i = 0;
				$id = $key;
				foreach ($output as $key => $value) {

					/*print_r($output[$i]);
						echo "<br>";*/
					if ($value[0] == $id) {
						array_push($sorted, $output[$i]);
						//print_r($output[$i]);
						//echo "<br>";
					}
					$i++;	
				}
				//echo "<br>";
			}
			//echo "<br><br>";
			//print_r($sorted);
			return $sorted;
		}
	}
?>
