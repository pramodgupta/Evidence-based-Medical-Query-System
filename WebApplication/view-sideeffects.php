<?php
$con=mysqli_connect("localhost","root","","thesis") ;

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $condition = "";
  $drug = "";
  
  if(isset($_GET["cname"]))
  $condition = $_GET["cname"];
  
   if(isset($_GET["sname"]))
  $drug = $_GET["sname"];
  
  $sql = "select * from medical_new where condition_name='".$condition."' and drug='".$drug."' and sideeffect <> '' order by percentage_effected";
  
  $result = mysqli_query($con,$sql);
  
  $row_count = $result->num_rows;
  
  if($row_count == 0)
  {
	echo "<center><h2>No Side Effects</h2></center>";
  }
  else
  {
	echo  "<center><h2>Possible side effects</h2></center>";
  }
  
  echo "<ul>";
  while($row = $result->fetch_array())
  {
  echo "<li>";
  
	echo $row["sideeffect"]."<br>";
	
	echo "</li>";
  }
  
    echo "</ul>";
?>