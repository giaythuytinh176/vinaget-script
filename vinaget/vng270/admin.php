<?php
echo '<h3><a href="?id=admin&page=config">Config</a> | <a href="?id=admin&page=account">Account</a> | <a href="?id=admin&page=cookie">Cookie</a></h3>';

$page = isset($_GET['page']) ? $_GET['page'] : 'config';
if($page == 'config'){
	if(isset($_POST['submit'])){
		$newconfig = array();
		foreach($_POST['config'] as $ckey => $cval){
			if($cval == 'on' || $cval == 'off') $newconfig[$ckey] = ($cval == 'on' ? true : false);
			elseif(is_numeric($cval)) $newconfig[$ckey] = intval($cval);
			elseif(gettype($cval) == 'array') {
				foreach($cval as $cckey => $ccval){
					if($ccval == 'on' || $ccval == 'off') $newconfig[$ckey][$cckey] = ($ccval == 'on' ? true : false);
					elseif(is_numeric($ccval)) $newconfig[$ckey][$cckey] = intval($ccval);
					else $newconfig[$ckey][$cckey] = $ccval;
				}
			}
			else $newconfig[$ckey] = $cval;
		}
		$obj->config = $newconfig;
		$obj->save_json($obj->fileconfig, $obj->config);
		echo "<b>Config Saved!</b>";
	}
?>
	<form method="post">
		<div>
<?php
	include ("config.php");
	echo '
		<div>
			<div><table>';
	foreach($config as $ckey => $cval){
		$cval = $obj->config[$ckey] ? $obj->config[$ckey] : $cval;
		echo '<tr>
				<td><i><b>'.$ckey.'</b></i></td>
				<td>:</td><td style="text-align:right">';
		if(gettype($cval) == 'string' || gettype($cval) == 'integer') echo '<input size="40" type="text" name="config['.$ckey.']" value="'.$cval.'"></td>';
		elseif(gettype($cval) == 'boolean') echo '<label for="config['.$ckey.'][\'on\']"><input type="radio" id="config['.$ckey.'][\'on\']" value="on" name="config['.$ckey.']"'.($cval ? ' checked="checked"' : '').'/> On</label> <label for="config['.$ckey.'][\'off\']"><input type="radio" id="config['.$ckey.'][\'off\']" value="off" name="config['.$ckey.']"'.(!$cval ? ' checked="checked"' : '').'/> Off</label></td>';
		elseif(gettype($cval) == 'array') {
			foreach($cval as $cckey => $ccval){
				echo ' <b>'.$cckey.'</b>';
				if(gettype($ccval) == 'string' || gettype($ccval) == 'integer') echo '<input size="15" type="text" name="config['.$ckey.']['.$cckey.']" value="'.$ccval.'">';
				elseif(gettype($ccval) == 'boolean') echo '<td><label for="config['.$ckey.']['.$cckey.'][\'on\']"><input type="radio" id="config['.$ckey.']['.$cckey.'][\'on\']" value="on" name="config['.$ckey.']['.$cckey.']"'.($ccval ? ' checked="checked"' : '').'/> On</label> <label for="config['.$ckey.']['.$cckey.'][\'off\']"><input type="radio" id="config['.$ckey.']['.$cckey.'][\'off\']" value="off" name="config['.$ckey.']['.$cckey.']"'.(!$ccval ? ' checked="checked"' : '').'/> Off</label></td>';
			}
			echo '</td>';
		}
		echo '</tr>';
	}
	echo '
			</table></div>
		</div>';
?>
	</div>
	<br/>
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
		foreach($host as $file => $site) {
			$class = substr($site, 0, -4);
			$site = str_replace("_", ".", $class);
			$alias = false;
			require_once ('hosts/' . $host[$file]);
			if(!$alias){
				echo "<option value='{$site}'>{$site}</option>";
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
		if(empty($obj->acc[$_POST['type']])) $obj->acc[$_POST['type']]['max_size'] = $obj->max_size_default;
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
		foreach($host as $file => $site) {
			$class = substr($site, 0, -4);
			$site = str_replace("_", ".", $class);
			$alias = false;
			require_once ('hosts/' . $host[$file]);
			if(!$alias){
				echo "<option value='{$site}'>{$site}</option>";
			}
		}
	?>
	</select>
	</td>
	<td>
		&nbsp; &nbsp; &nbsp; <input type="text" name="account" id="accounts" value="" size="50" maxlength="50"><br />
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
else{
	echo "<b>Page not available</b>";
}
?>