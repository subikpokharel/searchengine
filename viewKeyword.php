
<?php

	require_once('header.php');


	//require_once('class/GetData.class.php');

	require_once('class/DatabaseHelper.class.php');

	

	if (isset($_GET['id']) && !empty($_GET['id'])) {
			$id = ($_GET['id']);
			$data = new DatabaseHelper();
			$keywordlist  = $data->selectKeywordById($id);
	} else {
		header("Location:index.php");
	}

?>



<br/>
<!-- This is game list page.-->
<div class="box-header with-border">
              <h2 class="box-title"><strong>Selected Keyword: "<font color="#9932CC"><i><?php echo ucfirst($keywordlist[0]['keyword']); ?></i></font>" </strong></h2>
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
    				<th style="text-align: center;">Sl.No</th>
    				<th style="text-align: center;">URL</th> 
    				<th style="text-align: center;">Title</th>
					<th style="text-align: center;">Description</th>
  				</tr>
			</thead>
			
			
			<tbody>
				<?php  $i = 1; foreach ($keywordlist as $kl) {?>
					<tr class="odd gradeX" align="justify">
						<td ><?php echo $i;?></td>
					    <td><a href="viewUrl.php?id=<?php echo($kl['url_id']); ?>"><span><strong><?php echo $kl['url'];?></strong></span></a></td>
					    <td><?php echo $kl['title'];?></td>
						<td><?php echo $kl['description'];?></td>
					</tr>

				<?php $i++; } ?>
			</tbody>
		 </table>

	</div>
</div>


<?php

	require_once('footer.php');
?>