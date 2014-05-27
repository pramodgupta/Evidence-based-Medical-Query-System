<?php

$conceptName = "";

if(isset($_GET["conceptName"]))
	$conceptName = $_GET["conceptName"];
	
	//echo strpos($conceptName," ");
if(strpos($conceptName," ") )
{
$conceptName = substr($conceptName,0,strpos($conceptName," "));
}
	

$URL = "http://rxnav.nlm.nih.gov/REST/Ndfrt/search?conceptName=".$conceptName."&kindName=DRUG_KIND";

//Call to get the concept name
$xml=simplexml_load_file($URL);

$conceptNui = $xml->groupConcepts->concept->conceptNui;


if($conceptNui != null && $conceptNui != "")
{
$URL1 = "http://rxnav.nlm.nih.gov/REST/Ndfrt/allInfo/".$conceptNui;

// Call to get all the details

$xml1 = simplexml_load_file($URL1);

//print_r($xml1);



?>

<h2> Available modes in the market</h2>

<?php

$count = 0;
echo "<ul>";
foreach($xml1->fullConcept->childConcepts->concept as $child)
{
	echo "<li>";
	echo $child->conceptName;
	echo "</li>";
	$count++;
}
echo "</ul>";

if($count==0)
{
	echo "<h3>No Results</h3>";
}

?>

<?php

$ci_with = 0;
$may_treat = 0;
$has_PE = 0;
$may_prevent = 0;
$effect_may_be_inhibited_by = 0;
$induces = 0;

foreach($xml1->fullConcept->groupRoles->role as $child)
{
	if($child->roleName == "CI_with")
	{
		$ci_with = 1;
	}
	else if($child->roleName == "may_treat")
	{
		$may_treat = 1;
	}
	else if($child->roleName == "has_PE")
	{
		$has_PE = 1;
	}
	else if($child->roleName == "may_prevent")
	{
		$may_prevent = 1;
	}
	else if($child->roleName == "effect_may_be_inhibited_by")
	{
		$effect_may_be_inhibited_by = 1;
	}
	else if($child->roleName == "induces")
	{
		$induces = 1;
	}
	
}


?>
<?php

if($ci_with == 1)
{
echo "<h2> This drug cannot be used with following Medical Conditions</h2>";



$count = 0;
echo "<ul>";
foreach($xml1->fullConcept->groupRoles->role as $child)
{
	if($child->roleName == "CI_with")
	{
		echo "<li>";
		echo $child->concept->conceptName;
		echo "</li>";
		$count++;
	}
}
echo "</ul>";

if($count==0)
{
	echo "<h3>No Medical Conditions</h3>";
}
}

?>

<?php

if($may_treat == 1)
{

echo "<h2> Other Diseases that can be treated with this drug</h2>";



$count = 0;
echo "<ul>";
foreach($xml1->fullConcept->groupRoles->role as $child)
{
	if($child->roleName == "may_treat")
	{
		echo "<li>";
		echo $child->concept->conceptName;
		echo "</li>";
		$count++;
	}
}
echo "</ul>";

if($count==0)
{
	echo "<h3>No Medical Conditions</h3>";
}
}

?>

<?php

if($has_PE == 1)
{

echo "<h2> Following Physiologic Effects can occur</h2>";



$count = 0;
echo "<ul>";
foreach($xml1->fullConcept->groupRoles->role as $child)
{
	if($child->roleName == "has_PE")
	{
		echo "<li>";
		echo $child->concept->conceptName;
		echo "</li>";
		$count++;
	}
}
echo "</ul>";

if($count==0)
{
	echo "<h3>No Medical Conditions</h3>";
}
}

?>


<?php

if($effect_may_be_inhibited_by == 1)
{

echo "<h2> Drug Effect can be inhibited by the following drugs</h2>";



$count = 0;
echo "<ul>";
foreach($xml1->fullConcept->groupRoles->role as $child)
{
	if($child->roleName == "effect_may_be_inhibited_by")
	{
		echo "<li>";
		echo $child->concept->conceptName;
		echo "</li>";
		$count++;
	}
}
echo "</ul>";

if($count==0)
{
	echo "<h3>No Drugs</h3>";
}
}

?>


<?php

if($may_prevent == 1)
{

echo "<h2> Drug can prevent the following Medical Conditions</h2>";



$count = 0;
echo "<ul>";
foreach($xml1->fullConcept->groupRoles->role as $child)
{
	if($child->roleName == "may_prevent")
	{
		echo "<li>";
		echo $child->concept->conceptName;
		echo "</li>";
		$count++;
	}
}
echo "</ul>";

if($count==0)
{
	echo "<h3>No Medical Conditions</h3>";
}
}

?>

<?php

if($induces == 1)
{

echo "<h2> Drug can induce the following Medical Conditions</h2>";



$count = 0;
echo "<ul>";
foreach($xml1->fullConcept->groupRoles->role as $child)
{
	if($child->roleName == "induces")
	{
		echo "<li>";
		echo $child->concept->conceptName;
		echo "</li>";
		$count++;
	}
}
echo "</ul>";

if($count==0)
{
	echo "<h3>No Medical Conditions</h3>";
}
}


}
?>


