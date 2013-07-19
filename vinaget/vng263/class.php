<?php
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Description: 
	- Vinaget is script generator premium link that allows you to download files instantly and at the best of your Internet speed.
	- Vinaget is your personal proxy host protecting your real IP to download files hosted on hosters like RapidShare, megaupload, hotfile...
	- You can now download files with full resume support from filehosts using download managers like IDM etc
	- Vinaget is a Free Open Source, supported by a growing community.
* Code LeechViet by VinhNhaTrang
* Developed by ..:: [H] ::..

*/

##################################### Begin class getinfo #####################################
class getinfo {
	function config(){
		include("config.php");
		$this->self = 'http://'.$_SERVER['HTTP_HOST'].preg_replace('/\?.*$/', '', isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

		if (!file_exists(".htaccess")) {
			$clog=fopen(".htaccess","a") 
			or die('<CENTER><font color=red size=4>Can\'t find file <B>.htaccess</B></font></CENTER>');
			fwrite($clog,'');
			fclose($clog);
		}
		if (filesize(".htaccess") < 200) {

			#----------- Begin create file .htaccess -----------#
			$slashes = explode('/', $this->self);
			$max =  count($slashes)-1;
			$namefolder = "";
			if($max>3) for($i=3;$i<$max;$i++) $namefolder .= "/".$slashes[$i];
			else $namefolder = "/";
			$strhta ="";
			if(function_exists("apache_get_modules") && in_array('mod_rewrite',@apache_get_modules())) {
				$strhta .= "RewriteEngine on\n";
				$strhta .= "RewriteBase $namefolder\n";
				$strhta .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
				$strhta .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
				$strhta .= "RewriteRule ^(.*)/(.*)/ index.php?file=$2\n";
			}
			$strhta .= "order deny,allow\n";
			$strhta .= '<files ~ "\.(php|php.*|sphp|php3|php4|php5|phtml|cgi|pl|shtml|dhtml|html|htm|txt|dat)$">';
			$strhta .= "\n";
			$strhta .= "deny from all\n";
			$strhta .= "</files>\n";
			$strhta .= "\n";
			$strhta .= "<files add.php>\n";
			$strhta .= "allow from all\n";
			$strhta .= "</files>\n";
			$strhta .= "\n";
			$strhta .= "<files index.php>\n";
			$strhta .= "allow from all\n";
			$strhta .= "</files>\n";
			$strhta .= "\n";
			$strhta .= "<files login.php>\n";
			$strhta .= "allow from all\n";
			$strhta .= "</files>\n";
			$strhta .= "\n";
			$strhta .= "<files log.txt>\n";
			$strhta .= "deny from all\n";
			$strhta .= "</files>\n";
			$strhta .= "\n";
			$strhta .= '<files ~ "^\.">';
			$strhta .= "\n";
			$strhta .= "deny from all\n";
			$strhta .= "</files>";

			$htafile = ".htaccess";
			$fhta = fopen ($htafile, "w")
			or die('<CENTER><font color=red size=3>could not open file! Try to chmod the folder "<B>.htaccess</B>" to 666</font></CENTER>');
			fwrite ($fhta, $strhta)
			or die('<CENTER><font color=red size=3>could not write file! Try to chmod the folder "<B>.htaccess</B>" to 666</font></CENTER>');
			fclose ($fhta); 
			@chmod($htafile, 0666);

			#----------- End create file .htaccess -----------#
		}

		if (!file_exists ($fileinfo_dir)) {
			mkdir($fileinfo_dir)  
			or die("<CENTER><font color=red size=4>Could not create folder! Try to chmod the folder \"<B>$fileinfo_dir</B>\" to 777</font></CENTER>");
			@chmod($fileinfo_dir, 0777);
		}
		if (!file_exists ($fileinfo_dir."/files" )) {
			mkdir($fileinfo_dir."/files")   
			or die("<CENTER><font color=red size=4>Could not create folder! Try to chmod the folder \"<B>$fileinfo_dir/files</B>\" to 777</font></CENTER>");
			@chmod($fileinfo_dir."/files", 0777);
		}
		if(!file_exists($fileinfo_dir."/index.php")) { 
			$clog=fopen($fileinfo_dir."/index.php","a") 
			or die("<CENTER><font color=red size=4>Could not create folder! Try to chmod the folder \"<B>$fileinfo_dir</B>\" to 777</font></CENTER>");
			fwrite($clog,'<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=http://'.$homepage.'">');
			fclose($clog);
			@chmod($fileinfo_dir."/index.php", 0666);
		}
		if(!file_exists($fileinfo_dir."/files/index.php")) { 
			$clog=fopen($fileinfo_dir."/files/index.php","a") 
			or die("<CENTER><font color=red size=4>Could not create folder! Try to chmod the folder \"<B>$fileinfo_dir/files</B>\" to 777</font></CENTER>");
			fwrite($clog,'<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=http://'.$homepage.'">');
			fclose($clog);
			@chmod($fileinfo_dir."/files/index.php", 0666);
		}
		$this->Deny = true;
		if ($Secure == false) $this->Deny = false;
		foreach ($password as $login_vng)
		if(isset($_COOKIE["secureid"]) && $_COOKIE["secureid"] == md5($login_vng)) {
			$this->Deny = false;
			break;
		}
		$this->lang = $lang;
		$this->Secure = $Secure;
		$this->fileinfo_dir = $fileinfo_dir;
		$this->fileinfo_ext = $fileinfo_ext;
		$this->download_prefix = $download_prefix;
		$this->banned = explode(' ', '.htaccess .htpasswd .php .php3 .php4 .php5 .phtml .asp .aspx .cgi .pl');    // banned filetypes
		$this->unit = 512; 
		$this->UserAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:12.0) Gecko/20100101 Firefox/12.0';
		$this->limitMBIP = $limitMBIP;
		$this->ttl = $ttl;
		$this->limitPERIP = $limitPERIP; 
        $this->ttl_ip = $ttl_ip; 
		$this->max_jobs_per_ip = $max_jobs_per_ip;  
		$this->max_jobs  = $max_jobs; 
		$this->max_load = $max_load;
		$this->zlink = $ziplink;
		$this->link_zip = $apiadf;
		$this->badword = $badword;
		$this->act = $action;
		$this->listfile = $listfile;
		$this->checkacc = $checkacc;
		$this->privatef = $privatefile;
		$this->privateip = $privateip;
		$this->check3x = $checklinksex;
		$this->filecookie = "/".$filecookie;
		$this->colorfn = $colorfilename;
		$this->colorfs = $colorfilesize;
		$this->title = $title;
	}

	function notice(){
		printf($this->lang['notice'],Tools_get::convert_time($this->ttl*60),$this->limitPERIP,Tools_get::convert_time($this->ttl_ip*60));
		$this->CheckMBIP();
		$MB1IP = Tools_get::convertmb($this->countMBIP*1024*1024);
		$thislimitMBIP = Tools_get::convertmb($this->limitMBIP*1024*1024);
		$maxsize =  Tools_get::convertmb($this->max_size_other_host*1024*1024);

		printf($this->lang['yourjobs'],$_SERVER['REMOTE_ADDR'],$this->lookup_ip($_SERVER['REMOTE_ADDR']),$this->max_jobs_per_ip,$MB1IP,$thislimitMBIP);

		printf($this->lang['status'],$maxsize,count($this->jobs),$this->max_jobs,$this->get_load(),$this->max_load,Tools_get::useronline());
	}

	function showplugin(){
		foreach ($this->acc as $host => $value) {
			$xout = array('');
			$xout = $this->acc[$host]['accounts'];
			$max_size = $this->acc[$host]['max_size'];
			if (empty($xout[0])==false && empty($host)==false){
				$hosts[]=  '<span class="plugincollst">' .$host . ' '.count($xout).'</span><br/>';
			}
		}
		if(isset($hosts)){
			if(count($hosts)>4){
				for ($i=0; $i < 5; $i++) echo "$hosts[$i]";
				echo "<div id=showacc style='display: none;'>";
				for ($i=5; $i < count($hosts); $i++) echo "$hosts[$i]";
				echo "</div>";
			}
			else for ($i=0; $i < count($hosts); $i++) echo "$hosts[$i]";
			if(count($hosts)>4) echo "<a onclick=\"showOrHide();\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600><div id='moreacc'>".$this->lang['moreacc']."</div></font></a>";
		}
		return false;
	}
	function load_jobs(){
		if (isset($this->jobs)) return;
		$dir = opendir($this->fileinfo_dir."/files/");
		$this->lists = array();
		while ($file = readdir($dir)){
			if (substr($file,-strlen($this->fileinfo_ext)-1) == "." . $this->fileinfo_ext){
				$this->lists[] = $this->fileinfo_dir."/files/" . $file;
			}
		}
		closedir($dir);
		$this->jobs = array();
		if (count($this->lists)){
			sort($this->lists);
			foreach ($this->lists as $file){
				$contentsfile = @file_get_contents($file);
				$jobs_data = @json_decode($contentsfile, true);	
				if (is_array($jobs_data)){
					$this->jobs = array_merge($this->jobs,$jobs_data);
				}
			}
				
		}
	}

	function save_jobs(){
		if (!isset($this->jobs) || is_array($this->jobs)==false)return;
		### clean jobs ###
		$oldest = time() - $this->ttl*60;
		$delete = array();
		foreach ($this->jobs as $key=>$job) {
			if ($job['mtime'] < $oldest) {
				$delete[] = $key;
			}
		}
		foreach ($delete as $url) {
			unset($this->jobs[$url]);
		}
		### clean jobs ###
		$namedata = $timeload = explode(" ", microtime());
		$namedata = $namedata[1]*1000 + round($namedata[0]*1000);
		$this->fileinfo = $this->fileinfo_dir."/files/" .$namedata. "." . $this->fileinfo_ext;
		$tmp = @json_encode($this->jobs);
		$fh = fopen($this->fileinfo, 'w') or die('<CENTER><font color=red size=3>Could not open file ! Try to chmod the folder "<B>'.$this->fileinfo_dir."/files/".'</B>" to 777</font></CENTER>');
		fwrite($fh, $tmp);
		fclose($fh);
		@chmod($this->fileinfo, 0666);
		if (count($this->lists)) foreach ($this->lists as $file) if (file_exists($file)) @unlink($file);
		return true;
	}

	function load_cookies(){
		if (isset($this->cookies)){
    		return;
		}
		$this->cookies_data = @file_get_contents($this->fileinfo_dir.$this->filecookie);
		$this->cookies = @json_decode($this->cookies_data, true);
		if (! is_array($this->cookies))
		{
			$this->cookies = array();
			$this->cookies_data = 'wtf';
		}
	}

	function get_cookie($site){
		$cookie="";
		if(isset($this->cookies) && count($this->cookies)>0){
			foreach ($this->cookies as $ckey=>$cookies){
				if ($ckey === $site){
					$cookie = $cookies['cookie'];
					break;
				}
			}
		}
		return $cookie;
	}

	function save_cookies($site,$cookie){
		if (!isset($this->cookies)) return;
		if($site){
			$cookies = array(
					'cookie'	=> $cookie,
					'time'	=> time(),
			); 
			$this->cookies[$site] = $cookies;
		}
		$tmp = json_encode($this->cookies);
		if ($tmp !== $this->cookies_data){
			$this->cookies_data = $tmp;
			$fh = fopen($this->fileinfo_dir.$this->filecookie, 'w') 
			or die('<CENTER><font color=red size=3>Could not open file ! Try to chmod the folder "<B>'.$this->fileinfo_dir.'</B>" to 777</font></CENTER>');
			fwrite($fh, $this->cookies_data) 
			or die('<CENTER><font color=red size=3>Could not write file ! Try to chmod the folder "<B>'.$this->fileinfo_dir.'</B>" to 777</font></CENTER>');
			fclose($fh);
			@chmod($this->fileinfo_dir.$this->filecookie, 0666);
			return true;	
		}
	}

	function get_load($i = 0){
		$load = array('0', '0', '0');
		if (@file_exists('/proc/loadavg')){
			if ($fh = @fopen('/proc/loadavg', 'r')){
				$data = @fread($fh, 15);
				@fclose($fh);
				$load = explode(' ', $data);
			}
		}
		else{
			if ($serverstats = @exec('uptime')){
				if (preg_match('/(?:averages)?\: ([0-9\.]+),?[\s]+([0-9\.]+),?[\s]+([0-9\.]+)/', $serverstats, $matches)){
					$load = array($matches[1], $matches[2], $matches[3]);
				}
			}
		}
		return $i==-1 ? $load : $load[$i];
	}
	function lookup_ip($ip){
		$this->load_jobs();
		$cnt = 0;
		foreach ($this->jobs as $job)
		{
			if ($job['ip'] === $ip) $cnt++;
		}
		return $cnt;
	}
    function Checkjobs() {
		$ip = $_SERVER ['REMOTE_ADDR'];
		$heute = 0;
		$lasttime = time();
		$altr = $lasttime - $this->ttl_ip*60;
		foreach ($this->jobs as $job){
			if ($job['ip'] === $ip && $job['mtime'] > $altr) {
				$heute++;
				if($job['mtime'] < $lasttime ) $lasttime = $job['mtime'];
			}
		}

		$lefttime = $this->ttl_ip*60 -time() + $lasttime;
		$lefttime = Tools_get::convert_time($lefttime);
		return array($heute,$lefttime);
	}
	function get_account($service){
		$acc = '';
		if (isset($this->acc[$service])){
			$service = $this->acc[$service];
			$this->max_size = $service['max_size'];
			if(count($service['accounts'])>0) $acc = $service['accounts'][rand(0, count($service['accounts'])-1)];
		}
		return $acc;
	}

	function GetCookies($content){
		preg_match_all('/Set-Cookie: (.*);/U',$content,$temp);
		$cookie = $temp[1];
		$cookies = implode('; ',$cookie);
		return $cookies;
	}
	function lookup_job($hash){
		$this->load_jobs();
		foreach ($this->jobs as $key=>$job)
		{
			if ($job['hash'] === $hash) return $job;
		}
		return false;
	}
}
##################################### End class getinfo #######################################


##################################### Begin class stream_get ##################################
class stream_get extends getinfo {
	function stream_get(){
		$this->config();
		include("account.php");
		$this->load_jobs();
		$this->load_cookies();
		$this->cookie = '';
		if(preg_match('%^(http.+.index.php)/(.*?)/(.*?)/%U', $this->self, $redir)) $this->download($redir[3]);
		elseif (isset($_REQUEST['file'])) $this->download($_REQUEST['file']);
		if (isset($_COOKIE['owner'])){
			$this->owner = $_COOKIE['owner'];
		}
		else{
			$this->owner = intval(rand()*10000);
			setcookie('owner', $this->owner, 0);	
		}
	}
	function download($hash){   
		error_reporting (0);
		if (! $job = $this->lookup_job($hash)) {
			sleep(15);
			header("HTTP/1.1 404 Not Found");
			die($this->lang['errorget']);
		}
		if (($_SERVER['REMOTE_ADDR'] !== $job['ip']) && $this->privateip==true) {
			sleep(15);
			die($this->lang['errordl']);
		}
		if ($this->get_load() > $this->max_load) sleep(15);

		$link = '';
		$filesize = $job['size'];
		$filename = $this->download_prefix.Tools_get::convert_name($job['filename']);
		$directlink = urldecode($job['directlink']['url']);
		$this->cookie = $job['directlink']['cookies'];
		
		$link = $directlink;
		$link = str_replace(" ","%20",$link);
		if(!$link) {
			sleep(15);
			header("HTTP/1.1 404 Not Found");
			die($this->lang['erroracc']);
		}
		$range = '';
		if (isset($_SERVER['HTTP_RANGE'])) {
			$range = substr($_SERVER['HTTP_RANGE'], 6);
			list($start, $end) = explode('-', $range);
			$new_length = $filesize - $start;
		}
		$port = 80;
		$schema = parse_url(trim($link));
		$host= $schema['host'];
		$scheme = "http://";
		$gach = explode("/", $link);
		list($path1, $path)  = explode($gach[2], $link);
		if(isset($schema['port'])) $port = $schema['port'];
		elseif ($schema['scheme'] == 'https') {
			$scheme = "ssl://";
			$port = 443;
		}
		if ($scheme != "ssl://") {
			$scheme = "";
		}
		$hosts = $scheme . $host . ':' . $port;
		$fp = @stream_socket_client ($hosts, $errno, $errstr, 20, STREAM_CLIENT_CONNECT );
		if (!$fp) {
			sleep(15);
			header("HTTP/1.1 404 Not Found");
			die ("HTTP/1.1 404 Not Found");
		}
	
		$data = "GET {$path} HTTP/1.1\r\n";
		$data .= "User-Agent: ".$this->UserAgent."\r\n";
		$data .= "Host: {$host}\r\n";
		$data .= "Accept: */*\r\n";
		$data .= $this->cookie ? "Cookie: ".$this->cookie."\r\n" : '';
		if (!empty($range)) $data .= "Range: bytes={$range}\r\n";
		$data .= "Connection: Close\r\n\r\n";
		@stream_set_timeout($fp, 2);
		fputs($fp, $data);
		fflush($fp);
		$header = '';
		do {
			if(!$header) {
				$header .= stream_get_line($fp, $this->unit);
				if(!stristr($header,"HTTP/1")) break;
			}
			else $header .= stream_get_line($fp, $this->unit);
		} 
		while (strpos($header, "\r\n\r\n" ) === false);
		// Must be fresh start
		if( headers_sent() )
		die('Headers Sent');
		// Required for some browsers
		if(ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off'); 
		header("Pragma: public"); // required 
		header("Expires: 0"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Cache-Control: private",false); // required for certain browsers 
		header("Content-Transfer-Encoding: binary"); 
		header("Accept-Ranges: bytes");

		if(stristr($header,"TTP/1.0 200 OK") || stristr($header,"TTP/1.1 200 OK")) {
			if(!is_numeric($filesize)) $filesize = trim ($this->cut_str ($header, "Content-Length:", "\n" ));
			if(stristr($header,"filename")) {
				$filename = trim ($this->cut_str ( $header, "filename", "\n" ) );
				$filename = preg_replace("/(\"\;\?\=|\"|=|\*|UTF-8|\')/","",$filename);	
				$filename = $this->download_prefix.$filename;
			}
			if(is_numeric($filesize)) {
				header("HTTP/1.1 200 OK");
				header("Content-Type: application/force-download");
				header("Content-Disposition: attachment; filename=".$filename);
				header("Content-Length: {$filesize}");
			}
			else {
				sleep(5);
				header("HTTP/1.1 404 Not Found");
				die ("HTTP/1.1 404 Not Found");
			}
		}
		elseif(stristr($header,"TTP/1.1 206") || stristr($header,"TTP/1.0 206")) {
			sleep(2);
			header("HTTP/1.1 206 Partial Content");
			header("Content-Type: application/force-download");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range/{$filesize}");
		}
		else { 
			sleep(10);
			header("HTTP/1.1 404 Not Found");
			die ("HTTP/1.1 404 Not Found");
		}
		
		$tmp = explode("\r\n\r\n", $header);
		$max =count($tmp);
		for($i=1;$i < $max;$i++){
			print $tmp[$i];
			if($i != $max-1) echo "\r\n\r\n";
		}
		while (!feof($fp) && (connection_status()==0)) {
			$recv = @stream_get_line($fp, $this->unit);
			@print $recv;
			@flush();
			@ob_flush();
		}
		fclose($fp);
		exit;
	}

    function CheckMBIP(){
		$this->countMBIP = 0;
		$this->totalMB = 0;
		$this->timebw = 0;
		$timedata =time();
		foreach ($this->jobs as $job){
			if ($job['ip'] == $_SERVER['REMOTE_ADDR']) {
				$this->countMBIP = $this->countMBIP + $job['size']/1024/1024;
				if($job['mtime'] < $timedata )  $timedata = $job['mtime'];
				$this->timebw = $this->ttl*60 + $timedata -time();
			}
			if($this->privatef==false){
				$this->totalMB = $this->totalMB + $job['size']/1024/1024;
				$this->totalMB = round($this->totalMB);
			}
			else {
				if ($job['owner'] == $this->owner) {
					$this->totalMB = $this->totalMB + $job['size']/1024/1024;
					$this->totalMB = round($this->totalMB);
				}
			}

		}
		$this->countMBIP = round($this->countMBIP);
		if ($this->countMBIP >= $this->limitMBIP) return false;
		return true;
	}
	function curl($url,$cookies,$post,$header=1){
		$ch = @curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, $header);
		if ($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->UserAgent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER,$url); 
		if ($post){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
		$page = curl_exec( $ch);
		curl_close($ch); 
		return $page;
	}
	function cut_str($str, $left, $right)  {
		$str = substr ( stristr ( $str, $left ), strlen ( $left ) );
		$leftLen = strlen ( stristr ( $str, $right ) );
		$leftLen = $leftLen ? - ($leftLen) : strlen ( $str );
		$str = substr ( $str, 0, $leftLen );
		return $str;
	}

	function GetCookies($content){
		preg_match_all('/Set-Cookie: (.*);/U',$content,$temp);
		$cookie = $temp[1];
		$cookies = implode('; ',$cookie);
		return $cookies;
	}
	function GetAllCookies($page) {
		$lines = explode("\n", $page);
		$retCookie = "";
		foreach ($lines as $val) 
		{
			preg_match('/Set-Cookie: (.*)/',$val,$temp);
			if (isset($temp[1]))
			{
				if ($cook = substr($temp[1], 0, stripos($temp[1], ';')))
						$retCookie .= $cook . ";";
			}
		}
		return $retCookie;
    }
	function mf_str_conv($str_or)
	{
		$str_or = stripslashes($str_or);
		if (!preg_match("/unescape\(\W([0-9a-f]+)\W\);\w+=([0-9]+);[^\^]+\)([0-9\^]+)?\)\);eval/", $str_or, $match)) return $str_or;
		$match[3] = $match[3] ? $match[3] : "";
		$str_re = "";
		for ($i = 0; $i < $match[2]; $i++){
			$c = HexDec(substr($match[1], $i*2, 2));
			eval ("\$c = \$c".$match[3].";");
			$str_re .= chr($c);
		}
		$str_re = str_replace($match[0], stripslashes($str_re), $str_or);
		if (preg_match("/unescape\(\W([0-9a-f]+)\W\);\w+=([0-9]+);[^\^]+\)([0-9\^]+)?\)\);eval/", $str_re, $dummy))
			$str_re = $this->mf_str_conv($str_re);
		return $str_re;
	}

	function main(){
		if ($this->get_load() > $this->max_load){
			echo '<center><b><i><font color=red>'.$this->lang['svload'].'</font></i></b></center>';
			return;
		}
		if (isset($_POST['urllist'])) {
			$url = $_POST['urllist'];
			$url = str_replace("\r", "",  $url);
			$url = str_replace("\n", "",  $url);
			$url = str_replace("<", "",  $url);
			$url = str_replace(">", "",  $url);
			$url = str_replace(" ", "",  $url);
		}
		if (isset($url)&& strlen($url) > 10){
			if(substr($url,0,4)=='www.')$url = "http://".$url;
			if(!$this->check3x) $dlhtml = $this->get($url);
			else{
				################### CHECK 3X #########################
				$check3x = false;
				if(strpos($url,"|not3x")) $url = str_replace("|not3x","",$url) ;
				else{
					$data =  $this->curl("http://www.google.com/search?q=$url", "", "");
					$totalbadword = count ($this->badword);
					for($i = 0; $i < $totalbadword; $i++){
						if (stristr($data,$this->badword[$i])) {	
							$check3x = true;
							break;
						}
					}			
				}
				if($check3x == false) $dlhtml = $this->get($url);
				else {
					$dlhtml = printf($this->lang['issex'],$url,$url,$url);
					unset($check3x);
				}
				################### CHECK 3X #########################
			}
		}
		else $dlhtml =  "<b><a href=".$url." style='TEXT-DECORATION: none'><font color=red face=Arial size=2><s>".$url."</s></font></a> <img src=images/chk_error.png width='15' alt='errorlink'> <font color=#ffcc33><B>".$this->lang['errorlink']."</B></font><BR>";
		echo $dlhtml;
	}
	function get($url){
		$this->CheckMBIP();
		$dlhtml = '';
		if (count($this->jobs) >= $this->max_jobs){
			$dlhtml = '<center><b><i><font color=red>'.$this->lang['manyjob'].'</font></i></b></center>';
			return $dlhtml;
		}
		if ($this->countMBIP >= $this->limitMBIP){
			printf($this->lang['countMBIP'],Tools_get::convertmb($this->limitMBIP*1024*1024),Tools_get::convert_time($this->ttl*60),Tools_get::convert_time($this->timebw));
			return $dlhtml;
		}
		/* check 1 */
		$checkjobs = $this->Checkjobs();
		$heute =  $checkjobs[0];
		$lefttime = $checkjobs[1];
		if ($heute >= $this->limitPERIP){
			printf($this->lang['limitPERIP'],$this->limitPERIP,Tools_get::convert_time($this->ttl_ip*60),$lefttime);
			return $dlhtml;
		}
		/* /check 1 */
		if ($this->lookup_ip($_SERVER['REMOTE_ADDR']) >= $this->max_jobs_per_ip){
			$dlhtml = '<center><b><i><font color=red>'.$this->lang['limitip'].'</font></i></b></center>';
			return $dlhtml;
		}
		$url = trim($url);
		if (empty($url)) return;
		$Original = $url;
		$link = "";
		$user = ''; $pass = ''; $cookie = '';
		$report = false;
		include ("hosts/hosts.php");
		ksort($host);
		foreach ($host as $file => $site){
			$site = substr($site,0,-4);
			$site = str_replace("_",".",$site) ;
			if (preg_match('%'.$site.'%U', $Original)){
				include ('hosts/'.$host[$file]);
				break;
			}
		}
		
		if($report) {
			return $report;
		}
		if(!$link){
			$size_name = Tools_get::size_name($Original, "");
			$filesize =  $size_name[0];
			$filename = $size_name[1];
			$this->max_size = $this->max_size_other_host;
			if($size_name[0]>1024*100) $link=$url;
			else {
				printf($this->lang['notsupport'],$Original,$Original);
				return $dlhtml;
			}
		}
		$hosting = Tools_get::site_hash($Original);

		if(!isset($filesize)) {
			printf($this->lang['notdl'],$Original,$Original);
			return $dlhtml;
		}
		if(!isset($this->max_size)) $this->max_size = $this->max_size_other_host;
		$msize = Tools_get::convertmb($filesize);
		$hash = md5($_SERVER['REMOTE_ADDR'].$Original);
		if ($hash === false) {
			return $this->lang['cantjob'];
		}
		if ($filesize > $this->max_size*1024*1024) {
			printf($this->lang['filebig'],$Original,$msize,Tools_get::convertmb($this->max_size*1024*1024));
			return $dlhtml;
		}
		if (($this->countMBIP+$filesize/(1024*1024)) >= $this->limitMBIP){
			printf($this->lang['countMBIP'],Tools_get::convertmb($this->limitMBIP*1024*1024),Tools_get::convert_time($this->ttl*60),Tools_get::convert_time($this->timebw));
			return $dlhtml;
		}
		/* check 2 */
		$checkjobs = $this->Checkjobs();
		$heute =  $checkjobs[0];
		$lefttime = $checkjobs[1];
		if ($heute >= $this->limitPERIP){
			printf($this->lang['limitPERIP'],$this->limitPERIP,Tools_get::convert_time($this->ttl_ip*60),$lefttime);
			return $dlhtml;
		}
		/* /check 2 */
		$job = array(
			'hash'	=> substr(md5($hash), 0, 10),
            'path'	=> substr(md5(rand()), 0, 5),
			'filename'	=> urlencode($filename),
			'size'	=> $filesize,
			'msize'	=> $msize,
			'mtime'	=> time(),
			'speed'	=> 0,
			'url'	=> urlencode($Original),
			'owner'	=> $this->owner,
			'ip'	=> $_SERVER['REMOTE_ADDR'],
			'type'	=> 'direct',
			'directlink'	=> array(
				'url'	=> urlencode($link),
					'cookies'=> $this->cookie,
				),
		);
		$this->jobs[$hash] = $job;
		$this->save_jobs();
		$tiam = time().rand(0,999);
		
		$gach = explode('/', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
		$sv_name = "";
		for ($i=0;$i <count($gach)-1; $i++) $sv_name .= $gach[$i]."/";
		if(function_exists("apache_get_modules") && in_array('mod_rewrite',@apache_get_modules())) $linkdown = 'http://'.$sv_name.$hosting.'/'.$job['hash'].'/'.urlencode($filename);
		else $linkdown = 'http://'.$sv_name.'index.php/'.$hosting.'/'.$job['hash'].'/'.urlencode($filename);

		##########Begin short link ############
		if(empty($this->zlink)==false && empty($this->link_zip)==false && empty($link)==false ){ 
			$datalink = $this->curl($this->link_zip.$linkdown,'','',0);
			if (preg_match('%(http:\/\/.++)%U', $datalink, $shortlink)) $lik = trim($shortlink[1]);
			else $lik = $linkdown;	
		}
		############ End short link  ##########
		else $lik = $linkdown;

		$dlhtml =  "<b><a title='click here to download' href='$lik' style='TEXT-DECORATION: none' target='$tiam'> <font color='#00CC00'>".$filename."</font> <font color='#FF66FF'>($msize)</font></a></b>";
		return $dlhtml;
	}
	function datecmp($a, $b){
		return ($a[1] < $b[1]) ? 1 : 0;
	}
	function fulllist() {
		$act="";
		if($this->act['delete']==true) { $act.='<option value="del">'.$this->lang['del'].'</option>'; }
		if($this->act['rename']==true)  { $act.='<option value="ren">'.$this->lang['rname'].'</option>';}
		if ($act != ""){
			if ((isset($_POST['checkbox'][0]) && $_POST['checkbox'][0] != null) || isset($_POST['renn']) || isset($_POST['remove'])){ 
				echo '<table style="width: 500px; border-collapse: collapse" border="1" align="center"><tr><td><center>';
				switch ($_POST['option']){
					case 'del': $this->deljob(); break;
					case 'ren': $this->renamejob(); break;
				}
				if (isset($_POST['renn'])) $this->renamejob();
				if (isset($_POST['remove'])) $this->deljob();
				echo "</center></td></tr></table><br/>";
			}
		}
		else echo '</select>';
		$files = array();
		foreach ($this->jobs as $job) {
			if ($job['owner']!=$this->owner  && $this->privatef==true) continue;
			$files[] = array(urldecode($job['url']), $job['mtime'], $job['hash'], urldecode($job['filename']),$job['size'],$job['ip'],$job['msize']);
		}	
		if (count($files)==0) { echo "<Center>".$this->lang['notfile']."<br/><a href='$this->self'> [".$this->lang['main']."] </a></center>"; return;}
		echo "<script type=\"text/javascript\">function setCheckboxes(act){elts = document.getElementsByName(\"checkbox[]\");var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;if (elts_cnt){ for (var i = 0; i < elts_cnt; i++){elts[i].checked = (act == 1 || act == 0) ? act : (elts[i].checked ? 0 : 1);} }}</script>";
		echo "<center><a href=javascript:setCheckboxes(1)> Check All </a> | <a href=javascript:setCheckboxes(0)> Un-Check All </a> | <a href=javascript:setCheckboxes(2)> Invert Selection </a></center><br/>";	
		echo "<center><form action='$this->self' method='post' name='flist'><select onchange='javascript:void(document.flist.submit());'name='option'>";

		if ($act == "") echo "<option value=\"dis\"> ".$this->lang['acdis']." </option>"; else echo '<option selected="selected">'.$this->lang['ac'].'</option>'.$act;
		echo '</select>';

		echo '<div style="overflow: auto; height: auto; max-height: 450px; width: 800px;"><table id="table_filelist" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%"><thead><tr class="flisttblhdr" valign="bottom"><td id="file_list_checkbox_title" class="sorttable_checkbox">&nbsp;</td><td class="sorttable_alpha"><b>'.$this->lang['name'].'</b></td><td><b>'.$this->lang['original'].'</b></td><td><b>'.$this->lang['size'].'</b></td><td><b>'.$this->lang['date'].'</b></td></tr></thead><tbody>
		';

		usort($files, array($this, 'datecmp'));
		$data = "";
		foreach ($files as $file){
			$timeago = Tools_get::convert_time(time() - $file[1]). " ".$this->lang['ago'];
			if (strlen($file[3]) > 80) $file[3]= substr($file[3],0,70);
			$hosting = substr(Tools_get::site_hash($file[0]),0,15);
			if(function_exists("apache_get_modules") && in_array('mod_rewrite',@apache_get_modules())) $linkdown = Tools_get::site_hash($file[0])."/$file[2]/$file[3]";
			else $linkdown = 'index.php/'.Tools_get::site_hash($file[0])."/$file[2]/$file[3]"; 
			$data .= "<tr class='flistmouseoff' align='center'><td><input name='checkbox[]' value='$file[2]+++$file[3]' type='checkbox'></td><td><a href='".$linkdown."' style='font-weight: bold; color: rgb(0, 0, 0);'>$file[3]</a></td><td><a href='$file[0]' style='color: rgb(0, 0, 0);'>".$hosting."</a></td><td title='$file[5]'>".$file[6]."</td><td><a href=http://www.google.com/search?q=$file[0] title='".$this->lang['clickcheck']."' target='$file[1]'><font color=#000000>$timeago</font></a></center></td></tr>";

		}
		$this->CheckMBIP();
		echo $data;
		$totalall = Tools_get::convertmb($this->totalMB*1024*1024);
		$MB1IP = Tools_get::convertmb($this->countMBIP*1024*1024);
		$thislimitMBIP = Tools_get::convertmb($this->limitMBIP*1024*1024);
		$timereset = Tools_get::convert_time($this->ttl*60);
		
		echo "</tbody><tbody><tr class='flisttblftr'><td>&nbsp;</td><td>".$this->lang['total'].":</td><td></td><td>$totalall</td><td>&nbsp;</td></tr></tbody></table>
		</div></form><center><b>".$this->lang['used']." $MB1IP/$thislimitMBIP - ".$this->lang['reset']." $timereset</b>.</center><br/>";

	}
	function deljob(){	
		if($this->act['delete']==false) return;
		if (isset($_POST['checkbox'])){
			echo "<form action='$this->self' method='post'>";
			for ($i=0; $i < count($_POST['checkbox']); $i++){
				$temp = explode("+++",$_POST['checkbox'][$i]);
				$ftd = $temp[0];
				$name = $temp[1];
				echo "<br/><b> $name </b>";
				echo '<input type="hidden" name="ftd[]" value="'.$ftd.'" />';
				echo '<input type="hidden" name="name[]" value="'.$name.'" />';
			}
			echo "<br/><br/><input type='submit' value='".$this->lang['del']."' name='remove'/> &nbsp; <input type='submit' value='".$this->lang['canl']."' name='Cancel'/><BR><BR>";
		}
		if (isset($_POST['remove'])){	
			echo "<BR>";
			for ($i=0; $i < count($_POST['ftd']); $i++){
				$ftd = $_POST['ftd'][$i];
				$name = $_POST['name'][$i];
				$key = "";
				foreach ($this->jobs as $url=>$job){
					if ($job['hash'] == $ftd){
						$key = $url;
						break;
					}
				}
				if($key){
					unset($this->jobs[$key]);
					echo "<center>File: <b>$name</b> ".$this->lang['deld'];
				}
				else echo "<center>File: <b>$name</b> ".$this->lang['notfound'];
				echo "</center>";
			}
			echo "<BR>";
			$this->save_jobs();
		}
		if (isset($_POST['Cancel'])){	
			$this->fulllist();
		}
	}

	function renamejob(){	
		if($this->act['rename']==false) return;
		if (isset($_POST['checkbox'])){
			echo "<form action='$this->self' method='post'>";
			for ($i=0; $i < count($_POST['checkbox']); $i++){
				$temp = explode("+++",$_POST['checkbox'][$i]);
				$name = $temp[1];
				echo "<br/><b> $name </b>";
				echo '<input type="hidden" name="hash[]" value="'.$temp[0].'" />';
				echo '<input type="hidden" name="name[]" value="'.$name.'" />';
				echo '<br/>'.$this->lang['nname'].': <input type="text" name="nname[]" value="'.$name.'"/ size="70"><br />';
			}
			echo "<br/><input type='submit' value='".$this->lang['rname']."' name='renn'/> &nbsp; <input type='submit' value='".$this->lang['canl']."' name='Cancel'/><BR><BR>";
		}
		if (isset($_POST['renn'])){
			for ($i=0; $i < count($_POST['name']); $i++)
			{
				$orname = $_POST['name'][$i];
				$hash = $_POST['hash'][$i];
				$nname = $_POST['nname'][$i];
				$nname =Tools_get::convert_name($nname);
				$nname = str_replace($this->banned,'',$nname);
				if ($nname == "") { echo "<BR>".$this->lang['bname']."<BR><BR>"; return; }
				else {
					echo "<br/>";
					$key = "";
					foreach ($this->jobs as $url=>$job){
						if ($job['hash'] == $hash){	
							$key = $url;
							//$hash = $this->create_hash($key,$nname);
							$jobn = array(
								'hash'	=> $job['hash'],
								'path'	=> $job['path'],
								'filename'	=> urlencode($nname),
								'size'	=> $job['size'],
								'msize'	=> $job['msize'],
								'mtime'	=> $job['mtime'],
								'speed'	=> 0,
								'url'	=> $job['url'],
								'owner'	=> $job['owner'],
								'ip'	=> $job['ip'],
								'type'	=> 'direct',
								'directlink'	=> array(
									'url'	=> $job['directlink']['url'],
									'cookies'=> $job['directlink']['cookies'],
								),
							); 
						}	
					}
					if($key){
						$this->jobs[$key] = $jobn;
						$this->save_jobs();
						echo "File <b>$orname</b> ".$this->lang['rnameto']." <b>$nname</b>";
					}
					else echo "File <b>$orname</b> ".$this->lang['notfound'];
					echo "<br/><br />";
				}
			}
		}
		if (isset($_POST['Cancel'])){	
			$this->fulllist();
		}
	}

}
##################################### End class stream_get ###################################


##################################### Begin class Tools_get ###################################
class Tools_get extends getinfo {
	function useronline() {
		$data = @file_get_contents($this->fileinfo_dir."/online.dat");
		$online = @json_decode($data, true);	
		if (!is_array($online)){
			$online = array();
			$data = 'vng';
		}
		$online[$_SERVER['REMOTE_ADDR']] = time();

		### clean jobs ###
		$oldest = time() - 45;
		foreach ($online as $ip=>$time) {
			if ($time < $oldest) unset($online[$ip]);
		}
		### clean jobs ###
		/*-------------- save --------------*/
		$tmp = json_encode($online);
		if ($tmp !== $data){
			$data = $tmp;
			$fh = fopen($this->fileinfo_dir."/online.dat", 'w') 
			or die('<CENTER><font color=red size=3>Could not open file ! Try to chmod the folder "<B>'.$this->fileinfo_dir.'</B>" to 777</font></CENTER>');
			fwrite($fh, $data) 
			or die('<CENTER><font color=red size=3>Could not write file ! Try to chmod the folder "<B>'.$this->fileinfo_dir.'</B>" to 777</font></CENTER>');
			fclose($fh);
			@chmod($this->fileinfo_dir."/online.dat", 0666);
		}
		/*-------------- /save --------------*/
		return count($online);
	}
	function size_name($link,$cookie){
		if(!$link || !stristr($link,'http')) return;
		$link = str_replace(" ","%20",$link);
		$port = 80;
		$schema = parse_url(trim($link));
		$host= $schema['host'];
		$scheme = "http://";
		if(empty($schema['path']))  return;
		$gach = explode("/", $link);
		list($path1, $path)  = explode($gach[2], $link);
		if(isset($schema['port'])) $port = $schema['port'];
		elseif ($schema['scheme'] == 'https') {
			$scheme = "ssl://";
			$port = 443;
		}
		if ($scheme != "ssl://") {
			$scheme = "";
		}
		$data = "GET {$path} HTTP/1.1\r\n";
		$data .= "User-Agent: ".$this->UserAgent."\r\n";
		$data .= "Host: {$host}\r\n";
		$data .= $cookie ? "Cookie: $cookie\r\n" : '';
		$data .= "Connection: Close\r\n\r\n";
		$errno = 0;
		$errstr = "";

		$hosts = $scheme . $host . ':' . $port;
		$fp = @stream_socket_client ($hosts, $errno, $errstr, 20, STREAM_CLIENT_CONNECT );
		if (! $fp) return -1;

		fputs ( $fp, $data );
		fflush ( $fp );
		$header = "";
		do {
			if(!$header) {
				$header .= fgets ( $fp, 8192 );
				if(!stristr($header,"HTTP/1")) break;
			}
			else $header .= fgets ( $fp, 8192 );
		} while ( strpos ( $header, "\r\n\r\n" ) === false );

		if(stristr($header,"TTP/1.0 200 OK") || stristr($header,"TTP/1.1 200 OK") || stristr($header,"TTP/1.1 206")) 
			$filesize = trim ($this->cut_str ( $header, "Content-Length:", "\n" ) );
		else $filesize = -1;
		if(!is_numeric($filesize)) $filesize = -1;
		$filename = "";
		if(stristr($header,"filename")) {
			$filename = trim ($this->cut_str ( $header, "filename", "\n" ) );
		}
		else $filename = substr(strrchr($link, '/'), 1);
		$filename = self::convert_name($filename);
		return array($filesize,$filename);
	}

	function site_hash($url){
		     if(strpos($url,"rapidshare.com"))	$site = "RS"; //rename_prefix
		else if(strpos($url,"filefactory.com"))	$site = "FF";
		else if(strpos($url,"netload.in"))		$site = "NL";
		else if(strpos($url,"depositfiles.com"))$site = "DF";
		else if(strpos($url,"uploading.com"))$site = "ULD";
		else if(strpos($url,"mediafire.com"))		$site = "MF";
		else {
			$schema = parse_url($url);
			$site = preg_replace("/(www\.|\.com|\.net|\.biz|\.info|\.org|\.us|\.vn|\.jp|\.fr|\.in|\.to)/","",$schema['host']);
		}
		return $site;
	}
	function convert($filesize){
		$filesize = str_replace(",",".",$filesize);
		if(preg_match('/^([0-9]{1,4}+(\.[0-9]{1,2})?)/', $filesize,$value)){
			if(stristr($filesize,"TB"))		$value = $value[1]*1024*1024*1024*1024;
			elseif(stristr($filesize,"GB")) $value = $value[1]*1024*1024*1024;
			elseif(stristr($filesize,"MB")) $value = $value[1]*1024*1024;
			elseif(stristr($filesize,"KB")) $value = $value[1]*1024;
			else $value = $value[1];
		}
		else $value = 0;
		return $value;
	}
	function convertmb($filesize){
		if (!is_numeric($filesize)) return $filesize;
		$soam = false;
		if($filesize<0){
			$filesize = abs($filesize);
			$soam = true;
		}
		if($filesize >= 1024*1024*1024*1024) $value = ($soam ? "-" : "").round($filesize/(1024*1024*1024*1024), 2)." TB";
		elseif($filesize >= 1024*1024*1024) $value = ($soam ? "-" : "").round($filesize/(1024*1024*1024), 2)." GB";
		elseif($filesize >= 1024*1024) $value = ($soam ? "-" : "").round($filesize/(1024*1024), 2)." MB";
		elseif($filesize >= 1024) $value = ($soam ? "-" : "").round($filesize/(1024), 2)." KB";
		else $value = ($soam ? "-" : "").$filesize." Bytes";
		return $value;
	}
	function uft8html2utf8( $s ) {
		if ( !function_exists('uft8html2utf8_callback') ) {
			 function uft8html2utf8_callback($t) {
					 $dec = $t[1];
			if ($dec < 128) {
			  $utf = chr($dec);
			} else if ($dec < 2048) {
			  $utf = chr(192 + (($dec - ($dec % 64)) / 64));
			  $utf .= chr(128 + ($dec % 64));
			} else {
			  $utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
			  $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
			  $utf .= chr(128 + ($dec % 64));
			}
			return $utf;
			 }
		}                               
		return preg_replace_callback('|&#([0-9]{1,});|', 'uft8html2utf8_callback', $s );                                
	}

	function convert_name($filename){
		$filename =urldecode($filename);
		$filename =Tools_get::uft8html2utf8($filename);
		$filename = preg_replace("/(\]|\[|\@|\"\;\?\=|\"|=|\*|UTF-8|\')/","",$filename);
		$filename = preg_replace("/(HTTP|http|WWW|www|\.html|\.htm)/i","",$filename);
		$filename = str_replace($this->banned, '.xxx', $filename);
		if (empty($filename)==true) $filename =  substr(md5(time().$url), 0, 10);
		return $filename;
	}

	function convert_time($time){
		if($time >= 86400) $time = round($time/(60*24*60), 1)." ".$this->lang['days'];
		elseif(86400 > $time && $time >= 3600) $time = round($time/(60*60), 1)." ".$this->lang['hours'];
		elseif(3600 > $time && $time >= 60) $time = round($time/60, 1)." ".$this->lang['mins'];
		else $time = $time." ".$this->lang['sec'];

		return $time;
	}
	function report($url,$reason){
		if($reason == "dead"){
			$report ='<b><a href='.$url.' style="TEXT-DECORATION: none"><font color=red face=Arial size=2><s>'.$url.'</s></font></a> <img src=images/chk_error.png width="15" alt="errorlink"> <font color=#ffcc33 face=Arial size=2>'.$this->lang['dead'].'</font></b><BR>';
			return $report;
		}
		elseif($reason == "erroracc"){
			$report = '<center><B><a href='.$link.' style="TEXT-DECORATION: none"><font color=#00FF00 face=Arial size=2>'.$link.'</font></a> <img src=images/chk_good.png width="13" alt="g&#1086;&#1086;d_l&#1110;nk"> | <font color=#ffcc33 face=Arial size=2>'.$matches[1].'</font><font color=red face=Arial size=2> '.$this->lang['notwork'].'</font></B></center>';

			return $report;
		}
		elseif($reason == "svload"){
			$report = '<b><a href='.$url.' style="TEXT-DECORATION: none" title="please try again"><font color=#969696 face=Arial size=2>'.$url.'</font></a> <img src=images/chk_error.png width="15" alt="errorlink"></b> <font color=#ffcc33 face=Arial >'.$this->lang['again'].'</font>';
			return $report;
		}
		elseif($reason == "Unavailable") $reason = $this->lang['navailable'];
		elseif($reason == "disabletrial") $reason = $this->lang['disabletrial'];
		elseif($reason == "Adult") $reason = $this->lang['adult'];
		elseif($reason == "youtube_captcha") $reason = $this->lang['ytb_captcha'];
		elseif($reason == "ErrorLocating") $reason = $this->lang['ytb_Error'];
		$report ='<b><a href='.$url.' style="TEXT-DECORATION: none"><font color=red face=Arial size=2>'.$url.'</font></a> <img src=images/chk_error.png width="15" alt="errorlink"> <font color=#ffcc33 face=Arial size=2>'.$reason.'</font></b><BR>';
		return $report;
	}
}
##################################### End class Tools_get #####################################

?>