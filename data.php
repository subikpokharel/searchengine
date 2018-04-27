<?php

	require_once('header.php');


	//require_once('class/GetData.class.php');

	require_once('class/DatabaseHelper.class.php');

	$data = new DatabaseHelper();
	$datalist  = $data->selectAllUrls();

	//print_r($datalist);
?>


<br/>
<!-- This is game list page.-->
<div class="box-header with-border">
              <h2 class="box-title"><strong>List of URL's</strong></h2>
</div>
<br/>
<div id="box-body">
	<div class="row">
		<table class="table table-bordered table-hover dataTable pull-center">

			<colgroup>
       				<col span="1" style="width: 2%;">
       				<col span="1" style="width: 18%;">
       				<col span="1" style="width: 30%;">
					<col span="1" style="width: 40%;">
					<col span="1" style="width: 10%;">
    		</colgroup>	
			
			<thead>
				<tr>
    				<th style="text-align: center;">Sl.No</th>
    				<th style="text-align: center;">URL</th> 
    				<th style="text-align: center;">Title</th>
					<th style="text-align: center;">Description</th>
					<th style="text-align: right;">Visit Page</th>
  				</tr>
			</thead>
			
			<tbody>
				<?php  $i = 1;foreach ($datalist as $dl) {?>
					<tr class="odd gradeX" align="justify">
						<td ><?php echo $i;?></td>
					    <td><a href="viewUrl.php?id=<?php echo($dl['url_id']); ?>"><strong><?php echo $dl['url'];?></strong></a></td>
					    <td><?php echo $dl['title'];?></td>
						<td><?php echo $dl['description'];?></td>
						<td style="float: right;"><a href="<?php echo $dl['url']; ?> " class="btn btn-primary" target="_blank"> Visit page </a>   </td>
						
					</tr>

				<?php $i++;}?>
			</tbody>
		 </table>

	</div>
</div>

<?php

	require_once('footer.php');
?>

