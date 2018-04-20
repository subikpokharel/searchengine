<?php

	require_once('header.php');


	require_once('class/GetData.class.php');
	//require_once( 'class/Test.class.php');


	$data = new GetData();

	//$data = new Test();
	
	if (isset($_POST['btnSubmit'])) {
	//	print_r($_POST);
		$err = array();

		if (isset($_POST['searchBar']) && !empty($_POST['searchBar'])) {
			$data->url =  $_POST['searchBar'];
			$data->action = $_POST['action'];
		} else {
			$err['searchBar'] = "Please enter a URL";
		}


		if (isset($_POST['pages']) && !empty($_POST['pages']) && $_POST['pages']>10) {
			$data->pages =  $_POST['pages'];
		} else {
			$err['searchBar'] = "Please enter Total pages to be extracted";
		}



		if (count($err) == 0) {
			//print_r($_POST);
			$received = $data->getData();
		}
	}

?>



<div class = "row" style=" margin-top:50px;">
	<div class = "col-md-2"></div>
	<?php if (isset($err['searchBar'])) {?>
		<div class="alert alert-danger alert-dismissable col-md-8">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?php echo $err['searchBar'];?>
		</div>
	<?php }?>


	<?php if (isset($received)) {?>
		<div class="alert alert-success alert-dismissable col-md-8">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?php echo $received;?>
		</div>
	<?php }?>

</div>




<div class = "row">
	<form method="post" id="Search_Bar"> 
		<div class = "col-md-2"></div>
		<div class="box-tools ">
			  <!-- action="../../cgi-bin/GetData.pl" -->
				<input type="hidden" class="form-control" name = "action" value="getData">
				<div class="input-group input-group-lg col-md-8 ">
	                	<input type="text" name="searchBar" class="form-control" placeholder="Enter the url for data to be extracted..."/>
					<div class="input-group-btn">
						<button type="submit" class="btn btn-default" name="btnSubmit"><i>Extract Data</i></button>
	                 </div>
	            </div>
		</div>
		<div class = "col-md-2"></div>

		<br/>	

		<div class = "col-md-4"></div>
		<div class="box-tools ">
				<input type="hidden" class="form-control" name = "action" value="getData">
				<div class="input-group input-group-lg col-md-2 ">
						<label for="id_pages">Maximum pages to index: <strong>*</strong></label>
						<input type="number" name="pages" value="100" class="form-control" id="id_pages" placeholder="Page Limit"/>
	            </div>
		</div>
		<div class = "col-md-2"></div>
	</form>
</div>




<?php

	require_once('footer.php');
?>
