<?php

	require_once('header.php');


	//require_once('class/GetData.class.php');

	require_once('class/DatabaseHelper.class.php');

	

	if (isset($_GET['id']) && !empty($_GET['id'])) {
			$id = ($_GET['id']);
			$data = new DatabaseHelper();
			$urdetails  = $data->selectUrlById($id);
			//print_r($urdetails);
	} else {
		header("Location:index.php");
	}

?>


<br/>
<div class="box-header with-border">
              <h2 class="box-title"><strong>  URL : "<font color="#9932CC"><i><a href="<?php  echo($urdetails[0]['url']); ?>" target="_blank"><?php echo($urdetails[0]['url']); ?></a></i></font>"</strong></h2>
</div>
<br/>
<div id="box-body">
	<div class="row">
		<div class="col-sm-1"></div>
		<div class="col-sm-10">
				<div id="box-body">
					<div class="row">
						<div class="panel panel-default">
						<h3> &nbsp;&nbsp;URL Information </h3>
						<div class="panel-body">
							<label>Title </label>
				  			<div> <?php echo($urdetails[0]['title']); ?></div>
							<label for="title">Description</label>
				    			<div> <?php echo($urdetails[0]['description']); ?></div>
				    			<hr>
							<label>List of keywords</label>
				   			<div align="justify">
				   				<?php 
				   					$id =  $urdetails[0]['url_id'];
									$keyList = $data->selectAllKeys($id);
									$j = 0;	$length = sizeof($keyList);
								?>



								<?php  foreach ($keyList as $kl) {?><a href = "viewKeyword.php?id=<?php echo $kl['kw_id']; ?>"><span><strong><?php echo $kl['keyword'];?></strong></span></a>
							<?php $j++;
							 	if($j<$length)
									echo ', ';
								else
									echo '.';} ?>
				   			</div>
						</div>
					</div>

					</div>
				</div>
		</div>
		<div class="col-sm-1"></div>

	</div>
</div>



<?php

	require_once('footer.php');
?>