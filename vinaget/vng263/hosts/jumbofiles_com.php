<?php
if (preg_match('#^http://(www\.)?jumbofiles\.com/#', $url)){
	$account = trim($this->get_account('jumbofiles.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	$password = "";
	if(strpos($url,"|")) {
		$linkpass = explode('|', $url); 
		$url = $linkpass[0]; $password = $linkpass[1];
	}
	if (isset($_POST['password'])) $password = $_POST['password'];
	if($password) $post['password'] = $password;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("jumbofiles.com");
			if(!$cookie){
				$data = $this->curl("http://jumbofiles.com/","","op=login&redirect=&login=$user&password=$pass&x=".rand(1,35)."&y=".rand(1,20));
				$cookie = $this->GetCookies($data);
				$this->save_cookies("jumbofiles.com",$cookie);
			}
			$data =  $this->curl($url,$cookie,"");
			$cookie = $cookie."; ".$this->GetCookies($data);
			$this->cookie = $cookie;
			if (stristr($data,'Error happened when generating')) die("<font color=red>Your link error happened when generating Download Link</font>");
			elseif(strpos($data,'<br><b>Password:</b>') && empty($password) == true) die($this->lang['reportpass']);
			elseif (stristr($data,'No such user exist ')) die(Tools_get::report($Original,"dead"));
			elseif (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
			elseif (preg_match('%input type="hidden" name="op" value="(.*)">%U', $data, $redir2)) {
				$post["op"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="id" value="(.*)">%U', $data, $redir2)) $post["id"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="rand" value="(.*)">%U', $data, $redir2)) $post["rand"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="referer" value="(.*)">%U', $data, $redir2)) $post["referer"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="method_free" value="(.*)">%U', $data, $redir2)) $post["method_free"] = $redir2[1];
				if (preg_match('%<input type="hidden" name="method_premium" value="(.*)">%U', $data, $redir2)) $post["method_premium"] = $redir2[1];
				$post["x"] = rand(1,45);
				$post["y"] = rand(1,10);
				$data =  $this->curl($url,$cookie,$post);
				if (preg_match('%"(http:\/\/.+jumbofiles\.com/files/.+)"%U',$data, $redir2)) $link = trim($redir2[1]);
			}
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("jumbofiles.com","");
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