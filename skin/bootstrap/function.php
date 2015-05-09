<?php
function showDonate(){
	echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="3XUFFU48VV28W">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>';
}
function showStat(){
	global $obj;
	echo "<b>{$obj->notice("yourip")}</b><span class='label label-info'>{$_SERVER['REMOTE_ADDR']}</span><br/> <b>{$obj->notice("yourjob")}</b><span class='label label-info'>{$obj->notice("userjobs")}</span><br/> <b>{$obj->notice("youused")}</b><span class='label label-info'>{$obj->notice("used")}</span><br/>";
	echo "<b>{$obj->notice("sizelimit")}</b><span class='label label-info'>{$obj->notice("maxsize")}</span><br/> <b>{$obj->notice("totjob")}</b><span class='label label-info'>{$obj->notice("totjobs")}</span><br/> <b>{$obj->notice("serverload")}</b><span class='label label-info'>{$obj->notice("maxload")}</span><br/> <b>{$obj->notice("uonline")}</b><span class='label label-info'>{$obj->notice("useronline")}</span><br/>";
}
function showNotice(){
	global $obj;
	echo "<blink>{$obj->notice("notice")}</blink><br/>";
}
function showPlugin(){
	global $obj;
	foreach($obj->acc as $host => $value) {
		$xout = array('');
		$xout = $obj->acc[$host]['accounts'];
		$max_size = $obj->acc[$host]['max_size'];
		if (empty($xout[0]) == false && empty($host) == false) {
			$hosts[] = '<img src="skin/bootstrap/icons/' . $host . '.png" /> ' . $host . ' <span class="badge badge-success">' . count($xout) . '</span><br/>';
		}
	}
	if (isset($hosts)) {
		if (count($hosts) > 4) {
			for ($i = 0; $i < 5; $i++) echo "$hosts[$i]";
			echo "<div id=showacc style='display: none;'>";
			for ($i = 5; $i < count($hosts); $i++) echo "$hosts[$i]";
			echo "</div>";
		}
		else for ($i = 0; $i < count($hosts); $i++) echo "$hosts[$i]";
		if (count($hosts) > 4) echo "<a onclick=\"showOrHide();\" href=\"javascript:void(0)\" style='TEXT-DECORATION: none'><font color=#FF6600><div id='moreacc'>" . $obj->lang['moreacc'] . "</div></font></a>";
	}
	return false;
}
?>