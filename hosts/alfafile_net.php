<?php

class dl_alfafile_net extends Download {

	public function CheckAcc($cookie){
		$json = $this->lib->cut_str($cookie, '=', ';');
		$data = json_decode($json, true);
		if($data["status"] == 200) {
			if($data["response"]["user"]["is_premium"] != true) {
				return [false, "accfree"];
			} elseif($data["response"]["user"]["traffic"]["left"] == 0) {
				return [false, "LimitAcc"];
			} else {
				$date = date("Y/m/d H:i:s", $data["response"]["user"]["premium_end_time"]);
				$left = Tools_get::convertmb($data["response"]["user"]["traffic"]["left"]);
				return [true, "Expiry Date : {$date}<br>Bandwidth Left : {$left}"];
			}
		} else {
			return [false, $data["details"]];
		}
		return [false, 'accinvalid'];
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("https://alfafile.net/api/v1/user/login?login={$user}&password={$pass}", "", "", 0);
		return "dummy=".$data.";";
	}
	
    public function Leech($url) {
		$json = $this->lib->cut_str($this->lib->cookie, '=', ';');
		$data = json_decode($json, true);
		if(isset($data["response"]["token"])) {
			$id = explode('/', $url)[4];
			$down = $this->lib->curl("https://alfafile.net/api/v1/file/download?file_id={$id}&token={$data["response"]["token"]}", "", "", 0);
			$down = json_decode($down, true);
			if($down["status"] == 200) {
				return $down["response"]["download_url"];
			} elseif($down["status"] == 404) {
				$this->error("dead", true, false, 2);
			} elseif($down["status"] == 401) {
				$this->error("Token expired, please try again.");
			} else {
				$this->error($down["details"]);
			}
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Alfafile Download Plugin 
* Downloader Class By [FZ]
* 1st version by hogeunk [2016.02.11]
* Continue leeching when status 401 by hogeunk [2016/02/19]
*/
?>