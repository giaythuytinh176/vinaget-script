<?php

class dl_fshare_vn extends Download {
       
    public function Login($user, $pass){
        $post["login_useremail"]= $user;
        $post["login_password"]= $pass;
        $post['url_refe'] = $url;
		$post['auto_login'] = '1';
        $data = $this->lib->curl("https://www.fshare.vn/login.php","",$post);                        
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
			if (preg_match('%ocation: (http:\/\/([a-z0-9]+\.)?fshare\.vn\/vip\/.+)%i', $data, $redir) || preg_match("%<a href='(.+)'><img src=''\/green\/vi\/images\/vip_package_bt.png''\/>%U", $data, $redir)) 
					return trim($redir[1]);
			elseif (preg_match("%'(http:\/\/.+.fshare\.vn\/vip\/.+)'%U", $data, $match) || preg_match("%<a href='(.+)'><img src=''\/green\/vi\/images\/vip_package_bt.png''\/>%U", $data, $match))
					return trim($match[1]);
			elseif (stristr($data,"Tài khoản đang được sử dụng trên máy khác")) 
				$this->error("blockAcc", true, false);
	
			elseif(stristr($data,'Vui lòng nhập mật khẩu để tải tập tin')) 
				$this->error("notsupportpass", true, false);
 
			elseif(stristr($data,'Thông tin xác thực không hợp lệ. Xin vui lòng xóa cookie của trình duyệt để tiếp tục !</b></font>'))
				$this->error("blockAcc", true, false);

			elseif(stristr($data,'Liên kết bạn chọn không tồn tại trên hệ thống Fshare'))
				$this->error("dead", true, false, 2);

			elseif(stristr($data,'Tài khoản của bạn thuộc GUEST nên chỉ tải xuống 1 lần'))
				$this->error("accinvalid", true, false);
	
			elseif(stristr($data,'THÔNG TIN TẬP TIN TẢI XUỐNG') && stristr($data,'TẢI XUỐNG CHẬM'))
				$this->error("accfree", true, false);

				return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Fshare.VN Download Plugin By giaythuytinh176
* Downloader Class By [FZ]
* Date: 16.7.2013
*/
?>