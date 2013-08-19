<?php

class dl_youtube_com extends Download {
	
	public function FreeLeech($url){
	
		function FormToArr($content, $v1 = '&', $v2 = '=') {
		  $rply = array();
		  if (strpos($content, $v1) === false || strpos($content, $v2) === false) return $rply;
		  foreach (array_filter(array_map('trim', explode($v1, $content))) as $v) {
		   $v = array_map('trim', explode($v2, $v, 2));
		   if ($v[0] != '') $rply[$v[0]] = $v[1];
		  }
		  return $rply;
		}
		
		function GetVideosArr($fmtmaps) {
		  $fmturls = array();
		  foreach ($fmtmaps as $fmtlist) {
		   $fmtlist = array_map('urldecode',FormToArr($fmtlist));
		   $fmturls[$fmtlist['itag']] = $fmtlist['url'];
		   if (stripos($fmtlist['url'], '&signature=') === false) $fmturls[$fmtlist['itag']] .= '&signature='.$fmtlist['sig'];
		  }
		  return $fmturls;
		}
		
		function getSizeFile($url) {
				if (substr($url,0,4)=='http') {
				$x = array_change_key_case(get_headers($url, 1),CASE_LOWER);
				if ( strcasecmp($x[0], 'HTTP/1.1 200 OK') != 0 ) { $x = $x['content-length'][1]; }
				else { $x = $x['content-length']; }
				}
				else { $x = @filesize($url); }
				return $x;
		}
	
		$url = urldecode($url);
		$url = str_replace('www.', '', $url);
		$url = str_replace('http://', 'http://www.', $url);
		$parse = parse_url($url);
		$video_id =  $parse['query'];
		$video_id = explode("v=",$video_id);
		$video_id = $video_id[1];
		$data = $this->lib->curl('http://www.youtube.com/get_video_info?video_id='.$video_id.'&asv=3&el=detailpage&hl=en_US',"","");
		$response = array_map('urldecode',FormToArr(substr($data, strpos($data, "\r\n\r\n") + 4)));;

		if (!empty($response['reason']))  Tools_get::report($response['errorcode'],$response['reason']);
		if (isset($_REQUEST['step']) || preg_match('@Location: https?://(www\.)?youtube\.com/das_captcha@i', $data)) $this->error("ytb_captcha", true, false);	
		if (empty($response['url_encoded_fmt_stream_map']))  $this->error("Video links not found", true, false);	
		$fmt_url_maps = explode(',', $response['url_encoded_fmt_stream_map']);
		$this->fmts = array(38,37,22,45,35,44,34,43,18,5,17);
		$yt_fmt = empty($_REQUEST['yt_fmt']) ? '' : $_REQUEST['yt_fmt'];
		$this->fmturlmaps = GetVideosArr($fmt_url_maps);

		if (isset($_REQUEST['ytube_mp4']) && $_REQUEST['ytube_mp4'] == 'on' && !empty($yt_fmt)) {
		   //look for and download the highest quality we can find?
		   if ($yt_fmt == 'highest') {
				foreach ($this->fmts as $fmt) if (array_key_exists($fmt, $this->fmturlmaps)) {
				$furl = $this->fmturlmaps[$fmt];
				break;
				}
		   } elseif (!$furl = $this->fmturlmaps[$yt_fmt])  $this->error("Specified video format not found", true, false);	
		   else $fmt = $yt_fmt;
		  } else { //just get the one Youtube plays by default (in some cases it could also be the highest quality format)
		   $fmt = key($this->fmturlmaps);
		   $furl = $this->fmturlmaps[$fmt];
		  }
		  $ext = '.flv';
		  $fmtexts = array('.3gp' => array(17), '.mp4' => array(18,22,37,38), '.webm' => array(43,44,45));
		  foreach ($fmtexts as $k => $v) {
		   if (!is_array($v)) $v = array($v); 
		   if (in_array($fmt, $v)) {
				$ext = $k;
				break;
		   }
		  }
		  if (empty($response['title'])) $this->error("No video title found! Download halted.", true, false);
		  $filename = str_replace(str_split('\\/:*?"<>|'), '_', html_entity_decode(trim($response['title']), ENT_QUOTES)) . "-[YT-f$fmt][{$video_id}]$ext";
		  $link = $furl;
		  //$size_name = Tools_get::size_name($link, '');
		  //$filesize = $size_name[0];
		  $filesize = getSizeFile($link);
				return trim($link);
					return false;
	}
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Youtube.com Download Plugin by giaythuytinh176 [3.8.2013]
* Downloader Class By [FZ]
* Need update, correct filename :3. Update from youtube plugin 263
*/
?>