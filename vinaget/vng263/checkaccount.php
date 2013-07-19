<?php
if (isset($_POST["check"])) {
	$check = false;
	#======================= begin check acc rapidsahare =======================#
	if($_POST["check"]== "RS"){
		if(count($obj->acc["rapidshare.com"]["accounts"])>0){
			echo '<table id="tableRS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts rapidshare.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["rapidshare.com"]["accounts"]); $i++){
				$account = $obj->acc["rapidshare.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data = $obj->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi","","sub=getaccountdetails&withcookie=1&withpublicid=1&login=".$user."&cbf=RSAPIDispatcher&cbid=2&password=".$pass);
					if(strpos($data,'Login failed')) { 
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownRS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownRS"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else $cookie  =  $obj->cut_str($data, "ncookie=","\\n");
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$cookie = preg_replace("/(enc=|Enc=|ENC=)/","",$cookie);
				$data =  $obj->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi","","sub=getaccountdetails&withcookie=1&withpublicid=1&withsession=1&cookie=".$cookie."&cbf=RSAPIDispatcher&cbid=1");			
				if(strpos($data,'Login failed')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownRS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownRS"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
				//Validity				
					preg_match('/billeduntil=([0-9]+)/', $data, $matches);
					if ($matches[1]==0){	
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownRS"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownRS"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					} 
					else { 
						if (time() > $matches[1]) { 
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownRS"><font color=red><b><s>'.date('H:i:s Y-m-d',$matches[1]).'</s></b></font></td>
							<td id="unknownRS"><font color=#330099><B>Expired</B></font></td></tr>';
							$delacc[] = $i;
						}
						else{
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownRS"><font color=red><b>'.date('H:i:s Y-m-d',$matches[1]).'</b></font></td>
							<td id="unknownRS"><font color=blue><B>Working</B></font></td></tr>';
						}
					}
				}
			}
			echo "</table>";
			$obj = new stream_get();
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["rapidshare.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc rapidsahare =======================#
	###########################################################################
	#======================= begin check acc hotfile.com =====================#
	elseif($_POST["check"]== "HF"){
		if(count($obj->acc["hotfile.com"]["accounts"])>0){
			echo '<table id="tableHF" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts hotfile.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["hotfile.com"]["accounts"]); $i++){
				$account = $obj->acc["hotfile.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data = $obj->curl("http://www.hotfile.com/login.php","","user=$user&pass=$pass");
					if(strpos($data,"Bad username/password combination")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownHF"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownHF"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						preg_match('/^Set-Cookie: auth=(.*?);/m', $data, $matches);
						$cookie = $matches[1];
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$cookie = preg_replace("/(auth=|AUTH=|Auth=)/","",$cookie);
				$ch = @curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://hotfile.com/myaccount.html");
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIE, "auth=$cookie");
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
				$data = curl_exec( $ch);
				curl_close($ch); 
				if(strpos($data,'Location: http://hotfile.com/')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownHF"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownHF"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'<div class="centerSide"><p><span>Free</span>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownHF"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownHF"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%<p>Premium until: <span class="rightSide">(.+) <b>%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownHF"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownHF"><font color=blue><B>Working</B></font></td></tr>';
					}
					else{
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownHF">unknown</td>
						<td id="unknownHF">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["hotfile.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc hotfile.com =======================#
	###########################################################################
	#======================= begin check acc depositfiles.com ================#
	elseif($_POST["check"]== "DF"){
		if(count($obj->acc["depositfiles.com"]["accounts"])>0){
			echo '<table id="tableDF" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts depositfiles.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["depositfiles.com"]["accounts"]); $i++){
				$account = $obj->acc["depositfiles.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data=$obj->curl("http://depositfiles.com/login.php?return=%2F","lang_current=en","go=1&login=$user&password=$pass");
					if(strpos($data,"Your password or login is incorrect")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownDF"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownDF"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						$cookie = $obj->GetCookies($data);
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("http://depositfiles.com/gold/payment_history.php",$cookie.';lang_current=en;',"");
				if(strpos($data,'Location: http://depositfiles.com/login.php')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownDF"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownDF"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'Your current status: FREE - member')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownDF"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownDF"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%You have Gold access until: <b>(.*)</b>%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownDF"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownDF"><font color=blue><B>Working</B></font></td></tr>';
					}
					else{
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownDF">unknown</td>
						<td id="unknownDF">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["hotfile.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc depositfiles.com ==================#
	###########################################################################
	#======================= begin check acc bitshare.com ====================#
	elseif($_POST["check"]== "BS"){
		if(count($obj->acc["bitshare.com"]["accounts"])>0){
			echo '<table id="tableBS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts bitshare.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["bitshare.com"]["accounts"]); $i++){
				$account = $obj->acc["bitshare.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$data=$obj->curl("http://bitshare.com/login.html","","user=$user&password=$pass&rememberlogin=&submit=Login");
					if(strpos($data,"Click here to login")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownBS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownBS"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						$cookie = $obj->GetCookies($data);
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("http://bitshare.com/myaccount.html",$cookie,"");
				if(strpos($data,'Location: http://bitshare.com')) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownBS"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownBS"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'<i>Basic</i>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownBS"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownBS"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(preg_match('%Valid until: ([0-9].++)%U', $data, $matches)) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownBS"><font color=red><b>'.$matches[1].'</b></font></td>
						<td id="unknownBS"><font color=blue><B>Working</B></font></td></tr>';
					}
					else{
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownBS">unknown</td>
						<td id="unknownBS">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["bitshare.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc bitshare.com ======================#
	###########################################################################
	#======================= begin check acc oron.com ========================#
	elseif($_POST["check"]== "OR"){
		if(count($obj->acc["oron.com"]["accounts"])>0){
			echo '<table id="tableOR" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts oron.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["oron.com"]["accounts"]); $i++){
				$account = $obj->acc["oron.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$page = $obj->curl("http://oron.com/login","lang=english","login=$user&password=$pass&op=login");
					if(strpos($page,"Incorrect Login or Password")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownOR"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownOR"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						$cookie = $obj->GetCookies($page);
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("http://oron.com/?op=my_account",$cookie.";lang=english","");
				if(strpos($data,"http://oron.com/login")) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownOR"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownOR"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'Upgrade to premium')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownOR"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownOR"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(strpos($data,'Premium Account expires')) {
						$infoacc = $obj->cut_str($data, "Premium Account expires", "<td><input");
						if(preg_match("%<td>(.*)</td>%U", $infoacc, $matches)) {
							$Validity = $matches[1];
							$infoacc = $obj->cut_str($data, "Download Traffic Available", "</tr>");
							if(preg_match("%<td>(.*)</td>%U", $infoacc, $matches)) $bw = " <font color=#000000>(".$matches[1].")</font>";
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownOR"><font color=red><b>'.$Validity.' &nbsp; '.$bw.'</b></font></td>
							<td id="unknownOR"><font color=blue><B>Working</B></font></td></tr>';
						}
						else {
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownOR">unknown</td>
							<td id="unknownOR">unknown</td></tr>';
						}
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownOR">unknown</td>
						<td id="unknownOR">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["oron.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc oron.com ==========================#
	###########################################################################
	#======================= begin check acc uploading.com ===================#
	elseif($_POST["check"]== "ULD"){
		if(count($obj->acc["uploading.com"]["accounts"])>0){
			echo '<table id="tableULD" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts uploading.com</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["uploading.com"]["accounts"]); $i++){
				$account = $obj->acc["uploading.com"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$tid = str_replace(".","12",microtime(true));
					$page = $obj->curl("http://uploading.com/general/login_form/?ajax","","email=$user&password=$pass");
					if(strpos($page,"password combination")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownULD"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownULD"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						$cookie = $obj->GetCookies($page);
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("http://uploading.com/profile/",$cookie,"");  
				if(strpos($data,"Sign up")) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownULD"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownULD"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'<dd>Basic')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownULD"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownULD"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(strpos($data,'<dt>Valid Until')) {
						$infoacc = $obj->cut_str($data, "<dt>Valid Until", "Renew");
						if(preg_match("%<dd>(.*) \(\<%U", $infoacc, $matches)) {
							$Validity = $matches[1];
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownULD"><font color=red><b>'.$Validity.'</b></font></td>
							<td id="unknownULD"><font color=blue><B>Working</B></font></td></tr>';
						}
						else {
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownULD">unknown</td>
							<td id="unknownULD">unknown</td></tr>';
						}
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownULD">unknown</td>
						<td id="unknownULD">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["uploading.com"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc uploading.com =====================#
	###########################################################################
	#======================= begin check acc uploaded.to =====================#
	elseif($_POST["check"]== "ULTO"){
		if(count($obj->acc["uploaded.to"]["accounts"])>0){
			echo '<table id="tableULTO" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
				<tr class="flisttblhdr" valign="bottom">
					<td width="30%"><B>accounts uploaded.to</B></td>
					<td width="15%"><b>Type</b></td>
					<td><b>Validity</b></td>
					<td width="20%"><b>Report</b></td>
				</tr>
			';
			for($i = 0; $i < count($obj->acc["uploaded.to"]["accounts"]); $i++){
				$account = $obj->acc["uploaded.to"]["accounts"][$i];
				if (stristr($account,':')) {
					list($user, $pass) = explode(':', $account);
					$account = substr($account, 0, 5).'****';
					$type = "account";
					$page = $obj->curl("http://uploaded.net/io/login",'',"id=$user&pw=$pass");
					if(strpos($page,"User and password do not match")) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownULTO"><font color=#CCFF00><b>Login failed !!!</b></font></td>
						<td id="unknownULTO"><font color=green><B>Removed</B></font></td></tr>';
						$delacc[] = $i;
						continue;
					}
					else {
						$cookie = $obj->GetCookies($page);
					}
				}
				else {
					$type = "cookie";
					$cookie = $account;
					$account = substr($account, 0, 15).'****';
				}
				$data = $obj->curl("uploaded.net",$cookie,"");  
				if(strpos($data,"registerfree")) { 
					echo '<tr class="flistmouseoff" align="center">
					<td><B>'.$account.'</B></td><td>'.$type.'</td>
					<td id="unknownULTO"><font color=#CCFF00><b>Login failed !!!</b></font></td>
					<td id="unknownULTO"><font color=green><B>Removed</B></font></td></tr>';
					$delacc[] = $i;
				}
				else{
					//Validity	
					if(strpos($data,'<em>Free</em>')) {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownULTO"><b><font color=#666666>FREE ACC</font></b></td>
						<td id="unknownULTO"><font color=green><B>Removed</B></font></b></td></tr>';
						$delacc[] = $i;
					}
					elseif(strpos($data,'<em>Premium</em>')) {
						$infoacc = $obj->cut_str($data, "Duration", "</tr>");
						if(preg_match("%<th>(.*)</th>%U", $infoacc, $matches)) {
							$Validity = $matches[1];
							$infoacc = $obj->cut_str($data, "For downloading", "/th>");
							if(preg_match("%<em class=\"cB\">(.*)</em>%U", $infoacc, $matches)) $bw = " <font color=#000000>(".$matches[1].")</font>";
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownOR"><font color=red><b>'.$Validity.' &nbsp; '.$bw.'</b></font></td>
							<td id="unknownOR"><font color=blue><B>Working</B></font></td></tr>';
						}
						else {
							echo '<tr class="flistmouseoff" align="center">
							<td><B>'.$account.'</B></td><td>'.$type.'</td>
							<td id="unknownULTO">unknown</td>
							<td id="unknownULTO">unknown</td></tr>';
						}
					}
					else {
						echo '<tr class="flistmouseoff" align="center">
						<td><B>'.$account.'</B></td><td>'.$type.'</td>
						<td id="unknownULTO">unknown</td>
						<td id="unknownULTO">unknown</td></tr>';
					}
				}
			}
			echo "</table>";		
			$obj = new stream_get();	
			if(isset($delacc)) {
				foreach ($delacc as $i) unset($obj->acc["uploaded.to"]["accounts"][$i]);
				unset($delacc);
				$check = true;
			}
		}
	}
	#======================= end check acc uploaded.to =======================#



	if($check == true && is_array($obj->acc) && count($obj->acc) > 0) {
		$str = "<?php";
		$str .= "\n";
		$str .= "\n\$this->acc = array(";
		$str .= "\n";
		$str .= "# Example: 'accounts'	=> array('user:pass','cookie'),\n";
		$str .= "# Example with letitbit.net: 'accounts'    => array('user:pass','cookie','prekey=xxxx'),\n";
		$str .= "\n";
		foreach ($obj->acc as $host => $accounts) {
			$str .= "\n	'".$host."'		=> array(";
			$str .= "\n								'max_size'	=> ".($accounts['max_size']?$accounts['max_size']:1024).",";
			$str .= "\n								'accounts'	=> array(";
			foreach ($accounts['accounts'] as $acc) {
				$str .= "'".$acc."',";
			}
			$str .= "),";
			$str .= "\n							),";
			$str .= "\n";
		}
		$str .= "\n);";
		$str .= $obj->max_size_other_host ? "\n\$this->max_size_other_host = ".$obj->max_size_other_host.";" : "\n\$this->max_size_other_host = 1024;";
		$str .= "\n";
		$str .= "\n?>";
		$accountPath = "account.php";
		$CF = fopen ($accountPath, "w")
		or die('<CENTER><font color=red size=3>could not open file! Try to chmod the file "<B>account.php</B>" to 666</font></CENTER>');
		fwrite ($CF, $str)
		or die('<CENTER><font color=red size=3>could not write file! Try to chmod the file "<B>account.php</B>" to 666</font></CENTER>');
		fclose ($CF); 
		@chmod($accountPath, 0666);
	}
}
else {

	echo '<div style="overflow: auto; height: auto; width: 800px;" align="left">'; 
	if(count($obj->acc["bitshare.com"]["accounts"])>0){
		echo '<table id="tableBS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts bitshare.com</B></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["bitshare.com"]["accounts"]); $i++){
			$account = $obj->acc["bitshare.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownBS">unknown</td><td id="unknownBS">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('BS');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts bitshare.com >>></font></a><BR><BR>";
		$checkall = true;
	}
	if(count($obj->acc["rapidshare.com"]["accounts"])>0){
		echo '<table id="tableRS" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts rapidshare.com</B></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["rapidshare.com"]["accounts"]); $i++){
			$account = $obj->acc["rapidshare.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownRS">unknown</td><td id="unknownRS">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('RS');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts rapidshare.com >>></font></a><BR><BR>";
		$checkall = true;
	}
	if(count($obj->acc["hotfile.com"]["accounts"])>0){
		echo '<table id="tableHF" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts hotfile.com</B></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["hotfile.com"]["accounts"]); $i++){
			$account = $obj->acc["hotfile.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownHF">unknown</td><td id="unknownHF">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('HF');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts hotfile.com >>></font></a><BR><BR>";
		$checkall = true;
	}
	if(count($obj->acc["depositfiles.com"]["accounts"])>0){
		echo '<table id="tableDF" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts depositfiles.com</B></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["depositfiles.com"]["accounts"]); $i++){
			$account = $obj->acc["depositfiles.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownDF">unknown</td><td id="unknownDF">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('DF');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts depositfiles.com >>></font></a><BR><BR>";
		$checkall = true;
	}
	if(count($obj->acc["oron.com"]["accounts"])>0){
		echo '<table id="tableOR" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts oron.com</B></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["oron.com"]["accounts"]); $i++){
			$account = $obj->acc["oron.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownOR">unknown</td><td id="unknownOR">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('OR');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts oron.com >>></font></a><BR><BR>";
		$checkall = true;
	}
	if(count($obj->acc["uploading.com"]["accounts"])>0){
		echo '<table id="tableULD" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts uploading.com</B></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["uploading.com"]["accounts"]); $i++){
			$account = $obj->acc["uploading.com"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownULD">unknown</td><td id="unknownULD">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('ULD');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts uploading.com >>></font></a><BR><BR>";
		$checkall = true;
	}
	if(count($obj->acc["uploaded.to"]["accounts"])>0){
		echo '<table id="tableULTO" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td width="30%"><B>accounts uploaded.to</B></td>
				<td width="15%"><b>Type</b></td>
				<td><b>Validity</b></td>
				<td width="20%"><b>Report</b></td>
			</tr>
		';
		for($i = 0; $i < count($obj->acc["uploaded.to"]["accounts"]); $i++){
			$account = $obj->acc["uploaded.to"]["accounts"][$i];
			if (stristr($account,':')) $type = "account";
			else $type = "cookie";
			$account = substr($account, 0, 5)."****";
			echo '<tr class="flistmouseoff" align="center"><td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknownULTO">unknown</td><td id="unknownULTO">unknown</td></tr>';
		}
		echo "</table>";
		echo "<a onclick=\"checkacc('ULTO');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>Check accounts uploaded.to >>></font></a><BR><BR>";
		$checkall = true;
	}
	if(isset($checkall)) echo '<p align="right"><input type=button onclick="checkacc(\'all\');" value="Check all accounts"></p>';
}
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
?>