<?php
$host = array(); $alias = array(); 
$alias['dfiles.eu'] = 'depositfiles.com';
$alias['dfiles.ru'] = 'depositfiles.com';
$alias['depositfiles.net'] = 'depositfiles.com';
$alias['depositfiles.org'] = 'depositfiles.com';
$alias['ul.to'] = 'uploaded.net';
$alias['uploaded.to'] = 'uploaded.net';
$folderhost = opendir ( "hosts/" );
while ( $hostname = readdir ( $folderhost ) ) {		
	if($hostname == "." || $hostname == ".." || strpos($hostname,"bak") || $hostname == "hosts.php") {continue;}
	if(stripos($hostname,"php")){
		$site = str_replace("_", ".", substr($hostname, 0, -4));
		if(isset($alias[$site])){
			$host[$site] = array(
				'alias' => true,
				'site' => $alias[$site],
				'file' => str_replace(".", "_", $alias[$site]).".php",
				'class' => "dl_".str_replace(array(".","-"), "_", $alias[$site])
			);
		}
		else{
			$host[$site] = array(
				'alias' => false,
				'site' => $site,
				'file' => $hostname,
				'class' => "dl_".str_replace(array(".","-"), "_", $site)
			);
		}
	}
}
closedir ( $folderhost );
?>
