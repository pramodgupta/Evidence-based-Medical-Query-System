<?php

class pubMedApi
{

public function getIdString($InputConcept)
{
	
		$InputConcept = str_replace(" ","+",$InputConcept);
			
		// URL to pull pubmed id list with the input concept details
		$URL="http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=".$InputConcept;
			
		//Call to get the concept name
		$xml=simplexml_load_file($URL);

		$IdList = $xml->IdList;

		$ids = array();

		//Loading all the ids into the array
		foreach($IdList->Id as $id)
			{
				$ids[] = $id;
			}

		$resultlist = implode(',',$ids);

		return $resultlist;
	
	
}

public function getXMLFromIds($listIds)
{
		// URL to pull pubmed id list with the input concept details
		$URL="http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&id=".$listIds;
			
		//Call to get the concept name
		$xml=simplexml_load_file($URL);
		
		return $xml;
}

public function getIdDetails($DocSum)
{

	$idObject = new idDetails();
	
	$LinkId = $DocSum->Id;
	
	$idObject->pubmedLink = "http://www.ncbi.nlm.nih.gov/pubmed/".$LinkId;
	
	$idObject->pubmedId = $LinkId;
	
	foreach($DocSum->Item as $item)
	{
		
		if($item['Name'] == "PubDate")
		{
			$idObject->publicationDate = $item;
		}
		
		if($item['Name'] == "AuthorList")
		{
			$idObject->author = $item->Item;
		}
		if($item['Name'] == "Title")
		{
			$idObject->title = $item;
		}
			
	}
	
	return $idObject;
	
	
}

	

}

class idDetails
	{
		public $pubmedId;
		public $pubmedLink;
		public $title;
		public $author;
		public $publicationDate;
	}





?>