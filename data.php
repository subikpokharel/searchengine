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
       				<col span="1" style="width: 5%;">
       				<col span="1" style="width: 30%;">
       				<col span="1" style="width: 25%;">
					<col span="1" style="width: 40%;">
    		</colgroup>	
			
			<thead>
				<tr>
    				<th>Sl.No</th>
    				<th>URL</th> 
    				<th>Title</th>
					<th>Description</th>
  				</tr>
			</thead>
			
			<tbody>
				<?php  $i = 1;foreach ($datalist as $dl) {?>
					<tr class="odd gradeX" align="justify">
						<td ><?php echo $i;?></td>
					    <td><a href="viewUrl.php?id=<?php echo($dl['url_id']); ?>"><strong><?php echo $dl['url'];?></strong></a></td>
					    <td><?php echo $dl['title'];?></td>
						<td><?php echo $dl['description'];?></td>
						
					</tr>

				<?php $i++;}?>
			</tbody>
		 </table>

	</div>
</div>

<?php

	require_once('footer.php');
?>

