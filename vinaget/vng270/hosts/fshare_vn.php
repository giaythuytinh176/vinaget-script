<?php

class dl_fshare_vn extends Download {

	public function PreLeech($url){
		$url = str_replace("mega.1280.com", "fshare.vn", $url);
		if(stristr($url, "/folder/")) {
			$data = $this->lib->curl("http://www.fshare.vn/check_link.php?action=check_link&arrlinks=".urlencode($url), "", "", 0);
			$page = json_decode($data, true);
			echo $page['chk_link_record'];
			exit;
		}
	}	
/*
	public function PreLeech($url){
		$url = str_replace("mega.1280.com", "fshare.vn", $url);
		if(stristr($url, "/folder/")) {
			$data = $this->lib->curl("http://www.fshare.vn/check_link.php?action=check_link&arrlinks=".urlencode($url), "", "", 0);
			$page = json_decode($data, true);
			$folder = $page['chk_link_record'];
			$id = explode('<p><b><a href="http://www.fshare.vn/file', $folder);
			$maxfile = count($id);
			for ($i = 1; $i < $maxfile; $i++) {
				preg_match('%\/(.+)" target="_blank">%U', $id[$i], $code);
				//$list = "http://www.fshare.vn/file/".$code[1]."/<br/>"; 
				$list = "<a href=http://www.fshare.vn/file/{$code[1]}>http://www.fshare.vn/file/{$code[1]}/</a><br/>";
				echo $list;
			}
			exit;
		}
	}*/
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.fshare.vn/account_info.php", $cookie, "");
		if(stristr($data, '<dd>VIP</dd>') && stristr($data, '<dt>Thời hạn dùng:</dt>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<dt>Thời hạn dùng:</dt>','<dt>Loại thành viên:</dt>'), '<dd>','</dd>'));
		elseif(stristr($data, 'Tổng file upload:') && stristr($data, '<dd>VIP</dd>')) return array(true, "Account is lifetime!!!");
		elseif(stristr($data, 'Free Member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
     
    public function Login($user, $pass){
		$data = $this->lib->curl("https://www.fshare.vn/login.php", "", "login_useremail={$user}&login_password={$pass}&auto_login=1&url_refe=https://www.fshare.vn/");	
        $cookie = $this->lib->GetCookies($data);
		return $cookie;
    }
	
    public function Leech($url) {
		$url = preg_replace("@https?:\/\/(www\.)?fshare\.vn@", "http://www.fshare.vn", $url);
		list($url, $pass) = $this->linkpassword($url);  
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form action="', '</form>'));
			$post["link_file_pwd_dl"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Mật khẩu download file không đúng')) $this->error("wrongpass", true, false, 2);
			if(stristr($data,"Tài khoản đang được sử dụng trên máy khác") || stristr($data,"Thông tin xác thực không hợp lệ. Xin vui lòng xóa cookie của trình duyệt"))  {
				$this->lib->curl("{$this->lib->self}?id=check&rand=".time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
				$this->error("blockAcc", true, false);
			}
			elseif(stristr($data, "ocation: http://www.fshare.vn/logout.php")) 	{
				$this->lib->curl("{$this->lib->self}?id=check&rand=".time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
				$this->error("cookieinvalid", true, false);  
			}
			elseif(!$this->isredirect($data)) {
				if(preg_match('%"(http:\/\/download.*\.fshare.vn\/vip\/.+)"%U', $data, $giay))  return trim($giay[1]);
			}
			else  return trim($this->redirect);
		}
		if(stristr($data,"Tài khoản đang được sử dụng trên máy khác") || stristr($data,"Thông tin xác thực không hợp lệ. Xin vui lòng xóa cookie của trình duyệt"))  {
			$this->lib->curl("{$this->lib->self}?id=check&rand=".time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
			$this->error("blockAcc", true, false);
		}
		elseif(stristr($data, "ocation: http://www.fshare.vn/logout.php")) 	{
			$this->lib->curl("{$this->lib->self}?id=check&rand=".time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
			$this->error("cookieinvalid", true, false);  
		}
		elseif(stristr($data,"Thông tin tập tin tải xuống") && stristr($data,"TẢI XUỐNG CHẬM"))  $this->error("accfree", true, false);
		elseif(stristr($data,"Liên kết bạn chọn không tồn tại trên hệ thống Fshare"))	$this->error("dead", true, false, 2);
		elseif(stristr($data,"Vui lòng nhập mật khẩu để tải tập tin")) 	$this->error("reportpass", true, false);
		elseif(!$this->isredirect($data)) {
			if(preg_match('%"(http:\/\/download.*\.fshare.vn\/vip\/.+)"%U', $data, $giay))  return trim($giay[1]);
		}
		else return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Fshare.VN Download Plugin 
* Downloader Class By [FZ]
* Plugin By giaythuytinh176
* Date: 16.7.2013
* Fixed check account: 18.7.2013
* Support file password by giaythuytinh176 [26.7.2013]
*/
?>