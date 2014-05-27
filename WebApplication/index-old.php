<?php
	$con=mysqli_connect("localhost","root","","thesis") ;
	
	// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
	
	$result = mysqli_query($con,"select * from preferred_name");
	$count = 0;
	while($row = $result->fetch_array())
  {
 // echo $row[1] ;
  //echo "<br />";
  }
	
?>

<form method="POST">
<input type="text" name="query" value="Enter" />
<input type="submit" value="Search" />

</form>

<table>

<tr>
<td>Condition</td>
</tr>
<?php

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
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




$result = mysqli_query($con,"select distinct condition_name from medical_new where condition_name like '%".$search_text."%' order by frequency,percentage_effected desc");

echo $result->num_rows;
while($row = $result->fetch_array())
  {
	echo "<tr>";
     echo "<td><a href='condition-details.php?cname=".$row["condition_name"] ."'>".$row["condition_name"]."</a></td>";
  
	 echo "</tr>";
	$count++;
	
	if($count == 10)
{
break;}	
	
  }



}

?>

</table>

