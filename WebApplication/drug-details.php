<?php
	$con=mysqli_connect("localhost","root","","thesis") ;
	
	// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
	
$condition_name = "";
$drug_name = "";

	if(isset($_GET["cname"]))
	{
		$condition_name = $_GET["cname"];
	}
	if(isset($_GET["drug"]))
	{
		$drug_name = $_GET["drug"];
	}
	
	$count = 0;
		
?>

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


<h2 style="text-align: center;">Details of <?php echo $drug_name; ?> and its side effects for <?php echo $condition_name; ?></h2>

 <table class='striped rounded'> <thead><tr class='head'>
<th>Drug</th>
<th>Side Effect</th>
<th>Percentage Effected</th>
</tr>
</thead>
<tbody>
<tr>

<?php


$result = mysqli_query($con,"select * from medical_new where condition_name ='".$condition_name."' and drug like '%".$drug_name."%' order by frequency,percentage_effected ");

//echo $result->num_rows;
while($row = $result->fetch_array())
  {
	echo "<tr>";
     echo "<td>".$row["drug"]."</td>";
	  echo "<td>".$row["sideeffect"]."</td>";
	   echo "<td>".$row["percentage_effected"]."</td>";
  //echo "<td>".$row["frequency"]."</td>";
	 echo "</tr>";
	$count++;
	
	if($count == 10)
{
break;}	
	
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

