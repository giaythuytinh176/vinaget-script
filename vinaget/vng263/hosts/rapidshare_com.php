<?php
if (preg_match('#^http://([a-z0-9]+\.)?rapidshare\.(com)/#', $url) || preg_match('#^https://([a-z0-9]+\.)?rapidshare\.(com)/#', $url)){
	$account = trim($this->get_account('rapidshare.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		//==== Fix link RS ====
		if (stristr($url, 'rapidshare') == true && stristr($url, 'download|') == true){
			$url=str_replace("%21","!",$url);
			$url=str_replace("%7C","|",$url);
			$arrRSL = explode('|', $url);
			if (isset($arrRSL)){
				$idfiles = $arrRSL[2];
				$idnames = $arrRSL[3];
				$url = 'http://rapidshare.com/files/' . $arrRSL[2] . '/' . $arrRSL[3];
			}
		}
		else{		
			$arrRSL = explode('/', $url);
			if (isset($arrRSL)){
				$url = 'http://rapidshare.com/files/' . $arrRSL[4] . '/' . $arrRSL[5];
				$idfiles = $arrRSL[4];
				$idnames = $arrRSL[5];
			}
		}
		//==== Fix link RS ====
		$data = file_get_contents("http://api.rapidshare.com/cgi-bin/rsapi.cgi?sub=checkfiles&files=".$idfiles."&filenames=".$idnames);
		$infolink = explode(',', $data);
		if ($infolink[4]!= 1) die(Tools_get::report($Original,"dead"));
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("rapidshare.com");
			if(!$cookie) {
				$data =  $this->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi","","sub=getaccountdetails&withcookie=1&withpublicid=1&login=".$user."&cbf=RSAPIDispatcher&cbid=2&password=".$pass);
				if(strpos($data,"Login failed")) die($this->lang['erroracc']);
				$cookie  =  $this->cut_str($data, "ncookie=","\\n");
				$this->save_cookies("rapidshare.com",$cookie);
			}
			$cookie = preg_replace("/(enc=|ENC=|Enc=)/","",$cookie);
			//$cookie = "enc=".$cookie;
			$this->cookie = $cookie;
			$data = $this->curl("https://api.rapidshare.com/cgi-bin/rsapi.cgi?sub=download&cookie=$cookie&fileid=$idfiles&filename=$idnames",'',"",0);

			if(preg_match('/DL\:(.+rapidshare.com),/i', $data, $linkpre)) {
				$svrs = trim($linkpre[1]);
				$link = "https://$svrs/cgi-bin/rsapi.cgi?sub=download&cookie=$cookie&fileid=$idfiles&filename=$idnames";
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("rapidshare.com","");
			}
		}
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