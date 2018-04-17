
<?php

	require_once "DatabaseHelper.class.php";
	//$object = new DatabaseHelper();
	class GetData{
		public $url, $action;
		//public $object = new DatabaseHelper();

		/*public function __construct(){
			$obj = new DatabaseHelper();
		}*/

		public function getData(){
			if ($this->action == "getData") {
				$this->url = trim($this->url);
				# For security, remove some Unix metacharacters.
				$meta    = array( ";", ">", ">>", ";", "*", "?", "&", "|" );
				$this->url= str_replace( $meta, "", $this->url );

				if (filter_var($this->url, FILTER_VALIDATE_URL)) {
				    $this->extractData($this->url);
				} else 
				    return ("$url is not a valid URL");
			}else
				return "Failed to Load Data";
			
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

			//Get all links. You could also use any other tag name here,
			//like 'img' or 'table', to extract other tags.
			$links = $dom->getElementsByTagName('a');
			
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

			/*$tempKeywordArray = $this->getKeywords($html, $tags);
			$keywordArray = $this->removeStopWords($tempKeywordArray);
			print_r($keywordArray);*/
			

			//insert the url into the database
			$id = $obj->saveUrls();
			$ref_id = $id[0][0];

			//if the url is successfully inserted into the database, next extract all the keywords and insert it into the database
			if($ref_id > 0){

				//extrack all the keywords from the webpage or URL
				//$keywordArray = $this->getKeywords($html, $tags);
				$tempKeywordArray = $this->getKeywords($html, $tags);
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
					//echo "We have duplicates";
					$duplicate_ids = array_unique($duplicate_ids);
					echo "<br> Duplicates";
					print_r($duplicate_ids);
					$obj->insertDuplicateKeywords($ref_id, $duplicate_ids);
				}
				//insert keywords
				if(sizeof($result)>0){
                	//echo "We have new data";
                	$result = array_unique($result);
                	echo "<br>New Keys";
                	print_r($result);
					$obj->saveKeyword($ref_id, $result);
                }
			}

			$links = $this->extractUrls($hyperlink);
			print_r($links);
		}



		private function getKeywords($html, $tags)
		 {
		 	//extracting the body of the url
			$url_body = strip_tags(html_entity_decode($html));
			//preg_match('~<body[^>]*>(.*?)</body>~si', $html, $url_body);
			$meta    = array( ";", ">", ">>", ";", "*", "?", "&", "|", ":", "(", ")", ".", "'", ",", "{", "}", "“","”", "+", "‘","’" );
			$url_body = str_replace($meta, '', $url_body);
			//$url_body = preg_replace("/[^a-zA-Z 0-9]+/", "", $url_body);
			$url_body = preg_replace("/[^a-zA-Z 0-9]+/", "", $url_body);
			$url_body = preg_replace("/ ?\b[^ ]*[0-9][^ ]*\b/i", "", $url_body);
			$url_body = explode(" ", $url_body);
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
			foreach ($keywordTemp as $key=>&$value) {
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

		 	/*echo "<br><br>";
			echo "File dump URLs".'<br>';*/

			//$cmd = "lynx -dump '" . $hyperlink . "' > result.txt";
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

			/*print_r($link);
			echo "<br><br>";*/
			$link = array_merge(array_unique($link));
			//print_r(($link));
			return $link;
		 }
	}

?>
