<?php 

class dl_lumfile_com extends Download {
	
	private function Show_reCaptcha($pid, $inputs, $sname = 'Download File') {
		global $PHP_SELF;
		if (!is_array($inputs)) $this->error('Error parsing captcha data.', true, false);

		// Themes: 'red', 'white', 'blackglass', 'clean'
		echo "<script language='JavaScript'>var RecaptchaOptions = {theme:'white', lang:'en'};</script>\n\n<center><form name='recaptcha' action='$PHP_SELF' method='POST'><br />\n";
		foreach ($inputs as $name => $input) echo "<input type='hidden' name='$name' id='C_$name' value='$input' />\n";
		echo "<script type='text/javascript' src='http://www.google.com/recaptcha/api/challenge?k=$pid'></script><noscript><iframe src='http://www.google.com/recaptcha/api/noscript?k=$pid' height='300' width='500' frameborder='0'></iframe><br /><textarea name='recaptcha_challenge_field' rows='3' cols='40'></textarea><input type='hidden' name='recaptcha_response_field' value='manual_challenge' /></noscript><br /><input type='submit' name='submit' onclick='javascript:return checkc();' value='$sname' />\n<script type='text/javascript'>/*<![CDATA[*/\nfunction checkc(){\nvar capt=document.getElementById('recaptcha_response_field');\nif (capt.value == '') { window.alert('You didn\'t enter the image verification code.'); return false; }\nelse { return true; }\n}\n/*]]>*/</script>\n</form></center>\n</body>\n</html>";
		exit;
	}
	
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://lumfile.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<TR><TD align=right >Premium account expire:</TD>', '<TD><input type="button" value="Extend Premium Account"'), '<TD><b>', '</b></TD>'));
        elseif(stristr($data, '<a href="/?op=payments">Upgrade Now</a>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://lumfile.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://lumfile.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function FreeLeech($url) {	//Thanks to Th3-822@rapidleech.com
		if (empty($_POST['step']) || $_POST['step'] != '1') {
        $page = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($page));
		}
		if (empty($_POST['step']) || $_POST['step'] != '1') {
			$page2 = $this->lib->cut_str($page, 'Form method="POST" action=', '</form>'); //Cutting page
			$post = array();
			$post['op'] = $this->lib->cut_str($page2, 'name="op" value="', '"');
			if (stripos($post['op'], 'download') !== 0) $this->error('Error parsing download post data.', true, false);
			$post['usr_login'] = (empty($this->lib->cookie['xfss'])) ? '' : $this->lib->cookie['xfss'];
			$post['id'] = $this->lib->cut_str($page2, 'name="id" value="', '"');
			if (empty($post['id']))  $this->error('FileID form value not found. File isn\'t available?', true, false);
			$post['fname'] = $this->lib->cut_str($page2, 'name="fname" value="', '"');
			$post['referer'] = '';
			$post['method_free'] = $this->lib->cut_str($page2, 'name="method_free" value="', '"');

			$page = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($page,'premium membership is required to download this file')) $this->error('Premium account is required to download this file.', true, false); // F_______ PPS
			if (preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@', $page, $err)) 
			$this->error('Error: '.$err[0], true, false);

			$page2 = $this->lib->cut_str($page, '<form name="F1" method="POST"', '</form>'); //Cutting page
			if (!preg_match('@//(?:[^/]+\.)?(?:(?:google\.com/recaptcha/api)|(?:recaptcha\.net))/(?:(?:challenge)|(?:noscript))\?k=([\w|\-]+)@i', $page2, $pid)) $this->html_error('Error: reCAPTCHA not found.');
			if (preg_match('@<span id="countdown_str">[^<>]+<span[^>]*>(\d+)</span>[^<>]+</span>@i', $page2, $count) && $count[1] > 0) 
			sleep($count[1]);

			//$data = $this->DefaultParamArr($url, (empty($this->lib->cookie['xfss'])) ? 0 : encrypt(CookiesToStr($this->lib->cookie)));
			$data['T8[op]'] = $this->lib->cut_str($page2, 'name="op" value="', '"');
			if (stripos($data['T8[op]'], 'download') !== 0) $this->error('Error parsing download post data 2.', true, false);
			$data['T8[id]'] = $this->lib->cut_str($page2, 'name="id" value="', '"');
			$data['T8[rand]'] = $this->lib->cut_str($page2, 'name="rand" value="', '"');
			$data['T8[method_free]'] = urlencode(html_entity_decode($this->lib->cut_str($page2, 'name="method_free" value="', '"')));
			$data['step'] = '1';
			$this->Show_reCaptcha($pid[1], $data);
		} else {
			if (empty($_POST['recaptcha_response_field'])) $this->error('You didn\'t enter the image verification code.', true, false);
			$this->lib->cookie = (!empty($_POST['cookie'])) ? StrToCookies(decrypt(urldecode($_POST['cookie']))) : array();
			$this->lib->cookie['lang'] = 'english';

			$post = array('recaptcha_challenge_field' => $_POST['recaptcha_challenge_field'], 'recaptcha_response_field' => $_POST['recaptcha_response_field']);
			$post['op'] = $_POST['T8']['op'];
			$post['id'] = $_POST['T8']['id'];
			$post['rand'] = $_POST['T8']['rand'];
			$post['referer'] = '';
			$post['method_free'] = $_POST['T8']['method_free'];
			$post['down_script'] = 1;

			$page = $this->lib->curl($url, $this->lib->cookie, $post);

			if(stristr($page,'>Skipped countdown')) $this->error('Error: Skipped countdown?.', true, false);
			if(stristr($page,'>Wrong captcha<')) $this->error('Error: Wrong Captcha Entered.', true, false);
			if (preg_match('@You can download files up to \d+ [KMG]b only.@i', $page, $err)) 
			$this->error('Error: '.$err[0], true, false);

			if (!preg_match('@https?://[^/\r\n]+/(?:(?:files)|(?:dl?))/[^\'\"\s\t<>\r\n]+@i', $page, $dlink)) 
			$this->error('Error: Download link not found.', true, false, 2);
			else
			return trim($dlink[0]);
		}
		return false;	
	}	
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password')) $this->error("wrongpass", true, false, 2);
			elseif(!preg_match('@https?:\/\/\w+\.lumfile\.(.*)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2);	
			else 	
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner') || stristr($data,'File not found')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@https?:\/\/\w+\.lumfile\.(.*)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2);	
			else 	
			return trim($giay[0]);
		}
		else  
		return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Lumfile Download Plugin, updated by giaythuytinh176 [3.8.2013]
* Downloader Class By [FZ]
*/
?>