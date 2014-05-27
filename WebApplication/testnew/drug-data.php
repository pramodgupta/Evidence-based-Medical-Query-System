<?php



	$con=mysqli_connect("localhost","root","","thesis") ;
	
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
	
	$searchtext = "";
	$searchtype = "";
	
		

$query = "select * from medical_new";
$result = mysqli_query($con, $query);

while($row = $result->fetch_array())
{
			$frequency = 0;
			$percentage = 0;
			$id = 0;
			
			$frequency = $row["frequency"];
			$percentage = $row["percentage_effected"];
			$id = $row['id'];
		
echo $frequency."--".$percentage." -- ";		
			if($percentage == "0" || $percentage == "0.0" || $percentage == " ")
			{
				$percentage = "1";
			}
			
			$strength = $frequency/pow($percentage,2);
			
		$query1= "update medical_new set strength=".$strength." where id=".$id;
		$result1 = mysqli_query($con, $query1);
		
 }
		  

?>