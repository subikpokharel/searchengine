
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
			//get and display what you need:
			$obj->title = $nodes->item(0)->nodeValue;
			//ho ("TITlE-->".$title.'<br>');
			$tags = get_meta_tags($hyperlink);
			
			if(trim($tags['description'])!='') //if description is set and not empty
			{
    				//echo ("Description-->".$tags['description']).'<br /><br />';
				$obj->description = $tags['description'];
			}else
				$obj->description = null;

			$obj->url = $hyperlink;
			
			$id = $obj->saveUrls();
			//print_r($id);
			//echo $id[0]->url_id;
			foreach ($id as $dl)
				echo $dl[url_id];
			echo '<br><br>';
			echo "Keywords-->".$tags['keywords'];
			echo '<br><br>';
			//Iterate over the extracted links and display their URLs
                        foreach ($links as $link){
                         	//Extract and show the "href" attribute.
				if($link->nodeValue != NULL && !empty($link->nodeValue) && !is_null($link->nodeValue)){
					echo $link->nodeValue; echo "--------->\t";
					echo $link->getAttribute('href'), '<br>';
				}
                        }	
		} 
	}

?>
