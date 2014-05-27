<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
     
    <link rel="stylesheet" href="../styles/gumby.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <script src="../js/libs/modernizr-2.6.2.min.js"></script>
    <script src="../js/jquery-2.0.3.min.js" language="javascript"></script>
	
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

<link rel="stylesheet" href="../fancybox/source/jquery.fancybox.css?v=2.1.3" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/source/jquery.fancybox.pack.js?v=2.1.3"></script>

<link rel="stylesheet" href="../fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="../fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

<link rel="stylesheet" href="../fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>


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
<?php include('api.php');?>

<div id="pageTitle" class="text-center">
Evidence based Medical Query System
</div>
   
   <div id="innerwrap" class="row">
   

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
		<input type="text" name="query" id="query" value="Condition search term" />
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

   <li class="field" style="width: 60%; float:left;">
		<input type="text" name="query" id="query" value="Drug search term" />
      </li>
	   <li class="field"  style=" float:right;">
	   <div class="picker">
<select name="search_type">
	<option value="drug">Drug</option>
	<option value="condition_name">Medical Condition</option>
	<option value="sideeffect">Side Effect</option>
</select>
</div>
</li>

   <li class="field" style="width: 60%; float:left;">
		<input type="text" name="query" id="query" value="SideEffect search term" />
      </li>
	   <li class="field"  style=" float:right;">
	   <div class="picker">
<select name="search_type">
	<option value="sideeffect">Side Effect</option>
	<option value="condition_name">Medical Condition</option>
	<option value="drug">Drug</option>
	
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

$query = "select distinct condition_name, drug from medical_new where ".$search_type." like '%".$searchtext."%' order by frequency desc, percentage_effected asc";
$result = mysqli_query($con, $query);

$row_count =  $result->num_rows;

$count = 0;
$drug = "";

if($row_count != 0)
{



$outputString = "";
		
		// Get the first row from the result set
		while($row = $result->fetch_array())
		  {
				if($count == 0)
				{
					$outputString = $row['condition_name']."+AND+".$row['drug'];
					$drug = $row['drug'];
					$condition = $row['condition_name'];
				}
				else
				{
				break;
				}
			$count++;
			
		  }

echo "<div class='details'>";
		  
echo "<table class='striped rounded'> <thead><tr class='head'><th>S.no</th><th>Publication Title</th><th> Author</th><th style='width: 16%;'>Date Published</th><th style='width: 17%;'>Publication Link</th></tr></thead><tbody>"; 
		  
		$pubMedApi = new pubMedApi();
		
		$idList = $pubMedApi->getIdString($outputString);
	
		$xml = $pubMedApi->getXMLFromIds($idList);
		
		foreach($xml->DocSum as $childDoc)
		{
		
			$idDetails = $pubMedApi->getIdDetails($childDoc);
			
			echo "<tr>";
			
			echo "<td>".$count."</td>";
			echo "<td>".$idDetails->title."</td>";
			echo "<td>".$idDetails->author."</td>";
			echo "<td>".$idDetails->publicationDate."</td>";
			echo " <td><div class='small metro rounded btn danger'><a target='_blank' href='".$idDetails->pubmedLink."' class='details red' > Publication</a></div></td>";
			//echo  " <td><div class='small metro rounded btn success'><a data-fancybox-type='iframe' href='../drug-data.php?conceptName=".$drug."' class='details green' > Drug Details</a></div></td>";
			
			echo "</tr>";
			$count++;
		}
		
		echo "</tbody></table>";
		  
    
  }
  

	

}

?>

<script>
$("#query").click(function(){
		
		$("#query").val("") ;
	});
</script>


