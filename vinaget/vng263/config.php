<?php

$Secure = true;			#true : private host - false : public host
$password= array(
"demo"					# pass1
,"test"					# pass2
,"pass"					# pass3
); 



$homepage = "vinaget.us";
$fileinfo_dir = 'data';		# name folder data
$fileinfo_ext = "dat";		# type file data
$filecookie ="cookie.php";	# file cookie

$download_prefix = "vinaleech.com_";
$limitMBIP = 100*1024;	# limit load file for 1 IP (MB)
$ttl = 6*60;				# time to live (in minutes)
$limitPERIP = 10;		# limit file per mins, chmod 777 to folder tmp (files)
$ttl_ip = 1;			# limit load file per time (in minutes)
$max_jobs_per_ip = 100;	//total jobs for 1 IP  per time live
$max_jobs = 500;			# max total jobs in this host   
$max_load = 50;			# max server load (%)

$title = "[color=blue] download [/color]"; # Example: [color=blue]http://vinaleeech.com[/color]
$colorfilename = "green";
$colorfilesize = "red";

$ziplink = true;			#true : enable Zip URL to http://adf.ly - false : disable Zip URL to http://adf.ly
# if you want support me, please register from my Referrals ==> http://adf.ly/?id=343503
$apiadf = "http://api.adf.ly/api.php?key=94793cf6c45d36ed3d008d098fcfb964&uid=343503&advert_type=int&domain=adf.ly&url=";
$listfile = true;		# enable/disable all user can see list files.
$privatefile = false;	# enable/disable other people can see your file in the list files
$privateip = false;		# enable/disable other people can download your file.
$checkacc = true;		# enable/disable all user can use check account.
$checklinksex =  true;	# enable/disable check link 3x,porn...

$action = array(		# action with file in server files, set to true to enable, set to false to disable
'rename' => true,
'delete' => true,
);

# List of Bad Words, you can add more
$badword = array("porn","jav ", "Uncensored","xxx japan", "tora.tora", "tora-tora", "SkyAngle", "Sky_Angel", "Sky.Angel", "Incest","fuck", "Virgin", "PLAYBOY", "Adult", "tokyo hot", "Gangbang", "BDSM", "Hentai", "lauxanh", "homosexual", "bitch" , "Torture", "Nurse", "dâm đãng", "cực dâm", "phim cấp 3", "phim 18+", " Hentai", "Sex Videos", "Adult", "Adult XXX", "XXX movies", "Free Sex", "hardcore", "rape", "jav4u", "javbox", "jav4you", "akiba-online.com","JAVbest.ORG","X-JAV","cnnwe.com","J4v.Us","J4v.Us","teendaythi.com","entnt.com","khikhicuoi","sex-scandal.us","hotavxxx.com"); 


require_once ('languages.php');
?>