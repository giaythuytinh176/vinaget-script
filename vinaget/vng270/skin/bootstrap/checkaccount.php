<?php
if (isset($_POST["check"])) {
	$check = $_POST["check"];
	$del = array();
	$acc = $obj->acc[$check];
	if(count($acc["accounts"])>0){
		require_once ('hosts/' . $obj->list_host[$check]['file']);
		$download = new $obj->list_host[$check]['class']($obj, $check);
		echo 
		'<table id="table-'.$check.'" class="table table-bordered">
              <thead>
                <tr>
                  <th><b>'.$check.'</b></th>
                  <th><b>Type</b></th>
                  <th><b>Status</b></th>
                  <th><b>Note</b></th>
                </tr>
              </thead>
              <tbody>
                <tr>';
		$i = 0;
		foreach($acc["accounts"] as $account){
			$type = stristr($account,':') ? "account" : "cookie";
			if(method_exists($download, "CheckAcc")) {
				if ($type == "account") {
					list($user, $pass) = explode(':',$account);
					$cookie = $download->Login($user, $pass);
				}
				else $cookie = $account;
				$status = $download->CheckAcc($cookie);
			}
			else $status = array(false, "noplugin");
			$msgs = isset($obj->lang[$status[1]]) && $obj->lang[$status[1]] != "" ? sprintf($obj->lang[$status[1]], $check) : $status[1];
			if($status[0]) {
				$msg = array("<font color='green'><b>{$obj->lang['work']}</b></font>", "<font color='black'><b>{$msgs}</b></font>");
				$download->save($cookie);
			}
			else{
				if($status[1] == "noplugin") $msg = array("unknown", $msgs);
				else{
					$msg = array("<font color='red'><b>{$obj->lang['notwork']}</b></font>", "<font color='black'><b>{$msgs} {$obj->lang['removed']}</b></font>");
					$del[$check][$i] = true;
					$update = true;
				}
			}
			if(!$obj->isadmin()) $account = substr($account, 0, 5)."****";
			echo '<td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknown-'.$check.'">'.$msg[0].'</td><td id="unknown-'.$check.'">'.$msg[1].'</td></tr></tbody>';
			$i++;
		}
		echo "</table>";
	}
	
	if($update == true && is_array($obj->acc) && count($obj->acc) > 0) {
		foreach($del as $host=>$acc){
			$tmp = $obj->acc[$host]['accounts'];
			unset($obj->acc[$host]['accounts']);
			foreach($tmp as $key=>$val){
				if($acc[$key] == true) continue;
				$obj->acc[$host]['accounts'][] = $val;
			}
		}
		$obj->save_json($obj->fileaccount, $obj->acc);
	}
}
else {
	foreach($obj->acc as $host => $acc){
		if(count($acc["accounts"])>0){
			echo '<table id="table-'.$host.'" class="table table-bordered">
              <thead>
                <tr>
                  <th><b>'.$host.'</b></th>
                  <th><b>Type</b></th>
                  <th><b>Status</b></th>
                  <th><b>Note</b></th>
                </tr>
              </thead>
              <tbody>
                <tr>';
			foreach($acc["accounts"] as $account){
				if (stristr($account,':')) $type = "account";
				else $type = "cookie";
				if(!$obj->isadmin()) $account = substr($account, 0, 5)."****";
				echo '<td><B>'.$account.'</B></td><td>'.$type.'</td><td id="unknown-'.$host.'">'.$obj->lang['unknown'].'</td><td id="unknown-'.$host.'">'.$obj->lang['unknown'].'</td></tr>';
			}
			echo "</tbody></table>";
			echo "<a onclick=\"checkacc('{$host}');\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600>".sprintf($obj->lang['checkacc'], $host)."</font></a><BR><BR>";
			$checkall = true;
		}
	}
	//if(isset($checkall)) echo '<p align="right"><input type=button onclick="checkacc(\'all\');" value="Check all accounts"></p>';
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.7.0
* Description: 
	- Vinaget is script generator premium link that allows you to download files instantly and at the best of your Internet speed.
	- Vinaget is your personal proxy host protecting your real IP to download files hosted on hosters like RapidShare, megaupload, hotfile...
	- You can now download files with full resume support from filehosts using download managers like IDM etc
	- Vinaget is a Free Open Source, supported by a growing community.
* Code LeechViet by VinhNhaTrang
* Developed by ..:: [H] ::..

*/
?>