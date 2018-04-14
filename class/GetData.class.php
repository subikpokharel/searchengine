
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

				$this->extractData($this->url);

				/*if($links->length > 0)
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
			
			if(trim($tags['description'])!='') //if description is set and not empty
			{
				$obj->description = $tags['description'];
			}else
				$obj->description = "The page has no description";

			$obj->url = $hyperlink;
			//echo "data-->".$obj->url;
			$id = $obj->saveUrls();
			foreach ($id as $dl)
				$ref_id = $dl[url_id];

			if($ref_id > 0){
				if(trim($tags['keywords']) != ''){
					//echo "Keywords-->".$tags['keywords'];
					$keywordArray = explode(",", $tags['keywords']); //split string with keywords in an array
					$keywordArray = array_unique(array_map("StrToLower",$keywordArray));
					$kwDatabase = $obj->selectAllKeywords();
					$resultDKW = array();
					$resultKWI = array();
					foreach($kwDatabase as $kd){
						array_push($resultDKW, $kd[keyword]);
						array_push($resultKWI, $kd[kw_id]);
					}

					$resultDKW = array_unique(array_map("StrToLower",$resultDKW));
					$resultKWI = array_unique(array_map("StrToLower",$resultKWI));


                                        $result = array_diff($keywordArray, $resultDKW);
                                        //echo "NOOOO Data";
                                        //print_r($result);
                                        //echo '<br>';
					$duplicate_ids = array();
					foreach($keywordArray as $value) {
						$index = array_search($value,$resultDKW);
  						if($index>0){
							array_push($duplicate_ids, $resultKWI[$index]);
						}
					}
					//echo '<br>';
					//print_r($duplicate_ids);

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
					echo "No Keywords";
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
	}

?>
