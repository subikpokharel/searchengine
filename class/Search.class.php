<?php

	require_once "DatabaseHelper.class.php";

	class Search{
		

		public function getData($keywords){
			$obj = new DatabaseHelper();
			$search_keys = array_unique(array_filter(explode(" ", $keywords)));
			$result = $obj->search($search_keys);
			$result = array_slice($result, 0, 10);
			//print_r($result);
			return $result;
		}



	}


/*

*/

?>