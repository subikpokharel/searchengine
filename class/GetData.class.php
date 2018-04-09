
<?php

	class GetData{
		public $url, $action;

		public function getData(){
			if ($this->action == "getData") {
				$this->url = trim($this->url);

				# For security, remove some Unix metacharacters.
				$meta    = array( ";", ">", ">>", ";", "*", "?", "&", "|" );
				$this->url= str_replace( $meta, "", $this->url );
				

				$cmd = "lynx -dump -source '" . $this->url . "' > result.txt";
				//system( "sudo touch result.txt" );
				system( "sudo chmod 777 result.txt" );
				system( $cmd );
				system( "sudo chmod 755 result.txt " );
				system( "sudo cat result.txt" );

				$html = file_get_contents("result.txt");
				//Create a new DOM document
				$dom = new DOMDocument;

				//Parse the HTML. The @ is used to suppress any parsing errors
				//that will be thrown if the $html string isn't valid XHTML.
				@$dom->loadHTML($html);

				//Get all links. You could also use any other tag name here,
				//like 'img' or 'table', to extract other tags.
				$links = $dom->getElementsByTagName('a');

				//Iterate over the extracted links and display their URLs
				/*foreach ($links as $link){
				    //Extract and show the "href" attribute.
				    echo $link->nodeValue; echo "--------->\t";
				    echo $link->getAttribute('href'), '<br>';
				}*/
				

				$nodes = $dom->getElementsByTagName('title');
				//get and display what you need:
				$title = $nodes->item(0)->nodeValue;
				echo ("TITlE-->".$title.'<br>');
				$tags = get_meta_tags("result.txt");
				//print_r($tags);


				if(trim($tags['description'])!='') //if description is set and not empty
				{
    					echo ("Description-->".$tags['description']).'<br /><br />';
				}				

				echo "Keywords-->".$tags['keywords'];
				echo '<br><br>';
				//Iterate over the extracted links and display their URLs
                                foreach ($links as $link){
                                    //Extract and show the "href" attribute.
                                    //echo $link->nodeValue; echo "--------->\t";
					if($link->nodeValue != NULL && !empty($link->nodeValue) && !is_null($link->nodeValue)){
						echo $link->nodeValue; echo "--------->\t";
						echo $link->getAttribute('href'), '<br>';
					}
					/*else
						echo $link->getAttribute('alt');
					*/
                                    //echo $link->getAttribute('href'), '<br>';
                                }


				/*$keywordArray = explode(",", $tags['keywords']); //split string with keywords in an array
				foreach($keywordArray as $keyword) //for each entry in the array
				{	
    					echo $this->url.urlencode(trim($keyword)); //echo your URL. Encode the keyword in case special chars are present

					echo "<br>";
				}*/

				if($links->length > 0)
					return "Data success";
				else
					return "No Data in the URL entered";
			}else{
				return "Failed to Load Data";
			}
			
		}
	}

?>
