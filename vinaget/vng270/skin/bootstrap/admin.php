<?php
echo '<h3><a href="?id=admin&page=config">Config</a> | 
	  <a href="?id=admin&page=host">Host</a> | 
	  <a href="?id=admin&page=account">Account</a> | 
	  <a href="?id=admin&page=cookie">Cookie</a></h3>';

$page = isset($_GET['page']) ? $_GET['page'] : 'config';
echo "<form method='POST' action='proccess.php?page={$page}'>";
if($obj->msg) echo "<b>{$obj->msg}</b>";
if($page == 'config'){
	include ("config.php");
	echo '<table id="tableCONFIG" class="table table-bordered" align="center"><thead><tr>
				<th><B>CONFIG</B></th>
				<th><B>VALUE</B></th>
		</tr></thead>';
	if ($handle = opendir('lang/')) {
		$blacklist = array('.', '..', '', ' ');
		$lang = "<select name='config[language]'>";
		while (false !== ($file = readdir($handle))) {
			if (!in_array($file, $blacklist))
				$lang .= "<option value='".substr($file,0,-4)."' ".(substr($file,0,-4)==$obj->config['language'] ? "selected" : "").">".substr($file,0,-4)."</option>";
		}
		$lang .= "</select>";
		closedir($handle);
	}
	if ($handle = opendir('skin/')) {
		$blacklist = array('.', '..', '', ' ');
		$skin = "<select name='config[skin]'>";
		while (false !== ($file = readdir($handle))) {
			if (!in_array($file, $blacklist))
				$skin .= "<option value='".$file."' ".($file==$obj->config['skin'] ? "selected" : "").">".$file."</option>";
		}
		$skin .= "</select>";
		closedir($handle);
	}
	unset($obj->config['skin']);
	unset($obj->config['language']);
	foreach($obj->config as $ckey => $cval){
		echo '<tr><td><i><b>'.$ckey.'</b></i></td><td style="text-align:lef">';
		if(gettype($cval) == 'string' || gettype($cval) == 'integer') echo '<input size="40" type="text" name="config['.$ckey.']" value="'.$cval.'">';
		elseif(gettype($cval) == 'boolean') echo '<label for="config['.$ckey.'][\'on\']"><input type="radio" id="config['.$ckey.'][\'on\']" value="on" name="config['.$ckey.']"'.($cval ? ' checked="checked"' : '').'/> On</label> <label for="config['.$ckey.'][\'off\']"><input type="radio" id="config['.$ckey.'][\'off\']" value="off" name="config['.$ckey.']"'.(!$cval ? ' checked="checked"' : '').'/> Off</label>';
		echo '</td></tr>';
	}
	
	echo '<tr><td><i><b>language</b></i></td><td>'.$lang.'</td></tr>';
	echo '<tr><td><i><b>skin</b></i></td><td>'.$skin.'</td></tr>';
	echo "</table>";
?>	<br/>
	<center>
	        <button class="btn btn-primary" name="submit" id="submit" type="submit">Save Config</button>
	</center>
	<br/>
<?php
}
elseif($page == 'cookie'){
?>
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
		&nbsp; &nbsp; &nbsp; <button class="btn btn-primary" type="submit">Submit</button>
	</td>
	</tr>
	</table>
<?php
	echo '<table id="tableCOOKIE" class="table table-bordered">
			<thead><tr>
				<td><B>SERVER</B></td>
				<td colspan="2"><B>COOKIE</B></td>
			</tr></thead>
		';
	foreach ($obj->cookies as $ckey=>$cookies){
		if($cookies['cookie'] != "") echo '<tr class="flistmouseoff"><td><B>'.$ckey.'</B></td><td>'.$cookies['cookie'].'</td><td><a style="color: black;" href="proccess.php?page=cookie&del='.$ckey.'">[X]</a></td></tr>';
	}
	echo "</table>";
}
elseif($page == 'account'){
?>
	<table>
	<tr>
	<td>
	<?php printf($obj->lang['acctype']); ?> 
		<select name='type' id='type'>
	<?php
		foreach($host as $key => $val) {
			if(!$val['alias']){
				echo "<option value='{$key}'>{$key}</option>";
			}
		}
	?>
	</select>
	</td>
	<td>
		&nbsp; &nbsp; &nbsp; <input type="text" name="account" id="accounts" value="" size="50"><br />
	</td>
	<td>
		&nbsp; &nbsp; &nbsp; <button class="btn btn-primary" type="submit">Submit</button>
	</td>
	</tr>
	</table>
<?php
	echo '<table id="tableAccount" class="table table-bordered">
			<thead><tr class="flisttblhdr" valign="bottom">
				<td><B>Server</B></td>
				<td colspan="2"><B>Account</B></td>
			</tr></thead>
		';
	foreach ($obj->acc as $ckey=>$val){
		$max = count($val['accounts']);
		if($max != 0){
			for($i=0;$i<$max;$i++){
				echo '<tr class="flistmouseoff"><td><B>'.$ckey.'</B></td><td>'.$val['accounts'][$i].'</td><td><a style="color: black;" href="proccess.php?page=account&del='.$i.'&host='.$ckey.'">Delete</a></td></tr>';
			}
		}
	}
	echo "</table>";
}
elseif($page == 'host'){
	echo '<table id="tableHOST" class="table table-bordered">
			<thead><tr>
				<td align="center"><B>Host</B></td>
				<td align="center"><B>Max Size</B></td>
				<td align="center"><B>Proxy</B></td>
				<td align="center"><B>Direct</B></td>
			</tr></thead>
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
	echo "<button class='btn btn-primary' id='submit' name='submit' type='submit'>Save</button>";
}
else{
	echo "<b>Page not available</b>";
}
echo "</form>";
?>