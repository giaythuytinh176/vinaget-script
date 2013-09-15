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
				var loadimg = "loading_white.gif";
				var loadcolor = "#3a87ad";
				var title = '<?php echo $obj->title; ?>';
				var colorname = '<?php echo $obj->colorfn; ?>';
				var colorfile = '<?php echo $obj->colorfs; ?>';
				var lang = new Array();
				<?php 
				foreach($obj->lang as $key=>$val){
					$val = str_replace("'", "\'", $val);
					echo "lang['{$key}'] = '{$val}'; ";
				}
				?>
			</script> 
			
			<div class="hero-unit">
				<h1><?php printf($obj->lang['sitetile']) ?></h1>
				<strong><p><?php printf($obj->lang['welcome'],$obj->lang['homepage']); ?></p></strong>
			</div>
			
<div id="showlistlink" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">List Links</h3>
</div>
<div class="modal-body">

<div id="listlinks"><textarea rows="20" cols="10" id="textarea"></textarea></div>

</div>
<div class="modal-footer ">
<button class="btn" onclick="return bbcode('list');">BBCode</button>
<button class="btn" id ='SelectAll'>Select All</button>
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
</div>
</div>

			<div id="plugins" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="pluginsLabel" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="pluginsLabel"><?php printf($obj->lang['plugins']); ?></h3>
				</div>
				<div class="modal-body">
				<?php
				foreach ($host as $key => $val){
					if(isset($plugin[$val['site']])) $plugin[$val['site']] .= "{$key}\n";
					else $plugin[$val['site']] = "{$key}\n";
				}
				foreach($plugin as $key=>$val){		// Thanks to shahril@rapidleech.com  - Fixed lag when loading new vng
				  $val = substr($val, 0, -1);
				  $icon = "./skin/bootstrap/icons/{$key}.png";
				  if(file_exists($icon)) echo "<img src='{$icon}' title='{$val}' alt='{$val}'/> ";
				  echo $val.'<br>';
				}
				?>
				</div>
					<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				</div>
			</div>
			

    <div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">

		<aside class="sidebar well well-small">
			<h5><div align="center"><?php printf($obj->lang['premium']); ?></div></h5><br />
			<?php showPlugin(); ?>
		</aside>

		<aside class="sidebar well well-small">
			<h5><div align="center">Stats:</div></h5><br />
			<?php showStat(); ?>
		</aside>

<aside class="sidebar well well-small">
<div align="center">
			<h5>Donate:</h5><br />
			<?php showDonate(); ?>
</div>

		</aside>
			</div>	






	    <div class="span9">
		<div class="navbar">
			<div class="navbar-inner">

					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<div class="nav-collapse">
						<ul class="nav">

							<li><a href="index.php"><i class="icon-home"></i> <?php printf($obj->lang['main']); ?></a></li>

								<li class="dropdown">
   <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-briefcase"></i> Management<b class="caret"></b></a>
						<ul class="dropdown-menu">
						<li><a href="./?id=donate"><i class="icon-user"></i> <?php printf($obj->lang['donate']); ?></a></li>
						<li><a href="./?id=check"><i class="icon-repeat"></i> <?php printf($obj->lang['check']); ?></a></li>
							<li><a href="./?id=listfile"><i class="icon-file"></i> <?php printf($obj->lang['listfile']); ?></a></li>
									</ul>
								</li>

							<?php if ($obj->Secure || $obj->isadmin()) 
							echo '<li><a href="./?id=admin"><i class="icon-cog"></i> '.$obj->lang['admin'].'</a></li>'; ?>
							
																	<ul class="nav pull-right">
															<li><a href="#plugins" data-toggle="modal"><span class="badge badge-success"><?php echo count($host);?></span> <?php printf($obj->lang['plugins']); ?></a></li>
													
																				<?php if ($obj->Secure) 
	echo '<li><a href="./login.php?go=logout"><i class="icon-off"></i> '.$obj->lang['log'].'</a></li>'; ?>
													</ul>
													</ul>
					</div>
			</div>
		</div>	
			

				<!-- ########################## Begin Menu ########################## -->


				<!-- ########################## Begin Plugins ########################## -->
				
							<div align="center">
	
<?php 
						#---------------------------- begin list file ----------------------------#
						if ((isset($_GET['id']) && $_GET['id']=='listfile') || isset($_POST['listfile']) || isset($_POST['option']) || isset($_POST['renn']) || isset($_POST['remove']))  {
							if($obj->listfile || $obj->isadmin())$obj->fulllist();
							else echo "<BR><BR><font color=red size=2>".$obj->lang['notaccess']."</b></font>";
						}
						#---------------------------- end list file ----------------------------#

						#---------------------------- begin donate  ----------------------------#
						else if (isset($_GET['id']) && $_GET['id']=='donate') { 
?>

								<div id="wait"><font color="#FF3300"><?php printf($obj->lang['donations1']); ?><br/><?php printf($obj->lang['donations2']); ?></font></div><BR>
								<form action="javascript:donate(document.getElementById('donateform'));" name="donateform" id="donateform">
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
												&nbsp; &nbsp; &nbsp; <input type="text" name="accounts" id="accounts" value="" size="50"><br />
											<button class="btn btn-primary" id="submit" type="submit"><?php printf($obj->lang['sbdonate']); ?></button>
								</form>
								<div id="check"><font color=#FF6600>user:pass</font> or <font color=#FF6600>cookie</font></div><br />
<?php					
						}
						#---------------------------- end donate  ----------------------------#

						#---------------------------- begin check  ---------------------------#
						else if (isset($_GET['id']) && $_GET['id']=='check'){
							if($obj->checkacc || $obj->isadmin()) include("checkaccount.php");
							else echo "<BR><BR><font color=red size=2>".$obj->lang['notaccess']."</b></font>";
						}
						#---------------------------- end check  ------------------------------#
						
						#---------------------------- begin admin  ---------------------------#
						else if (isset($_GET['id']) && $_GET['id']=='admin'){
							if($obj->isadmin()) include("admin.php");
							else echo "<BR><BR><font color=red size=2>".$obj->lang['notaccess']."</b></font>";
						}
						#---------------------------- end admin  ------------------------------#
						
						#---------------------------- begin get  ------------------------------#
						else {
?>
							<form action="javascript:get(document.getElementById('linkform'));" name="linkform" id="linkform">

								<?php if($obj->isadmin()){
									$obj->last_version = $obj->getversion();
									if($obj->last_version > $obj->current_version)
										echo '<br><font color="#dbac58"><b>'.sprintf($obj->lang['update1']).'</b> - <a href="http://www.rapidleech.com/index.php/topic/14663-dev-vinaget-v270-beta/">'.sprintf($obj->lang['update2'],$obj->last_version).'</a></font>'; 
								}
								?>
								<br /><?php printf($obj->lang['homepage']);?></font> - <?php printf($obj->lang['welcome']);?><br>
								<textarea id='links' style='width:550px;height:100px;' name='links'></textarea><BR>
								<font face=Arial size=1><span style="font-familty: Arial; color: #000000; font-size: 10px">Example: http://www.megaupload.com/?d=ABCDEXYZ<font size="3">|</font>password</span></font><BR>
								<button class="btn btn-primary" id="submit" type="submit"><?php printf($obj->lang['sbdown']); ?></button>&nbsp;&nbsp;&nbsp;
								<button class="btn" onclick="reseturl();return false;">Reset</button>&nbsp;&nbsp;&nbsp;
								<input type="checkbox" name="autoreset" id="autoreset" checked>&nbsp;Auto reset&nbsp;&nbsp;&nbsp;
							</form><BR><BR>
							<div id="dlhere" align="left" style="display: none;">
								<BR><hr /><small style="color:#55bbff"><?php printf($obj->lang['dlhere']); ?></small>
								<div align="right"><a onclick="return bbcode('bbcode');" href="javascript:void(0)" style='TEXT-DECORATION: none'><font color=#FF6600>BB code</font></a>&nbsp;&nbsp;&nbsp;
								<a onclick="return makelist(document.getElementById('showresults').innerHTML);" href="#showlistlink" data-toggle="modal"><font color=#FF6600>Make List</font></a></div>
							</div>
							<div id="bbcode" align="center" style="display: none;"></div>
							<div id="showresults" align="center"></div>
<?php						
						}
						#---------------------------- end get  ------------------------------#
?>
				<!-- ########################## End Main ########################### -->

					<div style="width: 55%;">
						<!-- Start Server Info -->
						<div class="alert alert-info"><?php showNotice();?></div>
						<!-- End Server Info -->
						</div>
						<hr />

					<!-- Bootstrap skin by Rhuan Gonzaga (rhuangonzaga[@]gmail.com)-->	

						<script type="text/javascript" language="javascript" src="ajax.js?ver=1.0"></script> 
					<!-- Copyright please don't remove-->
						<STRONG><SPAN class='powered' style="font-size: 12px;">Code LeechViet. Developed by ..:: [H] ::..<br/>Powered by <a href='http://www.rapidleech.com/index.php/topic/14663-dev-vinaget-v270-beta/'><?php printf($obj->lang['version']); ?> Revision <?php printf($obj->current_version); ?></a> by [FZ]. Skin by Rhuan Gonzaga</SPAN><br/>
						<SPAN class='copyright' style="font-size: 12px;">Copyright 2009-<?php echo date('Y');?> by <a href='http://vinaget.us/'>http://vinaget.us</a>. All rights reserved. </SPAN><br /><br />
					<!-- Copyright please don't remove-->	
					</div>

		</body>
	</html>