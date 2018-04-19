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


		if (count($err) == 0) {
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
	<div class = "col-md-2"></div>
	<div class="box-tools ">
		<form method="post" id="Search_Bar">   <!-- action="../../cgi-bin/GetData.pl" -->
			<input type="hidden" class="form-control" name = "action" value="getData">
			<div class="input-group input-group-lg col-md-8 ">
                		<input type="text" name="searchBar" class="form-control" placeholder="Enter the url for data to be extracted..."/>
				<div class="input-group-btn">
					<button type="submit" class="btn btn-default" name="btnSubmit"><i>Submit</i></button>
                  		</div>
                	</div>
		</form>
	</div>
	<div class = "col-md-2"></div>
</div>




<?php

	require_once('footer.php');
?>
