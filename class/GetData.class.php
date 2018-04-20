
<?php

	require_once "DatabaseHelper.class.php";
	//$object = new DatabaseHelper();
	class GetData{
		public $url, $action, $length, $pages;

		public function getData(){
			$time_start = microtime(true); 
			if ($this->action == "getData") {
				//echo($this->pages);
				$this->url = trim($this->url);
				# For security, remove some Unix metacharacters.
				$meta    = array( ";", ">", ">>", ";", "*", "?", "&", "|" );
				$this->url= str_replace( $meta, "", $this->url );

				if (filter_var($this->url, FILTER_VALIDATE_URL)) {
					$urls = array();
					array_push($urls, $this->url);
					$this->length = sizeof($urls);
					//echo($length);

					for($i = 0; $i<$this->pages; $i++){
				    	//echo "<br>".$i;//."  ".$links[$i];
				    	if ($this->length >= $i) {
				    		$temp = $this->extractData($urls[$i]);
				    	
					    	if ($this->length < $this->pages) {
					    		$temp = array_merge(array_diff($temp, $urls));
					    		foreach ($temp as $t) {
						    		array_push($urls, $t);
						    	}
					    		$this->length = $this->length + sizeof($temp);
					    	}
				    	}
				    	
				    }

				    //return sizeof($urls)." data entered successfully";
				    //$this->extractData($this->url);
				    $time_end = microtime(true);

					//dividing with 60 will give the execution time in minutes otherwise seconds
					$execution_time = ($time_end - $time_start);

					//execution time of the script
					return '<b>Total Execution Time to insert '. $this->pages .' data is:</b> '.$execution_time.' seconds';
				} else 
				    return ("$this->url is not a valid URL");
			}
			
			
		}


		private function extractData($hyperlink){


			$obj = new DatabaseHelper();

			//echo $hyperlink;

			$html = file_get_contents($hyperlink);
			//Create a new DOM document
			$dom = new DOMDocument;

			//Parse the HTML. The @ is used to suppress any parsing errors
			//that will be thrown if the $html string isn't valid XHTML.
			@$dom->loadHTML($html);
			
			//extracting title from the dom document
			$nodes = $dom->getElementsByTagName('title');
			if (isset($nodes->item(0)->nodeValue) && trim($nodes->item(0)->nodeValue) != '') {
				$obj->title = $nodes->item(0)->nodeValue;
			}else
				$obj->title = "The page has no title.";
			
			$tags = get_meta_tags($hyperlink);


			//extracting the page description from the url if present
			if(isset($tags['description']) && trim($tags['description'])!='') //if description is set and not empty
			{
				$obj->description = $tags['description'];
			}else
				$obj->description = "The page has no description.";

			$obj->url = $hyperlink;

			$links = array();

			//insert the url into the database
			$id = $obj->saveUrls();
			$ref_id = $id[0][0];

			//if the url is successfully inserted into the database, next extract all the keywords and insert it into the database
			if($ref_id > 0){

				//extracting the body of the url
				$body = strip_tags(html_entity_decode($html));
				//extrack all the keywords from the webpage or URL
				$tempKeywordArray = $this->getKeywords($body, $tags);
				//remove all the stopwords from the keywords array
				$keywordArray = $this->removeStopWords($tempKeywordArray);

				$kwDatabase = $obj->selectAllKeywords();
				$resultDKW = array();
				$resultKWI = array();
				//seperate keywords and its ids into 2 different arrays
				foreach($kwDatabase as $kd){
					array_push($resultDKW, $kd['keyword']);
					array_push($resultKWI, $kd['kw_id']);
				}

				//extract the keyword that are not there inside the database
                $result = array_diff($keywordArray, $resultDKW);

                $duplicate_ids = array();
				//for all keywords in database extract the keyword ids of those which match the keywords in the page
				foreach($keywordArray as $value) {
					$index = array_search($value,$resultDKW);
  					if($index>0){
						array_push($duplicate_ids, $resultKWI[$index]);
					}
				}

				//insertduplicatekeyword
				if(sizeof($duplicate_ids)>0){
					$duplicate_ids = array_unique($duplicate_ids);
					$obj->insertDuplicateKeywords($ref_id, $duplicate_ids);
				}
				//insert keywords
				if(sizeof($result)>0){
                	$result = array_unique($result);
					$obj->saveKeyword($ref_id, $result);
                }

                $links = $this->extractUrls($hyperlink);
			}

			return $links;
		}



		private function getKeywords($url_body, $tags)
		 {
		 	
			$meta    = array( ";", ">", ">>", ":", "*", "?", "&", "|", "(", ")", "'", "{", "}", "“","”", "+", "‘","’" );
			$url_body = str_replace($meta, '', $url_body);
			//if other than alpha-numeric character, replace by ""
			$url_body = preg_replace("/[^a-zA-Z 0-9]+/", "", $url_body);
			//only extract alphabets words
			$url_body = preg_replace("/ ?\b[^ ]*[0-9][^ ]*\b/i", "", $url_body);
			$url_body = $this->explodeX(array(' ', ',', '.' ), $url_body);
			$url_body = array_unique(array_map("StrToLower",$url_body));
			$url_body = array_filter($url_body);


			if(isset($tags['keywords']) && trim($tags['keywords']) != ''){
				$keywordTemp = explode(",", $tags['keywords']); //split string with keywords in an array
				$keywordTemp = array_unique(array_map("StrToLower",$keywordTemp));
				$keywordTemp = array_merge($url_body);
			}else{
				$keywordTemp = array_merge($url_body);
			}

			$keywordTemp = array_map('trim',$keywordTemp);

			//remove empty strings and long words
			foreach ($keywordTemp as $key => $value) {
				if (strlen($value) > 30 || strlen($value) < 3  || !preg_match('/\S/', $value)) {
			        unset($keywordTemp[$key]);
			    }
			}
			$keywordArray = array_merge($keywordTemp);			
			$keywordArray =  array_map("utf8_encode", $keywordArray );

			return $keywordArray;

		 } 



		 private function removeStopWords($array)
		 {
		 	$words = array();
		 	$file = fopen("stopwords.txt", "r");
			while (!feof($file)) {
				$line = fgets($file);
				array_push($words, trim($line));
				//echo ($line);
			}
			fclose($file);

			$result_array = array_merge(array_diff($array, $words));

			return $result_array;


		 }

		 private function extractUrls($hyperlink){

			$cmd = "lynx -listonly -nonumbers -dump '" . $hyperlink . "' > hyperlink.txt";
			system( "chmod 777 result.txt" );
			system( $cmd );
			system( "chmod 755 result.txt" );

			$link = array();
			$file = fopen( "hyperlink.txt", "r" );
			while ( !feof( $file ) ) {
				$line = fgets( $file );
				//
				if (filter_var(trim($line), FILTER_VALIDATE_URL)) {
				    array_push($link, trim($line));
				}
			}

			$link = array_merge(array_unique($link));
			return $link;
		 }



		 private function explodeX( $delimiters, $string )
		{
		    return explode( chr( 1 ), str_replace( $delimiters, chr( 1 ), $string ) );
		}
	}

?>
