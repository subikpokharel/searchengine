<?php

	require_once('header.php');


	//require_once('class/GetData.class.php');

	require_once('class/DatabaseData.class.php');

	$data = new DatabaseData();
	$datalist  = $data->selectAllUrls();

	print_r($datalist);


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
				<?php $i = 1;foreach ($datalist as $dl) {?>
					<tr class="odd gradeX">
						<td><?php echo $i;?></td>
					        <td><?php echo $dl[url];?></td>
					        <td><?php echo $dl[title];?></td>
						<td><?php echo $dl[description];?></td>
					</tr>

				<?php $i++;}?>
			</tbody>
		 </table>

	</div>
</div>



<script type="text/javascript">
/*	var xmlhttp = new XMLHttpRequest( );
	var url = "/class/?action=list_game";
	xmlhttp.onreadystatechange = function( ) {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			myFunction( xmlhttp.responseText );
			//alert(xmlhttp.responseText);
		}
	}
	xmlhttp.open( "GET", url, true );
	xmlhttp.send( );
	function myFunction( response ) {
		var arr = JSON.parse( response );
		var i,j;
		var out  = "<tr><th>Sl.No</th>" +
			"<th>ASIN</th>" +
			"<th>Title</th>" +
			"<th>Price</th>" +
			"<th>Developers Name</th>" +
			"<th>Action</th></tr>";
		for ( i = 0; i < arr.length; i++ ) {
			out += "<tr><td>" + (i+1) + 
			"</td><td>"+ arr[i].ASIN +
			"</td><td>" + "<a href ='viewGame.php?asin="+arr[i].ASIN+"&action=viewGame'><span><strong>"+ arr[i].TITLE +"</strong></span></a>" +
			"</td><td>" + arr[i].Price +"</td><td>";
			
			var el = arr[i].Developers;
			var tmp = "";
			for ( j = 0; j < el.length; j++ ) {
				tmp += "Dev "+(j+1)+": <a href ='viewDeveloper.php?id="+el[j].Developer_ID+"&action=viewDeveloper'><span><strong>"+ el[j].Developer_Name +"</strong></span></a><br>" +"";
			}
			out += tmp;
			out += "</td><td>" + "<a href='addDeveloper.php?asin="+arr[i].ASIN+"&action=addDeveloper' class='btn btn-primary'>Add Developers</a>"+ 
			"</td></tr>";
		}
		//out += "</table>"
		document.getElementById( "game_table" ).innerHTML = out;
	}*/
</script>

<?php

	require_once('footer.php');
?>

