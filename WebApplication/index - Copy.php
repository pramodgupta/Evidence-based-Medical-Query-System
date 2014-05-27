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
		width: 90%;
		}
		select, input[type="text"], input[type="file"] {
width: 498px;
}
	</style>
</head>
<body>
   
   <div id="innerwrap" class="row">
<form method="POST">

<h2 style="text-align: center;"> Search Conditions</h2>
<br>	
 <ul>
      <li class="field" style="width: 68%;float: left;">
		<input type="text" name="query" value="Enter the condition name over here" />
      </li>
	  
	   <div class="row">
                     
                     <div class="pull_right">&nbsp; &nbsp;</div>
                     <div class="medium primary btn "><input type="submit" value="Search" /></div>
                  </div>
    </ul>

</form>

<?php
	$con=mysqli_connect("localhost","root","","thesis") ;
	
	// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
	
	$result = mysqli_query($con,"select * from preferred_name");
	$count = 0;
	
	
?>


<?php

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
?>

 <table class='striped rounded'> <thead><tr class='head'>


<th>Condition Name</th>
</tr>
</thead>
<tbody>



<?php
$search_text = $_POST['query'];


/*
$result1 = mysqli_query($con,"select * preferred_name where preferred_name like '%".$search_text."%'");

$row_counter = $result1->num_rows;

if($row_counter == 0)
{
		
}
else if ($row_counter == 1)
{
	while($row1 = $result1->fetch_array())
	{
			//$search_text = $row1["clinical_name"];
	}
}
else
{
	while($row1 = $result1->fetch_array())
	{
			
	}
}
*/




$result = mysqli_query($con,"select distinct condition_name from medical_new where condition_name like '%".$search_text."%' order by frequency desc,percentage_effected desc");

// echo $result->num_rows;
while($row = $result->fetch_array())
  {
	echo "<tr>";
     echo "<td><a href='condition-details.php?cname=".$row["condition_name"] ."'>".$row["condition_name"]."</a></td>";
	
	 echo "</tr>";
	$count++;
	
	if($count == 50)
{
break;}	
	
  }



}

?>
</tbody>
</table>


    </div>

    <script src="js/libs/gumby.min.js"></script>
<script src="js/main.js"></script>
<script src="js/libs/gumby.js"></script>
<script src="js/libs/ui/gumby.retina.js"></script>
<script src="js/libs/ui/gumby.fixed.js"></script>
<script src="js/libs/ui/gumby.skiplink.js"></script>
<script src="js/libs/ui/gumby.toggleswitch.js"></script>
<script src="js/libs/ui/gumby.checkbox.js"></script>
<script src="js/libs/ui/gumby.radiobtn.js"></script>
<script src="js/libs/ui/gumby.tabs.js"></script>
<script src="js/libs/ui/gumby.navbar.js"></script>
<script src="js/libs/ui/gumby.fittext.js"></script>
<script src="js/libs/ui/jquery.validation.js"></script>
<script src="js/libs/gumby.init.js"></script>
<script src="js/plugins.js"></script>
</body>
</html>











