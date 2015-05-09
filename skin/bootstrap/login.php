<!DOCTYPE html>
<head>
	<link rel="SHORTCUT ICON" href="images/vngicon.png" type="image/x-icon" />
	<title><?php printf($obj->lang['title'],$obj->lang['version']); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="keywords" content="<?php printf($obj->lang['version']); ?>, download, get, vinaget, file, generator, premium, link, sharing, bitshare.com, crocko.com, depositfiles.com, extabit.com, filefactory.com, filepost.com, filesmonster.com, freakshare.com, gigasize.com, hotfile.com, jumbofiles.com, letitbit.net, mediafire.com, megashares.com, netload.in, oron.com, rapidgator.net, rapidshare.com, ryushare.com, sendspace.com, share-online.biz, shareflare.net, uploaded.to, uploading.com" />
	<meta author="Code by [FZ] && Bootstrap Skin by Rhuan Gonzaga" />
	<link href="<?php echo $skin;?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>
<body>	
<script type="text/javascript" language="javascript" src="images/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $skin;?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="images/sprintf.js"></script>
<script type="text/javascript" language="javascript">
	var title = '<?php echo $obj->title; ?>';
	var	colorname = '<?php echo $obj->colorfn; ?>';
	var colorfile = '<?php echo $obj->colorfs; ?>';
	var lang = new Array();
	<?php 
	foreach($obj->lang as $key=>$val){
		$val = str_replace("'", "\'", $val);
		echo "lang['{$key}'] = '{$val}'; ";
	}
	?>
</script> 	
	<div class="modal-backdrop fade in"> </div>
	<div class="hero-unit">
		<h1><?php printf($obj->lang['sitetile']) ?></h1>
		<strong><p><?php printf($obj->lang['welcome'],$obj->lang['homepage']); ?></p></strong>
	</div>
	<form action="login.php" method="POST" style="margin:0px;">
		<div id="login" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="false" style="display: block; top:30%">
			<div class="modal-header">
				<h3 id="loginLabel"><?php printf($obj->lang['login']); ?></h3>
			</div>
			<div class="modal-body">
				<br/><br/>
				<table class="table" style="border-bottom: 1px solid #dddddd;"><tr>
				<td><b><?php printf($obj->lang['password']); ?></b></div></td>
				<td>:</td>
				<td><input style="margin-bottom: 0px;" type="password" name="secure"/></td>
				</tr></table>
			</div>
			<div class="modal-footer">
				<input class="btn" aria-hidden="true" name="submit" type="submit" value="Submit"/>
			</div>
		</div>
	</form>
	<!-- Bootstrap skin by Rhuan Gonzaga (rhuangonzaga[@]gmail.com)-->	
	<script type="text/javascript" language="javascript" src="ajax.js?ver=1.0"></script> 
	<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
	<div align="center">
	<!-- Copyright please don't remove-->
	<strong><span class='powered' style="font-size: 12px;">Code LeechViet. Developed by ..:: [H] ::..<br/>Powered by <a href='http://www.rapidleech.com/index.php/topic/14663-dev-vinaget-v270-beta/'><?php printf($obj->lang['version']); ?> Revision <?php printf($obj->current_version); ?></a> by [FZ]. Skin by Rhuan Gonzaga</span><br/>
	<span class='copyright' style="font-size: 12px;">Copyright 2009-<?php echo date('Y');?> by <a href='http://vinaget.us/'>http://vinaget.us</a>. All rights reserved. </span><br /><br /></strong>
	<!-- Copyright please don't remove-->	
	</div>
</body>
</html>