<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?easy-share\.com/#', $url)){
	$data = $this->curl($url,"","");
	if(preg_match('/ocation: *(.*)/i', $data, $redir)){
		$url = trim($redir[1]);
		include("crocko_com.php");
	}
}


/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::..
*/
?>