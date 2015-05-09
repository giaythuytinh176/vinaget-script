<?php
if (preg_match('#^http://(www\.)?fp.io/#', $url)){
	$data = $this->curl($url,"","");
	if(preg_match('/ocation: *(.*)/i', $data, $redir)){
		$url = trim($redir[1]);
		include("filepost_com.php");
	}
}
?>