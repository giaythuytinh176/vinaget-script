<?php
$host = array();
$folderhost = opendir ( "hosts/" );
while ( $hostname = readdir ( $folderhost ) )	{		
	if ($hostname == "." || $hostname == ".." || strpos($hostname,"bak")== true || $hostname == "hosts.php") {continue;}
	if(strpos($hostname,"php") || strpos($hostname,"PHP")){
		$host[] = $hostname;
	}
}
closedir ( $folderhost );
?>
