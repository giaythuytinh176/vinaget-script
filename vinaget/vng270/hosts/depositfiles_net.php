<?php
if(isset($url)){
	$head = get_headers($url, 1);	
	$url = trim($head['Location']);
}
$dlclass = "dl_depositfiles_com";
$site = "depositfiles.com";
$alias = true;
require_once("depositfiles_com.php");

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Dfiles Download Plugin 
* Downloader Class By [FZ]
*/
?>