<?php
echo '<h3><a href="?id=admin&page=config">Config</a> | 
	  <a href="?id=admin&page=host">Host</a> | 
	  <a href="?id=admin&page=account">Account</a> | 
	  <a href="?id=admin&page=cookie">Cookie</a></h3>';

$page = isset($_GET['page']) ? $_GET['page'] : 'config';
if($page == 'config'){
	if(isset($_POST['submit'])){
		foreach($_POST['config'] as $ckey => $cval){
			if($cval == 'on' || $cval == 'off') $cval = $cval == 'on' ? true : false;
			elseif(is_numeric($cval)) $cval = intval($cval);
			else $cval = $cval;
			$obj->config[$ckey] = $cval;
		}
		$obj->save_json($obj->fileconfig, $obj->config);
		echo "<b>Config Saved! Redirecting...</b>";
		echo "<script>setTimeout('window.location = \"index.php\"', 1000);</script>";
	}
?>
	<form method="post">
<?php
	include ("config.php");
	echo '<table id="tableCONFIG" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td align="center" colspan="2"><B>CONFIG</B></td>
			</tr>
		';
	foreach($obj->config as $ckey => $cval){
		echo '<tr class="flistmouseoff"><td><i><b>'.$ckey.'</b></i></td><td style="text-align:right">';
		if(gettype($cval) == 'string' || gettype($cval) == 'integer') echo '<input size="40" type="text" name="config['.$ckey.']" value="'.$cval.'">';
		elseif(gettype($cval) == 'boolean') echo '<label for="config['.$ckey.'][\'on\']"><input type="radio" id="config['.$ckey.'][\'on\']" value="on" name="config['.$ckey.']"'.($cval ? ' checked="checked"' : '').'/> On</label> <label for="config['.$ckey.'][\'off\']"><input type="radio" id="config['.$ckey.'][\'off\']" value="off" name="config['.$ckey.']"'.(!$cval ? ' checked="checked"' : '').'/> Off</label>';
		echo '</td></tr>';
	}
	echo "</table>";
?>	<br/>
	<center>
		<input id='submit' type='submit' name="submit" value='Save Config'/>
	</center>
	<br/>
</form>
<?php
}
elseif($page == 'cookie'){
	if(isset($_POST['cookie'])){
		$obj->save_cookies($_POST['type'], $_POST['cookie']);
		echo "<b>{$_POST['type']} cookie added</b>";
	}
	elseif(isset($_GET['del'])){
		$obj->save_cookies($_GET['del'], "");
		echo "<b>{$_GET['del']} cookie deleted</b>";
	}
?>
<form method="POST" name="donateform" id="donateform">
	<table>
	<tr>
	<td>
	<?php printf($obj->lang['acctype']); ?> 
		<select name='type' id='type'>
	<?php
		foreach($host as $key => $val) {
			if(!$val['alias']){
				require_once ('hosts/' . $val['file']);
				if(method_exists($val['class'], "CheckAcc")) echo "<option value='{$key}'>{$key}</option>";
			}
		}
	?>
	</select>
	</td>
	<td>
		&nbsp; &nbsp; &nbsp; <input type="text" name="cookie" id="accounts" value="" size="50"><br />
	</td>
	<td>
		&nbsp; &nbsp; &nbsp; <input type=submit value="Submit">
	</td>
	</tr>
	</table>
</form>
<?php
	echo '<table id="tableCOOKIE" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td align="center" colspan="3"><B>COOKIE</B></td>
			</tr>
		';
	foreach ($obj->cookies as $ckey=>$cookies){
		if($cookies['cookie'] != "") echo '<tr class="flistmouseoff"><td><B>'.$ckey.'</B></td><td>'.$cookies['cookie'].'</td><td><a style="color: black;" href="?id=admin&page=cookie&del='.$ckey.'">[X]</a></td></tr>';
	}
	echo "</table>";
}
elseif($page == 'account'){
	if(isset($_POST['account'])){
		if(empty($obj->acc[$_POST['type']])) {
			$obj->acc[$_POST['type']]['max_size'] = $obj->max_size_default;
			$obj->acc[$_POST['type']]['proxy'] = "";
			$obj->acc[$_POST['type']]['direct'] = false;
		}
		$obj->save_account($_POST['type'], $_POST['account']);
		echo "<b>{$_POST['type']} account added</b>";
	}
	elseif(isset($_GET['del']) && isset($_GET['host'])){
		unset($obj->acc[$_GET['host']]['accounts'][$_GET['del']]);
		$obj->save_json($obj->fileaccount, $obj->acc);
		echo "<b>Account deleted</b>";
	}
?>
<form method="POST" name="donateform" id="donateform">
	<table>
	<tr>
	<td>
	<?php printf($obj->lang['acctype']); ?> 
		<select name='type' id='type'>
	<?php
		foreach($host as $key => $val) {
			if(!$val['alias']){
				require_once ('hosts/' . $val['file']);
				if(method_exists($val['class'], "CheckAcc")) echo "<option value='{$key}'>{$key}</option>";
			}
		}
	?>
	</select>
	</td>
	<td>
		&nbsp; &nbsp; &nbsp; <input type="text" name="account" id="accounts" value="" size="50"><br />
	</td>
	<td>
		&nbsp; &nbsp; &nbsp; <input type=submit value="Submit">
	</td>
	</tr>
	</table>
</form>
<?php
	echo '<table id="tableAccount" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td align="center" colspan="3"><B>Account</B></td>
			</tr>
		';
	foreach ($obj->acc as $ckey=>$val){
		$max = count($val['accounts']);
		if($max != 0){
			for($i=0;$i<$max;$i++){
				echo '<tr class="flistmouseoff"><td><B>'.$ckey.'</B></td><td>'.$val['accounts'][$i].'</td><td><a style="color: black;" href="?id=admin&page=account&del='.$i.'&host='.$ckey.'">Delete</a></td></tr>';
			}
		}
	}
	echo "</table>";
}
elseif($page == 'host'){
	if(isset($_POST['host'])){
		foreach($_POST['host'] as $key=>$val){
			$obj->acc[$key]['max_size'] = $val['max_size'];
			$obj->acc[$key]['proxy'] = $val['proxy'];
			$obj->acc[$key]['direct'] = (isset($val['direct']) && $val['direct'] == "ON" ? true : false);
		}
		ksort($obj->acc);
		$obj->save_json($obj->fileaccount, $obj->acc);
		echo "<b>host setting changed</b>";
	}
	echo '<form method="post"><table id="tableHOST" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%">
			<tr class="flisttblhdr" valign="bottom">
				<td align="center"><B>Host</B></td>
				<td align="center"><B>Max Size</B></td>
				<td align="center"><B>Proxy</B></td>
				<td align="center"><B>Direct</B></td>
			</tr>
		';
	foreach ($obj->acc as $ckey=>$val){
		echo '<tr class="flistmouseoff">
				<td><B>'.$ckey.'</B></td>
				<td><input type="text" name="host['.$ckey.'][max_size]" value="'.$val['max_size'].'"/></td>
				<td><input type="text" name="host['.$ckey.'][proxy]" value="'.$val['proxy'].'"/></td>
				<td><input type="checkbox" name="host['.$ckey.'][direct]" value="ON" '.($val['direct'] ? 'checked' : '').'/></td>
			</tr>';
	}
	echo "</table>";
	echo "<input id='submit' type='submit' name='submit' value='Save Changed'/></form>";
}
else{
	echo "<b>Page not available</b>";
}
?>