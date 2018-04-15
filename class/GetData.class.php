
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
				} else {
				    return ("$url is not a valid URL");
				}
				//$this->extractData($this->url);

				/*
				if (filter_var($url, FILTER_VALIDATE_URL)) {
    echo("$url is a valid URL");
} else {
    echo("$url is not a valid URL");
}
				if($links->length > 0)
					return "Data success";
				else
					return "No Data in the URL entered";*/
			}else{
				return "Failed to Load Data";
			}
			
		}


		private function extractData($hyperlink){

			//$data = new GetData();
			//$id = $data->insert();
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
			
			$nodes = $dom->getElementsByTagName('title');
			$obj->title = $nodes->item(0)->nodeValue;
			$tags = get_meta_tags($hyperlink);

			//print_r($tags);
			
			if(isset($tags['description']) && trim($tags['description'])!='') //if description is set and not empty
			{
				$obj->description = $tags['description'];
			}else
				$obj->description = "The page has no description";

			$obj->url = $hyperlink;

			
			
			//$body= str_replace( $meta, "", $body );
			//print_r($body);



			//insert the url into the database
			$id = $obj->saveUrls();
			$ref_id = $id[0][0];

			if($ref_id > 0){

				$keywordArray = $this->getKeywords($html, $tags);


				/*if(trim($tags['keywords']) != ''){
					//echo "Keywords-->".$tags['keywords'];
					$keywordArray = explode(",", $tags['keywords']); //split string with keywords in an array
					$keywordArray = array_unique(array_map("StrToLower",$keywordArray));
					$kwDatabase = $obj->selectAllKeywords();
					$resultDKW = array();
					$resultKWI = array();
					//seperate keywords and its ids into 2 different arrays
					foreach($kwDatabase as $kd){
						array_push($resultDKW, $kd[keyword]);
						array_push($resultKWI, $kd[kw_id]);
					}


					$resultDKW = array_unique(array_map("StrToLower",$resultDKW));
					$resultKWI = array_unique(array_map("StrToLower",$resultKWI));

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
						$obj->insertDuplicateKeywords($ref_id, $duplicate_ids);
					}
					//insert keywords
					if(sizeof($result)>0){
                                                //echo "We have new data";
						$obj->saveKeyword($ref_id, $result);
                    }

				}
				else
					echo "No Keywords";*/
			}

			/*$plain =  file_get_html($hyperlink); 
			print_r( $plain);*/
			/*//Iterate over the extracted links and display their URLs
                        foreach ($links as $link){
                         	//Extract and show the "href" attribute.
				if($link->nodeValue != NULL && !empty($link->nodeValue) && !is_null($link->nodeValue)){
					echo $link->nodeValue; echo "--------->\t";
					echo $link->getAttribute('href'), '<br>';
				}
                        }	*/

			/*$links_array = array();
			 foreach ($links as $link){
                                //Extract and show the "href" attribute.
				//$link = $l;// trim($l);
                                if(trim($link->nodeValue) != NULL && !empty($link->nodeValue)){
                                        echo $link->nodeValue; echo "--------->\t";
                                        echo $link->getAttribute('href'), '<br>';
                                }
                        }*/	
		}



		public function getKeywords($html, $tags)
		 {
		 	//extracting the body of the url
			$url_body = strip_tags(html_entity_decode($html));
			//echo ($url_body);
			$meta    = array( ";", ">", ">>", ";", "*", "?", "&", "|", ":", "(", ")", ".", "'", ",", "{", "}" );
			$url_body = str_replace($meta, '', $url_body);
			$url_body = explode(" ", $url_body);
			//$body = preg_split("/[;,]\n/", $url_body);
			echo "<br><br>";
			$url_body = array_unique(array_map("StrToLower",$url_body));
			$url_body = array_filter($url_body);
			print_r($url_body);
			echo "<br><br>";


			if(isset($tags['keywords']) && trim($tags['keywords']) != ''){
				$keywordTemp = explode(",", $tags['keywords']); //split string with keywords in an array
				$keywordTemp = array_unique(array_map("StrToLower",$keywordTemp));
				print_r($keywordTemp);
				$keywordTemp = array_merge($url_body);
				echo "<br><br>";
				print_r($keywordTemp);
			}else{
				//echo "No Keyword tag";
				$keywordTemp = array_merge($url_body);
				echo "<br><br>";
				print_r($keywordTemp);
			}

			//remove empty strings and long words
			foreach ($keywordTemp as $key=>&$value) {
				//echo(strlen($value)).' ';
			    if (strlen($value) > 30 || strlen($value) < 3) {
			        unset($keywordTemp[$key]);
			    }
			}
			$keywordArray = array_merge($keywordTemp);
			echo "<br><br>";
			print_r($keywordArray);
			return $keywordArray;
		 } 
	}

?>
