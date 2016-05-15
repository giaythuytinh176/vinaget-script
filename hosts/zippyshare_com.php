<?php

class dl_zippyshare_com extends Download
{
    public function FreeLeech($url)
	{
		if (strpos($url, "www") == false) $url = str_replace("http://", "http://www.", $url);
		$srv = $this->lib->cut_str($url,'http://','zippyshare.com');
		$page = $this->lib->curl($url, "ziplocale=en".$this->lib->cookie, "");
		$this->save("ziplocale=en; " .$this->lib->GetCookies($page)); 
		if (stristr($page, '>File does not exist on this server<') || stristr($page, '>File has expired and does not exist anymore on this server')) $this->error("dead", true, false, 2);
		elseif (stristr($page, '/"+(b+18)+"/'))
		{
			$id = $this->lib->cut_str($page, "document.getElementById('dlbutton').omg = ", ';');
			$tachid = explode('%', $id); 
			$id2 = $this->lib->cut_str($page, "parseInt(document.getElementById('dlbutton').omg) * (", ');');
 			$tachid2 = explode('%', $id2); 
			$b = ($tachid[0] % $tachid[1]) * ($tachid2[0] % $tachid2[1]);
			$data = $this->lib->cut_str($page, 'document.getElementById(\'dlbutton\').href    = "', '";');
			$tach = str_replace('"+(b+18)+"', $b + 18, $data);  
			$link = "http://{$srv}zippyshare.com{$tach}"; 
		}
 		elseif (stristr($page, '/"+e()+"/'))
		{
			$a = $this->lib->cut_str($page, ".a = ", ';');
			$b = $this->lib->cut_str($page, "var b = ", ';');
			$ID = (($a+3)*3)%$b + 3;
			$data = $this->lib->cut_str($page, "dlbutton').href = \"", '";');
			$tach = str_replace('"+e()+"', $ID, $data);
			$link = "http://{$srv}zippyshare.com{$tach}";
		}
		elseif (stristr($page, '/"+(a * b + c + d)+"/'))
		{
			$a = $this->lib->cut_str($page, "var a = ", ';');
			$tacha = explode('%', $a); 
			
			$b = $this->lib->cut_str($page, "var b = ", ';');
			$tachb = explode('%', $b);
			
			$c = $this->lib->cut_str($page, "var c = ", ';');
			if(preg_match_all('/var d = (.*);/i', $page, $tachd)) $d = explode('%', $tachd[1][1]);
			
			$ID = ($tacha[0] % $tacha[1]) * ($tachb[0] % $tachb[1]) + $c + ($d[0] % $d[1]);
			
			$data = $this->lib->cut_str($page, "dlbutton').href = \"", '";');
			$tach = str_replace('"+(a * b + c + d)+"', $ID, $data);
			$link = "http://{$srv}zippyshare.com{$tach}";
		}
		elseif (stristr($page, '"+(n + n * 2 + b)+'))
		{
			$n = $this->lib->cut_str($page, "var n = ", ';');
			$b = $this->lib->cut_str($page, "var b = ", ';');
			$data = $this->lib->cut_str($page, "dlbutton').href = \"", '";');
			$tach = explode("\"", $data);
			$number = current(explode("/", $tach[2]));
			$ID = $n + $n * 2 + $b.$number;
			$tach1 = str_replace('"'.$tach[1].'"'.$number, $ID, $data);
			$link = "http://{$srv}zippyshare.com{$tach1}";
		}
		else
		{
			$id = $this->lib->cut_str($page, "dlbutton').href = \"", '";');
 			$id2 = preg_replace('/\s+/', '', $this->lib->cut_str($page, '" + (', ') + "'));
			$replace = $this->lib->cut_str($id, '/"', '"/');
			$tachid2 = explode('%', $id2); 
			$L1 = $tachid2[0];
			$L2 = $this->lib->cut_str($id2, "%", '+');
			$L3 = $this->lib->cut_str($id2, "+", '%');
			$L4 = $tachid2[2];
			$ID = $L1 % $L2 + $L3 % $L4;
			$ID2 = str_replace('"', '', str_replace($replace, $ID, $id));
			$link = "http://{$srv}zippyshare.com{$ID2}";
		}
		if ($link) return trim($link);
		if (!$link) $this->error("Zippyshare under maintenance", true, false);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Zippyshare Download Plugin by giaythuytinh176 [18.2.2014]
* Downloader Class By [FZ]
*/
?>