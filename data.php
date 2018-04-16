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
		<table class="table table-bordered table-hover dataTable pull-center" id ="game_table">
			<thead>
				<tr>
    					<th>Sl.No</th>
    					<th>URL</th> 
    					<th>Title</th>
					<th>Description</th>
					<th>Keywords</th>
  				</tr>
			</thead>
			<tbody>
				<?php  $i = 1;foreach ($datalist as $dl) {?>
					<tr class="odd gradeX" align="justify">
						<td ><?php echo $i;?></td>
					    <td><?php echo $dl['url'];?></td>
					    <td><?php echo $dl['title'];?></td>
						<td><?php echo $dl['description'];?></td>
						<?php $id =  $dl['url_id'];
							$keyList = $data->selectAllKeys($id);
							$j = 0;	$length = sizeof($keyList);
						?>
						<td><?php  foreach ($keyList as $kl) {?><a href = "."><span><strong><?php echo $kl['keyword'];?></strong></span></a>
							<?php $j++;
							 	if($j<$length)
									echo ', ';
								else
									echo '.';} ?>
						</td>
					</tr>

				<?php $i++;}?>
			</tbody>
		 </table>

	</div>
</div>

<?php

	require_once('footer.php');
?>

