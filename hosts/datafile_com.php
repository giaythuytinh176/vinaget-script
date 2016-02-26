<?php

class dl_datafile_com extends Download {

	public function PreLeech($url){
		if(stristr($url, "/f/")) {
			$data = $this->lib->curl($url, "lang=en;", "");
			$data = $this->lib->cut_str($data, 'first file-name', '</table');
			$FID = explode('row-size', $data);
			$maxfile = count($FID) - 1;
			if($maxfile >= 1) echo "Your link is folder link<br>";
			else echo "Your link is folder but no files there<br>";
			for ($i = 0; $i < $maxfile; $i++) {
				$link = $this->lib->cut_str($FID[$i], 'href="', '"');
				$list = "<a href={$link}>{$link}</a><br/>";
				echo $list;
			}
			exit;
		}
	}

	public function CheckAcc($cookie){
		$data = $this->lib->cut_str($cookie, '=', ';');
		$json = json_decode($data, true);
		if(isset($json["code"]) && $json["code"] === 200) {
			if($json["userdata"]["premium_till"] === 0) {
				return [false, "accfree"];
			} elseif($json["userdata"]["traffic_left"] <= 0) {
				return [false, "LimitAcc"];
			} elseif(isset($json["userdata"]["token"])) {
				$date = date("Y/m/d H:i:s", $json["userdata"]["premium_till"]);
				$left = Tools_get::convertmb($json["userdata"]["traffic_left"]);
				return [true, "Expiry Date:{$date}, Bandwidth:{$left}, Token:{$json["userdata"]["token"]}"];
			}
		} elseif(isset($json["message"])) {
			return [false, $json["message"]];
		}
		return [false, "accinvalid"];
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("https://api.datafile.com/users/auth?login={$user}&password={$pass}&accesskey=cddce1a5-a6dd-4300-9c08-eb70909de7c6", "", "", 0);
		return "dummy={$data}; ";
	}

	public function Leech($url) {
		global $lib;
		$data = $this->lib->cut_str($this->lib->cookie, '=', ';');
		$json = json_decode($data, true);
		if(isset($json["userdata"]["token"])) {
			$this->lib->cookie = '';
			$data = $this->lib->curl("https://api.datafile.com/files/download?file={$url}&token={$json["userdata"]["token"]}","","",0);
			$json2 = json_decode($data, true);
			if(isset($json2["download_url"])) {
				$link = $json2["download_url"];
				$data = $this->lib->curl($link, "", "", -1);
				if(strpos($data, "404 Not Found") !== false) $this->error("dead", true, false, 2);
				return $link;
			} elseif($json2["code"] === 704) {
				$this->error("LimitAcc");
			} elseif($json2["code"] === 703) {
				$this->error("dead", true, false, 2);
			}
		} else {
			$this->error("accinvalid");
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* DataFile.com Download Plugin by giaythuytinh176
* Downloader Class By [FZ]
* Date: 20.7.2013
* Fix check account by giaythuytinh176 [21.7.2013]
* Fix check account by giaythuytinh176 [6.8.2013]
* Fix check account by hogeunk [4.3.2015]
* Made API version by hogeunk [2016/01/08]
* Check deleted file, Fix filename process [2016/02/14]
* Remove filename process [2016/02/24]
*/
?>