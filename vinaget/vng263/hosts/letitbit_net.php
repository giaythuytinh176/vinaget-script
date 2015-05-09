<?php
if (preg_match('#^http://www.letitbit.net/#', $url) || preg_match('#^http://letitbit.net/#', $url)){
	$account = trim($this->get_account('letitbit.net'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("letitbit.net");
			if(!$cookie) {
				$data = $this->curl("http://letitbit.net/","lang=en","act=login&login=".urlencode($user)."&password=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("letitbit.net",$cookie);
			}
			if (stristr($cookie,'prekey')) {
				$cookie = preg_replace("/(;|prekey=)/","",$cookie);
				$data = $this->curl($url,"lang=en","");
				if (preg_match('%<input type="hidden" name="uid5" value="(.*)"%U', $data, $value)) $post["uid5"] = $value[1];
				if (preg_match('%<input type="hidden" name="uid" value="(.*)"%U', $data, $value)) $post["uid"] = $value[1];
				if (preg_match('%<input type="hidden" name="id" value="(.*)"%U', $data, $value)) $post["id"] = $value[1];
				if (preg_match('%<input type="hidden" name="live" value="(.*)"%U', $data, $value)) $post["live"] = $value[1];
				if (preg_match('%<input type="hidden" name="seo_name" value="(.*)"%U', $data, $value)) $post["seo_name"] = $value[1];
				if (preg_match('%<input type="hidden" name="name" value="(.*)"%U', $data, $value)) $post["name"] = $value[1];
				if (preg_match('%<input type="hidden" name="pin" value="(.*)"%U', $data, $value)) $post["pin"] = $value[1];
				if (preg_match('%<input type="hidden" name="realuid" value="(.*)"%U', $data, $value)) $post["realuid"] = $value[1];
				if (preg_match('%<input type="hidden" name="realname" value="(.*)"%U', $data, $value)) $post["realname"] = $value[1];
				if (preg_match('%<input type="hidden" name="host" value="(.*)"%U', $data, $value)) $post["host"] = $value[1];
				if (preg_match('%<input type="hidden" name="ssserver" value="(.*)"%U', $data, $value)) $post["ssserver"] = $value[1];
				if (preg_match('%<input type="hidden" name="sssize" value="(.*)"%U', $data, $value)) $post["sssize"] = $value[1];
				if (preg_match('%<input type="hidden" name="file_id" value="(.*)"%U', $data, $value)) $post["file_id"] = $value[1];
				if (preg_match('%<input type="hidden" name="index" value="(.*)"%U', $data, $value)) $post["index"] = $value[1];
				if (preg_match('%<input type="hidden" name="dir" value="(.*)"%U', $data, $value)) $post["dir"] = $value[1];
				if (preg_match('%<input type="hidden" name="optiondir" value="(.*)"%U', $data, $value)) $post["optiondir"] = $value[1];
				if (preg_match('%<input type="hidden" name="desc" value="(.*)"%U', $data, $value)) $post["desc"] = $value[1];
				if (preg_match('%<input type="hidden" name="lsarrserverra" value="(.*)"%U', $data, $value)) $post["lsarrserverra"] = $value[1];
				if (preg_match('%<input type="hidden" name="page" value="(.*)"%U', $data, $value)) $post["page"] = $value[1];
				if (preg_match('%<input type="hidden" name="is_skymonk" value="(.*)"%U', $data, $value)) $post["is_skymonk"] = $value[1];
				if (preg_match('%<input type="hidden" name="md5crypt" value="(.*)"%U', $data, $value)) $post["md5crypt"] = $value[1];
				if (preg_match('%<input type="hidden" name="realuid_free" value="(.*)"%U', $data, $value)) $post["realuid_free"] = $value[1];
				$post["pass"] = $cookie;
				$post["submit_sms_ways_have_pass"] = "Download+file";
				$data = $this->curl("http://letitbit.net/sms/check2.php","",$post);
				$cookies = $this->GetCookies($data);
				$this->cookie = $cookies;
				if (stristr($data,'direct_link_2')) {
					$data2 = trim ($this->cut_str ($data, "direct_link_1", "direct_link_2" ));
					if (preg_match('%(http:\/\/.+)" :%U', $data2, $value)) $link2 = trim($value[1]);
				}
				$data1 = trim ($this->cut_str ($data, "var direct_links", "direct_link_1" ));
				if (preg_match('%(http:\/\/.+)" :%U', $data1, $value)) {
					$link = trim($value[1]);
					$size_name = Tools_get::size_name($link, $this->cookie);
					if($size_name[0] > 200) {
						$filesize = $size_name[0];
						$filename = $size_name[1];
					}
					elseif(isset($link2)) {
						$link = $link2;
						$size_name = Tools_get::size_name($link, $this->cookie);
						$filesize = $size_name[0];
						$filename = $size_name[1];
					}
					break;
				}
				elseif (stristr($data,'The file is temporarily unavailable for download')) die(Tools_get::report($Original,"dead"));
				else {
					$cookie = "";
				}
			}
			else {
				$data = $this->curl($url,$cookie,"");
				if (stristr($data,'Registration</a></li>')) {
					$cookie = "";
					$this->save_cookies("letitbit.net","");
					continue;
				}
				$this->cookie = $cookie.$this->GetCookies($data);
				if(preg_match ( '/ocation: (\/download.+)/', $data, $linkpre)) $check2 = trim($linkpre[1]);
				else $check2 = "http://letitbit.net/sms/check2.php";
				$data = $this->curl($check2,$this->cookie,"");

				if (stristr($data,'direct_link_2')) {
					$data2 = trim ($this->cut_str ($data, "direct_link_1", "direct_link_2" ));
					if (preg_match('%(http:\/\/.+)" :%U', $data2, $value)) $link2 = trim($value[1]);
				}
				$data1 = trim ($this->cut_str ($data, "var direct_links", "direct_link_1" ));
				if (preg_match('%(http:\/\/.+)" :%U', $data1, $value)) {
					$link = trim($value[1]);
					$size_name = Tools_get::size_name($link, $this->cookie);
					if($size_name[0] > 200) {
						$filesize = $size_name[0];
						$filename = $size_name[1];
					}
					elseif(isset($link2)) {
						$link = $link2;
						$size_name = Tools_get::size_name($link, $this->cookie);
						$filesize = $size_name[0];
						$filename = $size_name[1];
					}
					break;
				}
				elseif (stristr($data,'The file is temporarily unavailable for download')) die(Tools_get::report($Original,"dead"));
				else {
					$cookie = "";
					$this->save_cookies("letitbit.net","");
				}
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