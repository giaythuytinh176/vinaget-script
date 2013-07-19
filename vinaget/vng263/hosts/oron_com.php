<?php
if (preg_match('#^http://([a-z0-9]+)\.oron\.com/#', $url) || preg_match('#^http://oron\.com/#', $url)){
	$maxacc = count($this->acc['oron.com']['accounts']);
	if($maxacc > 0){
		for ($k=0; $k < $maxacc; $k++){
			for ($j=0; $j < 2; $j++){
				if(!$cookie) $cookie = $this->get_cookie("oron.com");
				if(!$cookie){
					$account = trim($this->acc['oron.com']['accounts'][$k]);
					if (stristr($account,':')) list($user, $pass) = explode(':',$account);
					$data = $this->curl("http://oron.com/login","lang=english","login=$user&password=$pass&op=login");
					$cookie = $this->GetCookies($data);
					$this->save_cookies("oron.com",$cookie);
				}
				$this->cookie = $cookie;
				$data = $this->curl($url,$cookie,"");
				if(stristr($data, "have reached the download limit")){
					if($k <$maxacc-1) {
						$cookie = '';
						$this->save_cookies("oron.com","");
						continue;
					}
					else die("<font color=red>Account out of bandwidth</font>");
				}
				elseif (stristr($data,'<h2>File Not Found</h2>')) die(Tools_get::report($Original,"dead"));
				elseif (stristr($data,'Create Download Link')) {
					$post["op"] = "download2";
					$post["method_premium"] = "1";
					$post["down_direct"] = "1";
					if (preg_match('%<input type="hidden" name="id" value="(.*)">%U', $data, $redir2)) $post["id"] = $redir2[1];
					if (preg_match('%<input type="hidden" name="rand" value="(.*)">%U', $data, $redir2)) $post["rand"] = $redir2[1];
					$data = $this->curl($url,$cookie,$post);
					$data = $this->cut_str($data, '<td align="center" height="100">',"Download File");
					if (preg_match('%href="(.*)"%U', $data, $redir2)) {
						$link = trim($redir2[1]);
						$link = str_replace(substr(strrchr($link, '/'), 1),urlencode(substr(strrchr($link, '/'), 1)),$link);
						$size_name = Tools_get::size_name($link, $this->cookie);
						if($size_name[0] > 200 ){
							$filesize =  $size_name[0];
							$filename = $size_name[1];
							break;
						}
						else $link = '';
					}
				}
				if(!$link) {
					$cookie = "";
					$this->save_cookies("oron.com","");
				}
			}
			if($link) break;
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