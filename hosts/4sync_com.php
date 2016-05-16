<?php

class dl_4sync_com extends Download {

	public function FreeLeech($url) {
		$data = $this->lib->curl($url,'','');
		if(strpos($data, 'jsDLink" value="') !== false){
			return $this->lib->cut_str($data, 'jsDLink" value="', '"');;
		} else {
			$this->error("dead", true, false, 2);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 4sync Download Plugin by hogeunk
* Downloader Class By hogeunk
* 1st version by hogeunk [2016/05/16]
*/
?>