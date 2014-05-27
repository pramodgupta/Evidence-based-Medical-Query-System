<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
     
    <link rel="stylesheet" href="styles/gumby.css">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/admin.css">
    <script src="js/libs/modernizr-2.6.2.min.js"></script>
    <script src="js/jquery-2.0.3.min.js" language="javascript"></script>
	
	<style>
		table
		{
		width: 100%;
		}
		select, input[type="text"], input[type="file"] {
width: 100%;
}

a, a:hover
{
	color: white !important; 
	text-decoration: none !important;
}

div.btn
{
border-radius: 10px;
}
	</style>
	
	
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>

<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.3" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.3"></script>

<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>


<script type="text/javascript">
    $(".details").fancybox({
        maxWidth: 800,
        maxHeight: 600,
        fitToView: false,
        width: '70%',
        height: '70%',
        autoSize: false,
        closeClick: false,
        openEffect: 'elastic',
        closeEffect: 'elastic'
    });

    $(".email").fancybox({
        maxWidth: 750,
        maxHeight: 600,
        fitToView: false,
        width: '60%',
        height: '60%',
        autoSize: false,
        closeClick: false,
        openEffect: 'elastic',
        closeEffect: 'elastic'
    });

	
	
</script>


</head>
<body>

<div id="pageTitle" class="text-center">
Medical Application
</div>
   
   <div id="innerwrap" class="row">
   
<h2 style="text-align: center;"> Search Medical Application</h2>
<br><br>

<?php
	$con=mysqli_connect("localhost","root","","thesis") ;
	
	// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
	
	$searchtext = "";
	$searchtype = "";
	
		
?>


<form method="POST">
<ul>
      <li class="field" style="width: 60%; float:left;">
		<input type="text" name="query" id="query" value="Enter the Condition Search term over here" />
      </li>
	   <li class="field"  style=" float:right;">
	   <div class="picker">
<select name="search_type">
	<option value="condition_name">Medical Condition</option>
	<option value="drug">Drug</option>
	<option value="sideeffect">Side Effect</option>
</select>
</div>
</li>

  
 <div class="row" >
                     
                     <div class="pull_right">&nbsp; &nbsp;</div>
                     <div class="medium primary btn " style="width: 100%;"><input type="submit" value="Search" /></div>
                  </div>

</form>



<?php

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
$searchtext = $_POST['query'];
$search_type = $_POST["search_type"];

$query = "select distinct condition_name, drug from medical_new where ".$search_type." like '%".$searchtext."%'";
$result = mysqli_query($con, $query);

$row_count =  $result->num_rows;

if($row_count != 0)
{

	echo "<table class='striped rounded'> <thead><tr class='head'><th>Condition Name</th><th>Drug</th><th style='width: 17%;'> Details</th><th style='width: 17%;'></th><th>Drug </th></tr></thead><tbody>";

while($row = $result->fetch_array())
  {
	echo "<tr>";
     echo "<td>".$row["condition_name"]."</td>";
	 echo "<td style='font-weight: bold;'>".$row["drug"]."</td>";
	 echo  " <td><div class='small metro rounded btn danger'><a data-fancybox-type='iframe' href='view-sideeffects.php?cname=".$row["condition_name"] ."&sname=".$row["drug"]."' class='details red' > View Side Effects</a></div></td>";
     echo  " <td><div class='small metro rounded btn success'><a data-fancybox-type='iframe' href='drug-data.php?conceptName=".$row["drug"] ."' class='details green' > View Drug Details</a></div></td>";
	 echo "</tr>";
	
	
	
	
  }
}

	echo "</tbody></table>";

}

?>

<script>
$("#query").click(function(){
		
		$("#query").val("") ;
	});
</script>


