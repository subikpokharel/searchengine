<?php

	require_once('header_searching.php');


	
?>

</br>


<div class = "row">
	<div class = "col-md-2"></div>
	<div class="box-tools ">
		<form method="post" id="Search_Bar">   <!-- action="../../cgi-bin/GetData.pl" -->
			<input type="hidden" class="form-control" name = "action" value="getData">
			<div class="input-group input-group-lg col-md-8 ">
                		<input type="text" name="searchBar" class="form-control" placeholder="Enter the keywords to be searched..."/>
				<div class="input-group-btn">
					<button type="submit" class="btn btn-default" name="btnSubmit"><i>Search</i></button>
                  		</div>
                	</div>
		</form>
	</div>

	<br/><br/>
	<div class="container" style="display: none;" id="searched_result">
		<table class="table table-bordered table-hover dataTable pull-center"  >

			<colgroup>
       				<col span="1" style="width: 7%;">
       				<col span="1" style="width: 23%;">
       				<col span="1" style="width: 30%;">
					<col span="1" style="width: 40%;">
    		</colgroup>	
			
			<thead>
				<tr>
    				<th style="text-align: center;">Rank</th>
    				<th style="text-align: center;">URL</th> 
    				<th style="text-align: center;">Title</th>
					<th style="text-align: center;">Description</th>
  				</tr>
			</thead>
			
			<tbody>
	
			</tbody>
	</div>
	<div class = "col-md-2"></div>
</div>




<?php

	require_once('footer.php');
?>
