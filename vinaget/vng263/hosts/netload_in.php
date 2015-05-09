<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?netload\.in/#', $url)){
	$account = trim($this->get_account('netload.in'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		//==== Fix link NL ====
		$data = $this->curl('http://api.netload.in/index.php?id=2',"",'links='.urlencode($url).'&send=Absenden');
		$data = $this->cut_str($data, '<h3', '</body>');
		$data = $this->cut_str($data, 'name="links">', '</textarea>');
		if (stristr($data,'online')) {
			$gach = explode(';', $data);
			$url = 'http://www.netload.in/datei' . $gach[0].'.htm';
		}
		else die(Tools_get::report($Original,"dead"));
		//==== Fix link NL ====
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("netload.in");
			if(!$cookie){
				$post["txtuser"]= $user;
				$post["txtpass"]= $pass;
				$post["txtcheck"] = "login";
				$post["txtlogin"] = "";
				$page=$this->curl("http://netload.in/","",$post);
				$cookie = $this->GetCookies($page); 
				$this->save_cookies("netload.in",$cookie);
			}
			$this->cookie = $cookie;
			$page=$this->curl($url,$cookie,"");
			if(preg_match('/ocation: *(.+)/i', $page, $redir)){
				if(preg_match('/^http:/', $redir[1])) $link = trim($redir[1]);
				else {
					$url = 'http://netload.in'.trim($redir[1]);
					$page=$this->curl($url,$cookie,"");
					if(preg_match('/<a class="Orange_Link" href="(.*)" >Click here for the download/Ui', $page, $redir)) $link = trim($redir[1]);
					elseif (preg_match('%ocation: (.+)\r\n%U', $page, $flink)) $link = trim($flink[1]);
				}					
			}
			if (stristr($page,"The file was deleted")) die(Tools_get::report($Original,"dead"));
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("netload.in","");
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