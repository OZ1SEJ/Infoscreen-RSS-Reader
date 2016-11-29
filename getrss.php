<?php

// Include QR code generator
include('phpqrcode/qrlib.php'); 

//get the q and no parameters from URL
$q =$_GET["q"];
$no=$_GET["no"];

//find out which feed was selected
switch ($q)
{
	case "alt": $xml = ("https://www.altinget.dk/forskning/rss.aspx"); break;
	//case "dtu": $xml = ("http://www.dtu.dk/Forskning/Forskningsformidling/Artikler-og-nyheder-om-forskning?rss=1"); break;
	case "dtu": $xml = ("http://www.dtu.dk/Nyheder?rss=1"); break;
	case "ing": $xml = ("http://ing.dk/rss/term/353"); break;
	case "khu": $xml = ("http://nyheder.ku.dk/natur_tal_teknologi/?get_rss=1"); break;
	case "nat": $xml = ("http://feeds.nature.com/news/rss/news"); break;
	case "sci": $xml = ("http://rss.sciam.com/ScientificAmerican-Global?format=xml"); break;
	case "scn": $xml = ("http://sciencenordic.com/taxonomy/term/4/feed"); break;
	case "sct": $xml = ("http://www.sciencetalenter.dk/da/news.rss"); break;
	case "spa": $xml = ("http://spaceflightnow.com/feed/"); break;
	case "vid": $xml = ("http://videnskab.dk/rss/teknologi"); break;
	default:    $xml = ("http://www.sciencetalenter.dk/da/news.rss"); break;
}

// Construct XML Document

$xmlDoc = new DOMDocument();
$xmlDoc->load($xml);

//get elements from "<channel>"
$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
$channel_title = $channel -> getElementsByTagName('title')       -> item(0) -> childNodes -> item(0) -> nodeValue;
$channel_link  = $channel -> getElementsByTagName('link')        -> item(0) -> childNodes -> item(0) -> nodeValue;
$channel_desc  = $channel -> getElementsByTagName('description') -> item(0) -> childNodes -> item(0) -> nodeValue;

//output elements from "<channel>"
//echo("<h2>".$channel_title."</h2>");
//echo("<p>".$channel_desc."</p>");

//get and output "<item>" elements
$x=$xmlDoc->getElementsByTagName('item');

$i=$no;
//for ($i=0; $i<=20; $i++)
//{
$item_title = "";
while( $item_title == "" )
{
	$item_title = $x -> item($i) -> getElementsByTagName('title')       -> item(0) -> childNodes -> item(0) -> nodeValue;
	if( substr( $item_title,0,3 ) == "OTD" )
	{
		$item_title = "";
		$i++;
	}
}
	$item_link  = $x -> item($i) -> getElementsByTagName('link')        -> item(0) -> childNodes -> item(0) -> nodeValue;
	$item_desc  = $x -> item($i) -> getElementsByTagName('description') -> item(0) -> childNodes -> item(0) -> nodeValue;
	if( $item_desc == "" )
	{
		$item_content = $x -> item($i) -> getElementsByTagName('content');
		if( $item_content -> length > 0 )
		{
			$item_desc  = $x -> item($i) -> getElementsByTagName('content') -> item(0) -> childNodes -> item(0) -> nodeValue;
		}
	}
	
	$item_date  = "";
	$item_date_obj = $x -> item($i) -> getElementsByTagName('pubDate');
	if( $item_date_obj -> length > 0 )
	{
		$item_date  = $x -> item($i) -> getElementsByTagName('pubDate') -> item(0) -> childNodes -> item(0) -> nodeValue;
		$dateobj = date_create($item_date);
		//echo "#"-date('d m Y')."#";
	}
	/*
	if( $item_date=="" )
	{
		$item_date_obj = $x -> item($i) -> getElementsByTagName('dc:date');
		echo "#".$item_data_obj -> length."#";
		if( $item_date_obj -> length > 0 )
		{
			$item_date  = $x -> item($i) -> getElementsByTagName('dc:date') -> item(0) -> childNodes -> item(0) -> nodeValue;
		}
	}
	*/

	$item_desc = str_replace("-- Read more on ScientificAmerican.com", "", $item_desc);
	$item_desc = str_replace("<br />", " ", $item_desc);
	$item_desc = preg_replace('/<iframe.*?\/iframe>/i','', $item_desc);

	// how to save PNG codes to server 
	 
	$tempDir = "qr/";
	 
	$codeContents = $item_link; 
	 
	// we need to generate filename somehow,  
	// with md5 or with database ID used to obtains $codeContents... 
	$fileName = '005_file_'.md5($codeContents).'.png'; 
	 
	$pngAbsoluteFilePath = $tempDir.$fileName; 
	$urlRelativeFilePath = "qr/".$fileName; 
	 
	// generating 
	if (!file_exists($pngAbsoluteFilePath))
	{
		QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 4);
	}

	// displaying 
	echo('<img id="logo" class="rss" src="logi/' . $q . '.png" alt="" style="display:block;float:none;clear:both;margin:20px 0 0 0;"/>');
	echo('<img id="qr" class="rss" alt="" style="float:right;display:block;" src="' . $urlRelativeFilePath . '" />'); 
	echo('<h2 style="margin:20px 0 10px 0;">' . $item_title . '</h2>');
	if( $item_date != "" )
	{
		//echo('<h3>' . $item_date . '</h3>');
	}
	echo('<p>'  . $item_desc . '</p>');
//}
?>
