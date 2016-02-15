<? include("function.php") ; ?>
<!DOCTYPE html>
<head>
	<link rel="SHORTCUT ICON" href="images/vngicon.png" type="image/x-icon" />
	<title><?php printf($obj->lang['title'],$obj->lang['version']); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="keywords" content="<?php printf($obj->lang['version']); ?>, download, get, vinaget, file, generator, premium, link, sharing, bitshare.com, crocko.com, depositfiles.com, extabit.com, filefactory.com, filepost.com, filesmonster.com, freakshare.com, gigasize.com, hotfile.com, jumbofiles.com, letitbit.net, mediafire.com, megashares.com, netload.in, oron.com, rapidgator.net, rapidshare.com, ryushare.com, sendspace.com, share-online.biz, shareflare.net, uploaded.to, uploading.com" />
	<meta author="Code by [FZ] && Bootstrap Skin by Rhuan Gonzaga" />
	<link href="https://logboost.com/resources/css/lbbutton.css" rel="stylesheet" />
    <link href="https://logboost.com/resources/iconmoon/style.css" rel="stylesheet" />
    <link href="<?php echo $skin;?>/css/style.css" rel="stylesheet" />
	<link href="<?php echo $skin;?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $skin;?>/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
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
	<? if(isset($login_showadmin)) { ?>
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
					<td><input class="form-control" style="margin-bottom: 0px;" type="password" name="secure"/></td>
					</tr></table>
				</div>
				<div class="modal-footer">
					<input class="btn" aria-hidden="true" name="submit" type="submit" value="Submit"/>
				</div>
			</div>
		</form>
	<? } else { ?>
			<div id="login" class="modal modal-login hide fade in" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="false" style="display: block; top:30%;width:800px ;">
				<div class="modal-header">
					<h3 id="loginLabel"><?php printf($obj->lang['please_choose_plan']); ?></h3>
				</div>
				<div class="modal-body">
					<br/><br/>
					<div style="width:49%;display:inline-block">
					<table class="table" style="border-bottom: 1px solid #dddddd;">
					<th>
							Free access
					</th>
					<tr>
						<td>
							<i class="fa fa-remove fa-red"></i> Ads
						</td>
					</tr>
					<tr>
						<td>
							<i class="fa fa-remove fa-red"></i> <? printf(($obj->config['limitMBIP']/1024)); ?> Gb max
						</td>
					</tr>
					<tr>
						<td>
							<i class="fa fa-remove fa-red"></i> <? printf($obj->config['max_jobs_per_ip']); ?> parallels jobs
						</td>
					</tr>
					<tr>
						<td>
							<i class="fa fa-remove fa-red"></i> <? countFreePlugin() ; ?> hosters available
						</td>
					</tr>
					<tr>
						<td>
							<i class="fa fa-remove fa-red"></i> Shared download limit
						</td>
					</tr>
					<tr>
					<td colspan="3" style="text-align:center">
						<button type="button" class="lbbtn lbbtn-lg lbbtn-freeaccess" onClick="window.location.href = 'login.php?method=freeaccess'">
	                        <i class="fa fa-unlock"></i>
	                        <span> Free access</span>
	                    </button>
					</td>
					</tr><tr>
					</tr>
					</table>
					</div>
					<div style="width:49%;display:inline-block">
					<table class="table" style="border-bottom: 1px solid #dddddd;">
					<th>
							Premium access
					</th>
					<tr>
						<td>
							<i class="fa fa-check fa-green"></i> No ads
						</td>
						<tr>
						<td>
							<i class="fa fa-check fa-green"></i> <? printf(($obj->config['logboost_limitMBIP']/1024)); ?> Gb max
						</td>
					</tr>
					<tr>
						<td>
							<i class="fa fa-check fa-green"></i> <? printf($obj->config['logboost_max_jobs_per_ip']); ?> parallels jobs
						</td>
					</tr>
					<tr>
						<td>
							<i class="fa fa-check fa-green"></i> <? countPremiumPlugin() ?> hosters available
						</td>
					</tr>
					<tr>
						<td>
							<i class="fa fa-check fa-green"></i> No shared download limit
						</td>
					</tr>
					</tr>
					<tr>
					<td colspan="3" style="text-align:center">
	                    <button type="button" class="lbbtn lbbtn-lg lbbtn-woodcub" onClick="window.location.href = 'login.php?method=logboost'">
	                        <i class="iconmoon-logboost"></i>
	                        <span> Connect with Logboost</span>
	                    </button>
	   				</td>
					</tr><tr>
					</tr>
					</table>
					</div>
				</div>
			</div>
	<? } ?>

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
