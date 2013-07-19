<?php
if(isset($url)){
	$head = get_headers($url, 1);	
	$url = trim($head['Location']);
}
$dlclass = "dl_uploaded_net";
$alias = true;
require_once("uploaded_net.php");

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uploaded Download Plugin 
* Downloader Class By [FZ]
*/
?>