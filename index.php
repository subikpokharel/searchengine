<?php

	require_once('header_searching.php');

	require_once('class/Search.class.php');

	$data = new Search();



	if (isset($_POST['btnSearch'])) {
	//	print_r($_POST);
		$err = array();

		if (isset($_POST['keywordBar']) && !empty($_POST['keywordBar'])) {
			$keywords =  $_POST['keywordBar'];
			//$data->action = $_POST['searchData'];
		} else {
			$err['keywordBar'] = "Please enter a keyword";
		}


		if (count($err) == 0) {
			$result = $data->getData($keywords);
			//print_r($result);
		}
	}


	
?>

</br>

<div class = "row" style=" margin-top:50px;">
	<div class = "col-md-2"></div>
	<?php if (isset($err['keywordBar'])) {?>
		<div class="alert alert-danger alert-dismissable col-md-8">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?php echo $err['keywordBar'];?>
		</div>
	<?php }?>
</div>

<div class = "row">
	<div class = "col-md-2"></div>
	<div class="box-tools ">
		<form method="post" id="Keyword_Bar">   <!-- action="../../cgi-bin/GetData.pl" -->
			<input type="hidden" class="form-control" name = "action" value="searchData">
			<div class="input-group input-group-lg col-md-8 ">
                		<input type="text" name="keywordBar" class="form-control" placeholder="Enter the keywords to be searched..."/>
				<div class="input-group-btn">
					<button type="submit" class="btn btn-default" name="btnSearch"><i>Search</i></button>
                  		</div>
                	</div>
		</form>
	</div>

	<br/><br/>
	<div class="container"> <!-- style="display: none;" id="searched_result"> !-->
		<table class="table table-bordered table-hover dataTable pull-center"  >

			<colgroup>
       				<col span="1" style="width: 2%;">
       				<col span="1" style="width: 18%;">
       				<col span="1" style="width: 30%;">
					<col span="1" style="width: 40%;">
					<col span="1" style="width: 10%;">
    		</colgroup>	
			
			<thead>
				<tr>
    				<th style="text-align: center;">Rank</th>
    				<th style="text-align: center;">URL</th> 
    				<th style="text-align: center;">Title</th>
					<th style="text-align: center;">Description</th>
					<th style="text-align: right;">Visit Page</th>
  				</tr>
			</thead>
			
			<tbody>
				<?php  $i = 1;foreach ($result as $dl) {?>
					<tr class="odd gradeX" align="justify">
						<td ><?php echo $i;?></td>
					    <td><a href="viewUrl.php?id=<?php echo($dl['url_id']); ?>"><strong><?php echo $dl['url'];?></strong></a></td>
					    <td><?php echo $dl['title'];?></td>
						<td><?php echo $dl['description'];?></td>
						<td style="float: right;"><a href="<?php echo $dl['url']; ?> " class="btn btn-primary" target="_blank"> Visit page </a>   </td>
						
					</tr>

				<?php $i++;}?>
			</tbody>
	</div>
	<div class = "col-md-2"></div>
</div>


<!-- viewUrl.php?id=<?php echo($dl['url_id']); ?> !-->

<?php

	require_once('footer.php');
?>
