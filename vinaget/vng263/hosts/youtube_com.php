<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?youtube\.com/#', $url)){
	$url = urldecode($url);
	$url = str_replace('www.', '', $url);
	$url = str_replace('http://', 'http://www.', $url);
	$data = $this->curl($url,"","");
	if(strstr($data,'verify-age-thumb')) $report = Tools_get::report($url,"Adult");
	elseif(strstr($data,'das_captcha')) $report = Tools_get::report($url,"youtube_captcha");
	elseif(!preg_match('/stream_map=(.[^&]*?)&/i',$data,$match)) $report = Tools_get::report($url,"ErrorLocating");
	elseif(preg_match('/stream_map=(.[^&]*?)&/i',$data,$match)) {
		$this->max_size = $this->max_size_other_host;
		$fmt_url =  urldecode($match[1]);
		if(preg_match('/^(.*?)\\\\u0026/',$fmt_url,$match)) $fmt_url = $match[1];
		$urls = explode(',',$fmt_url);
		$foundArray = array();
		$signature = array();

		foreach($urls as $urldl){
				if(preg_match('/itag=([0-9]*)&url=(.*?)&/si',$urldl,$um)){
						$u = urldecode($um[2]);
						$foundArray[$um[1]] = trim($u);
						preg_match('/sig=(.*?)&/si',$urldl,$sig);
						$signature[$um[1]] = $sig[1];
				}
		}

		if(preg_match('<meta name="title" content="(.*?)">', $data, $matches)) $title = $matches[1];
		else $title = "unknown";

		$sig = "";
		if (isset($foundArray[36])) {
			$URL=$foundArray[36];
			$sig=$signature[36];
			$URL .= "&signature=".$sig;
			$this->youtube = $title . ".3gp";
			echo '<div id="link">'.$this->get($URL).'</div>';
		}
		if (isset($foundArray[35])) {
			$URL=$foundArray[35];
			$sig=$signature[35];
			$URL .= "&signature=".$sig;
			$this->youtube = $title . ".flv";
			echo '<div id="link">'.$this->get($URL).'</div>';
		}
		if (isset($foundArray[18])) {
			$URL=$foundArray[18];
			$sig=$signature[18];
			$URL .= "&signature=".$sig;
			$this->youtube= $title . ".mp4";
			echo '<div id="link">'.$this->get($URL).'</div>';
		}
		if (isset($foundArray[22])) {
			$URL=$foundArray[22];
			$sig=$signature[22];
			$URL .= "&signature=".$sig;
			$this->youtube = $title . ".mp4";
			echo '<div id="link">'.$this->get($URL).'</div>';
		}
		if (isset($foundArray[37])) {
			$URL=$foundArray[37];
			$sig=$signature[37];
			$URL .= "&signature=".$sig;
			$this->youtube = $title . ".mp4";
			echo '<div id="link">'.$this->get($URL).'</div>';
		}
	}
	exit;
}
elseif(isset($this->youtube)) {
	$URL = $url;
	$size_name = Tools_get::size_name(trim($URL), "");
	if($size_name[0] > 200 ){
		$filename = $this->youtube;
		$filename = str_replace("/","",$filename);
		$filesize = $size_name[0];
		$link = $URL;
	}
	else exit;
}
/*
* Home page: http://vinaget.us
* Blog: http://blog.vinaget.us
* Script Name: Vinaget
* Version: 2.6.3
* Created: afterburnerleech.com (7 Sep 2011)
* Updated:
                - By H (Wednesday, November 16, 2011)
                - By H (Thursday, February 02, 2012)
				- By _rchaves_ (Saturday, September 29, 2012)
                - By H (Sunday, September 30, 2012)

*/
?>